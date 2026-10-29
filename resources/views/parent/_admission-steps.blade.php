@props(['application' => null, 'currentStep' => null])

@php
    $currentRoute = request()->route()?->getName();
    $currentStep = $currentStep ?? match ($currentRoute) {
        'parent.dashboard' => 1,
        'parent.application.edit' => 2,
        'parent.application.health.edit' => 3,
        default => 1,
    };

    $studentCompleted = $application && in_array($application->status, ['draft', 'biodata_completed', 'health_draft', 'submitted'], true);
    $healthCompleted = $application && in_array($application->status, ['health_draft', 'submitted'], true);
    $documentUploaded = $application && collect([
        'student_photo',
        'father_id_card',
        'mother_id_card',
        'family_card',
        'birth_certificate',
        'report_card',
    ])->contains(fn ($field) => !empty($application->{$field . '_path'}));
    $reviewCompleted = $application?->status === 'submitted';

    $steps = [
        ['label' => 'Akun Parent', 'description' => 'Masuk dan kelola profil Anda.', 'completed' => true, 'active' => $currentStep === 1],
        ['label' => 'Informasi Siswa', 'description' => 'Isi data calon peserta didik.', 'completed' => $studentCompleted, 'active' => $currentStep === 2],
        ['label' => 'Data Kesehatan', 'description' => 'Lengkapi riwayat kesehatan siswa.', 'completed' => $healthCompleted, 'active' => $currentStep === 3],
        ['label' => 'Unggah Dokumen', 'description' => 'Unggah berkas admission yang diperlukan.', 'completed' => $documentUploaded, 'active' => $currentStep === 4],
        ['label' => 'Review Pendaftaran', 'description' => 'Tinjau dan kirim formulir lengkap.', 'completed' => $reviewCompleted, 'active' => $currentStep === 5],
    ];
@endphp

<div class="bg-primary text-white rounded shadow-sm p-4 h-100">
    <h4 class="text-white mb-3">Admission Steps</h4>
    <div class="d-flex flex-column gap-3">
        @foreach($steps as $step)
            <div class="d-flex gap-3 align-items-start">
                <span class="mt-1 {{ $step['completed'] ? 'fa fa-check-circle' : ($step['active'] ? 'fa fa-dot-circle' : 'far fa-circle') }}"></span>
                <div>
                    <div class="fw-semibold {{ $step['active'] ? 'text-white' : 'text-white-75' }}">{{ $step['label'] }}</div>
                    <div class="small text-white-50">{{ $step['description'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
