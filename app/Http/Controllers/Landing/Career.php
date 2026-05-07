<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\CampusArea;
use App\Models\CareerApplication;
use App\Models\Career as CareerModel;
use App\Models\Tagline;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Career extends Controller
{
    private function sharedData(): array
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return compact('tagline', 'campusAreas', 'settings');
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $careers = CareerModel::query()
            ->when(in_array($status, ['open', 'closed'], true), function (Builder $query) use ($status) {
                $query->where('career_status', $status === 'open' ? 1 : 0);
            })
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $inner) use ($search) {
                    $inner->where('title', 'like', '%' . $search . '%')
                        ->orWhere('title_id', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%')
                        ->orWhere('content_id', 'like', '%' . $search . '%');
                });
            })
            ->orderByRaw('CASE WHEN career_status = 1 THEN 0 ELSE 1 END')
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        return view('landing.career', array_merge(
            $this->sharedData(),
            compact('careers', 'search', 'status')
        ));
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'career_id' => [
                'required',
                Rule::exists('careers', 'id')->where(fn ($query) => $query
                    ->where('career_status', 1)
                    ->whereNull('deleted_at')),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'latest_education' => ['nullable', 'string', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'cover_message' => ['nullable', 'string', 'max:3000'],
        ]);

        if ($request->hasFile('cv_file')) {
            $validated['cv_path'] = $request->file('cv_file')->store('career-applications', 'public');
        }

        unset($validated['cv_file']);

        CareerApplication::create(array_merge($validated, [
            'status' => 'submitted',
            'source_locale' => current_locale(),
        ]));

        return back()
            ->withFragment('career-application')
            ->with('career_application_success', __('front.career_application_success'));
    }
}
