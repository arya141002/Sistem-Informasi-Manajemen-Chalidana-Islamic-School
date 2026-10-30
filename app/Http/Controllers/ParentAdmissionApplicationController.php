<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\Campus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File as FileRule;

class ParentAdmissionApplicationController extends Controller
{
    public function edit()
    {
        abort_unless(auth()->user()?->account_type === 'parent', 403);

        $application = AdmissionApplication::query()
            ->where('user_id', auth()->id())
            ->latest('id')
            ->first();

        if ($application?->status === 'submitted') {
            return redirect()
                ->route('parent.dashboard')
                ->with('admission_success', 'Formulir sudah dikirim dan tidak dapat diedit lagi.');
        }

        $campuses = Campus::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('parent.application.form', compact('application', 'campuses'));
    }


    public function indexAdmin(Request $request)
    {
        $search = trim($request->query('search', ''));
        $status = $request->query('status');
    
        $applications = $this->adminApplicationsQuery()
            ->with(['user', 'campus'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('registration_code', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%")
                      ->orWhere('school_destination', 'like', "%{$search}%")
                      ->orWhereHas('campus', fn ($c) => $c->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();
    
        $staffCampuses = auth()->user()->role?->name === 'administration_staff'
            ? auth()->user()->campuses
            : collect();

        return view('admin.ppdb', compact('applications', 'search', 'status', 'staffCampuses'));
    }

    public function showAdmin(int $id)
    {
        $application = $this->adminApplicationsQuery()
            ->with(['user', 'campus'])
            ->findOrFail($id);

        return response()->json($application);
    }

    public function downloadPdf(int $id)
    {
        ini_set('memory_limit', '512M');

        $application = $this->adminApplicationsQuery()
            ->with(['user', 'campus'])
            ->findOrFail($id);

        $pdf = Pdf::setOption([
            'chroot' => [base_path(), public_path(), storage_path('app/private')],
        ])->loadView('admin.ppdb_pdf', compact('application'));

        return $pdf->download('ppdb-application-' . ($application->registration_code ?? $application->id) . '.pdf');
    }
    
    public function downloadHealthPdf(int $id)
    {
        ini_set('memory_limit', '512M');
    
        $query = $this->adminApplicationsQuery()->with(['user', 'campus']);

        $application = $query->findOrFail($id);
    
        $pdf = Pdf::loadView('admin.ppdb_health_pdf', compact('application'));
    
        return $pdf->download(
            'ppdb-health-' . ($application->registration_code ?? $application->id) . '.pdf'
        );
    }

    public function downloadCsv()
    {
        $applications = $this->adminApplicationsQuery()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $filename = 'ppdb-applications-' . now()->format('YmdHis') . '.csv';
        $headers = [
            'Registration Code',
            'Status',
            'Full Name',
            'Nickname',
            'Birth Date',
            'Education Level',
            'School Destination',
            'Phone',
            'Email',
            'Parent Address',
            'Father Name',
            'Mother Name',
            'Created At',
        ];

        $callback = function () use ($applications, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->registration_code,
                    $application->status,
                    $application->full_name,
                    $application->nickname,
                    optional($application->birth_date)->format('Y-m-d'),
                    $application->education_level,
                    $application->campus?->title ?? $application->school_destination,
                    $application->phone,
                    $application->email,
                    $application->parent_address,
                    $application->father_name,
                    $application->mother_name,
                    optional($application->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function adminApplicationsQuery()
    {
        $user = auth()->user();
        $roleName = $user?->role?->name;
        $allowedRoles = [
            'super_admin',
            'managing_director',
            'administrator',
            'administration_staff',
            'principal',
        ];

        abort_unless($user && in_array($roleName, $allowedRoles, true), 403);

        $query = AdmissionApplication::query();

        if (in_array($roleName, ['administration_staff', 'principal'], true)) {
            $campusIds = $user->campuses()->pluck('campuses.id');
            if ($campusIds->isEmpty() && $user->campus_id) {
                $campusIds = collect([$user->campus_id]);
            }

            abort_unless($campusIds->isNotEmpty(), 403, 'Akun staff belum memiliki cabang.');
            $query->whereIn('campus_id', $campusIds);
        }

        return $query;
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->account_type === 'parent', 403);

        $application = AdmissionApplication::query()
            ->where('user_id', auth()->id())
            ->latest('id')
            ->first();

        if ($application?->status === 'submitted') {
            return redirect()
                ->route('parent.dashboard')
                ->with('admission_success', 'Formulir sudah dikirim dan tidak dapat diedit lagi.');
        }

        $isContinue = $request->input('action') === 'continue';
        $validated = $request->validate($this->rules($isContinue, $application));

        $validated['ever_out_of_school'] = $request->boolean('ever_out_of_school');
        $validated['had_special_therapy'] = $request->boolean('had_special_therapy');
        if (!empty($validated['campus_id'])) {
            $validated['school_destination'] = Campus::findOrFail($validated['campus_id'])->title;
        }
        $validated['status'] = $isContinue ? 'biodata_completed' : 'draft';
        $validated['registration_date'] = $application?->registration_date;
        $documents = $this->documentFields();

        foreach (array_keys($documents) as $field) {
            unset($validated[$field]);
        }

        $application ??= new AdmissionApplication([
            'user_id' => auth()->id(),
            'registration_code' => $this->makeRegistrationCode(),
        ]);

        $application->fill($validated);

        foreach ($documents as $field => $directory) {
            if ($request->hasFile($field)) {
                if ($application->{$field . '_path'}) {
                    Storage::disk('local')->delete($application->{$field . '_path'});
                }

                $uploadedFile = $request->file($field);
                $extension = $this->safeDocumentExtension($uploadedFile);
                abort_unless($extension, 422, 'Format dokumen tidak didukung.');

                $filename = Str::uuid()->toString() . '.' . $extension;
                $application->{$field . '_path'} = $uploadedFile->storeAs(
                    'admission-applications/' . auth()->id() . '/' . $directory,
                    $filename,
                    'local'
                );
            }
        }

        $application->save();

        if ($isContinue) {
            return redirect()->route('parent.application.health.edit');
        }

        return redirect()
            ->route('parent.dashboard')
            ->with('admission_success', 'Draft formulir pendaftaran berhasil disimpan.');
    }

    public function editHealth()
    {
        abort_unless(auth()->user()?->account_type === 'parent', 403);

        $application = AdmissionApplication::query()
            ->where('user_id', auth()->id())
            ->latest('id')
            ->firstOrFail();

        if ($application->status === 'submitted') {
            return redirect()
                ->route('parent.dashboard')
                ->with('admission_success', 'Formulir sudah dikirim dan tidak dapat diedit lagi.');
        }

        return view('parent.application.health', compact('application'));
    }

    public function storeHealth(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->account_type === 'parent', 403);

        $application = AdmissionApplication::query()
            ->where('user_id', auth()->id())
            ->latest('id')
            ->firstOrFail();

        if ($application->status === 'submitted') {
            return redirect()
                ->route('parent.dashboard')
                ->with('admission_success', 'Formulir sudah dikirim dan tidak dapat diedit lagi.');
        }

        $isSubmit = $request->input('action') === 'submit';
        $validated = $request->validate($this->healthRules($isSubmit));
        $validated['basic_immunizations'] = $request->input('basic_immunizations', []);
        $validated['health_statement_approved'] = $request->boolean('health_statement_approved');
        $validated['status'] = $isSubmit ? 'submitted' : 'health_draft';
        $validated['registration_date'] = $isSubmit
            ? ($application->registration_date ?? now()->toDateString())
            : $application->registration_date;

        $application->update($validated);

        return redirect()
            ->route('parent.dashboard')
            ->with('admission_success', $isSubmit
                ? 'Formulir pendaftaran dan data kesehatan berhasil dikirim.'
                : 'Draft data kesehatan berhasil disimpan.');
    }

    private function rules(bool $isSubmit, ?AdmissionApplication $application = null): array
    {
        $required = $isSubmit ? 'required' : 'nullable';
        $requiredDocuments = collect(array_keys($this->documentFields()))
            ->mapWithKeys(fn ($field) => [
                $field => [
                    $application?->{$field . '_path'} ? 'nullable' : $required,
                    FileRule::types(['jpg', 'jpeg', 'png', 'pdf'])->max(5120),
                    'mimetypes:image/jpeg,image/png,application/pdf',
                ],
            ])
            ->all();

        $rules = [
            'full_name' => [$required, 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'gender' => [$required, 'in:laki-laki,perempuan'],
            'birth_place' => [$required, 'string', 'max:255'],
            'birth_date' => [$required, 'date'],
            'education_level' => [$required, 'string', 'max:255'],
            'campus_id' => [$required, 'integer', 'exists:campuses,id'],
            'academic_year' => [$required, 'string', 'max:50'],
            'weight' => ['nullable', 'string', 'max:50'],
            'height' => ['nullable', 'string', 'max:50'],
            'head_circumference' => ['nullable', 'string', 'max:50'],
            'religion' => ['nullable', 'string', 'max:100'],
            'address' => [$required, 'string', 'max:2000'],
            'phone' => [$required, 'string', 'max:50'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'child_number' => ['nullable', 'string', 'max:50'],
            'language_at_home' => ['nullable', 'string', 'max:255'],
            'other_languages' => ['nullable', 'string', 'max:255'],
            'living_with' => ['nullable', 'string', 'max:255'],
            'distance_to_school' => ['nullable', 'string', 'max:100'],
            'email' => [$required, 'email', 'max:255'],
            'total_siblings' => ['nullable', 'string', 'max:50'],
            'older_siblings' => ['nullable', 'string', 'max:50'],
            'younger_siblings' => ['nullable', 'string', 'max:50'],
            'total_step_siblings' => ['nullable', 'string', 'max:50'],
            'older_step_siblings' => ['nullable', 'string', 'max:50'],
            'younger_step_siblings' => ['nullable', 'string', 'max:50'],
            'total_adopted_siblings' => ['nullable', 'string', 'max:50'],
            'older_adopted_siblings' => ['nullable', 'string', 'max:50'],
            'younger_adopted_siblings' => ['nullable', 'string', 'max:50'],
            'ever_out_of_school' => ['nullable', 'boolean'],
            'out_of_school_duration' => ['nullable', 'string', 'max:255'],
            'had_special_therapy' => ['nullable', 'boolean'],
            'therapy_type' => ['nullable', 'string', 'max:255'],
            'father_name' => [$required, 'string', 'max:255'],
            'father_birth_place' => ['nullable', 'string', 'max:255'],
            'father_birth_date' => ['nullable', 'date'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'father_income' => ['nullable', 'string', 'max:255'],
            'father_notes' => ['nullable', 'string', 'max:255'],
            'mother_name' => [$required, 'string', 'max:255'],
            'mother_birth_place' => ['nullable', 'string', 'max:255'],
            'mother_birth_date' => ['nullable', 'date'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'mother_income' => ['nullable', 'string', 'max:255'],
            'mother_notes' => ['nullable', 'string', 'max:255'],
            'parent_address' => [$required, 'string', 'max:2000'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_gender' => ['nullable', 'in:laki-laki,perempuan'],
            'guardian_birth_place' => ['nullable', 'string', 'max:255'],
            'guardian_birth_date' => ['nullable', 'date'],
            'guardian_religion' => ['nullable', 'string', 'max:100'],
            'guardian_occupation' => ['nullable', 'string', 'max:255'],
            'guardian_address' => ['nullable', 'string', 'max:2000'],
            'guardian_income' => ['nullable', 'string', 'max:255'],
        ];

        return array_merge($rules, $requiredDocuments);
    }

    private function documentFields(): array
    {
        return [
            'father_id_card' => 'father-id-card',
            'mother_id_card' => 'mother-id-card',
            'family_card' => 'family-card',
            'birth_certificate' => 'birth-certificate',
            'akta' => 'akta',
        ];
    }

    private function safeDocumentExtension($file): ?string
    {
        return match ($file->getMimeType()) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            default => null,
        };
    }

    private function healthRules(bool $isSubmit): array
    {
        $required = $isSubmit ? 'required' : 'nullable';

        return [
            'emergency_contact_1_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_1_phone' => ['nullable', 'string', 'max:50'],
            'emergency_contact_1_doctor' => ['nullable', 'string', 'max:255'],
            'emergency_contact_1_hospital' => ['nullable', 'string', 'max:255'],
            'emergency_contact_2_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_2_phone' => ['nullable', 'string', 'max:50'],
            'emergency_contact_2_doctor' => ['nullable', 'string', 'max:255'],
            'emergency_contact_2_hospital' => ['nullable', 'string', 'max:255'],
            'basic_immunizations' => ['nullable', 'array'],
            'basic_immunizations.*' => ['string', 'max:100'],
            'allergy_food' => ['nullable', 'string', 'max:255'],
            'allergy_medicine' => ['nullable', 'string', 'max:255'],
            'allergy_other' => ['nullable', 'string', 'max:255'],
            'allergy_description' => ['nullable', 'string', 'max:2000'],
            'pregnancy_medicine_consumption' => ['nullable', 'string', 'max:2000'],
            'pregnancy_diseases' => ['nullable', 'string', 'max:2000'],
            'gestational_age' => ['nullable', 'string', 'max:255'],
            'delivery_process' => ['nullable', 'string', 'max:255'],
            'baby_birth_weight' => ['nullable', 'string', 'max:50'],
            'baby_birth_length' => ['nullable', 'string', 'max:50'],
            'routine_medication' => ['nullable', 'string', 'max:2000'],
            'surgery_history' => ['nullable', 'string', 'max:2000'],
            'hospitalization_history' => ['nullable', 'string', 'max:2000'],
            'previous_diseases' => ['nullable', 'string', 'max:2000'],
            'family_medical_history' => ['nullable', 'string', 'max:2000'],
            'health_statement_approved' => [$required, 'boolean'],
            'health_approved_city' => [$required, 'string', 'max:255'],
            'health_approved_date' => [$required, 'date'],
            'health_approved_by' => [$required, 'string', 'max:255'],
        ];
    }

    private function makeRegistrationCode(): string
    {
        do {
            $code = 'CIS-' . strtoupper(Str::random(6));
        } while (AdmissionApplication::query()->where('registration_code', $code)->exists());

        return $code;
    }
}
