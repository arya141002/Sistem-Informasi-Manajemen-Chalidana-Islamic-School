<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Health Data | {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ webp_asset('images/icon/CIS_Logo.webp') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('parent.dashboard') }}">
                <img src="{{ webp_asset('images/icon/CIS_Branding_Logo.webp') }}" alt="{{ config('app.name') }}" style="max-height: 54px;">
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('parent.application.edit') }}" class="btn btn-outline-primary rounded-pill px-4">Biodata</a>
                <form method="POST" action="{{ route('parent.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    @php
        $fieldValue = fn ($field, $default = '') => old($field, $application?->{$field} ?? $default);
        $dateValue = fn ($field) => old($field, optional($application?->{$field})->format('Y-m-d'));
        $immunizations = old('basic_immunizations', $application->basic_immunizations ?? []);
    @endphp

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-xl-8">
                    <div class="mb-4">
                        <h1 class="h3 mb-2">Data Kesehatan Siswa</h1>
                        <p class="text-muted mb-0">Lengkapi data kesehatan siswa sesuai formulir kesehatan CIS.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Mohon periksa kembali form kesehatan.</strong>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('parent.application.health.store') }}">
                        @csrf

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Informasi Dasar Siswa</h2>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Nama Lengkap Siswa</label><input type="text" class="form-control" value="{{ $application->full_name }}" readonly></div>
                                <div class="col-md-6"><label class="form-label">Nama Panggilan</label><input type="text" class="form-control" value="{{ $application->nickname }}" readonly></div>
                                <div class="col-md-4"><label class="form-label">Jenis Kelamin</label><input type="text" class="form-control" value="{{ $application->gender }}" readonly></div>
                                <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" class="form-control" value="{{ $application->birth_place }}" readonly></div>
                                <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input type="text" class="form-control" value="{{ optional($application->birth_date)->format('d M Y') }}" readonly></div>
                                <div class="col-md-6"><label class="form-label">Nama Ayah</label><input type="text" class="form-control" value="{{ $application->father_name }}" readonly></div>
                                <div class="col-md-6"><label class="form-label">Nama Ibu</label><input type="text" class="form-control" value="{{ $application->mother_name }}" readonly></div>
                                <div class="col-md-6"><label class="form-label">No. Telp/HP Orang Tua/Wali</label><input type="text" class="form-control" value="{{ $application->phone }}" readonly></div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-2">Kontak Darurat</h2>
                            <p class="text-muted mb-4">Keadaan darurat yang harus dihubungi selain orang tua/wali.</p>
                            @for($i = 1; $i <= 2; $i++)
                                <div class="row g-3 {{ $i > 1 ? 'mt-2 pt-3 border-top' : '' }}">
                                    <div class="col-12"><h3 class="h6 text-primary mb-0">Kontak Darurat {{ $i }}</h3></div>
                                    <div class="col-md-3"><label class="form-label">Nama</label><input type="text" name="emergency_contact_{{ $i }}_name" class="form-control" value="{{ $fieldValue('emergency_contact_' . $i . '_name') }}"></div>
                                    <div class="col-md-3"><label class="form-label">No. Telepon</label><input type="text" name="emergency_contact_{{ $i }}_phone" class="form-control" value="{{ $fieldValue('emergency_contact_' . $i . '_phone') }}"></div>
                                    <div class="col-md-3"><label class="form-label">Dokter</label><input type="text" name="emergency_contact_{{ $i }}_doctor" class="form-control" value="{{ $fieldValue('emergency_contact_' . $i . '_doctor') }}"></div>
                                    <div class="col-md-3"><label class="form-label">Rumah Sakit</label><input type="text" name="emergency_contact_{{ $i }}_hospital" class="form-control" value="{{ $fieldValue('emergency_contact_' . $i . '_hospital') }}"></div>
                                </div>
                            @endfor
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Imunisasi Dasar</h2>
                            <div class="row g-3">
                                @foreach([
                                    'BCG',
                                    'Hepatitis B',
                                    'Diphtheria/Tetanus/Pertussis',
                                    'Polio',
                                    'Campak',
                                    'Hepatitis A',
                                    'MMR',
                                    'Influenza',
                                    'Lain-lain',
                                ] as $immunization)
                                    <div class="col-md-4">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="basic_immunizations[]" value="{{ $immunization }}" @checked(in_array($immunization, $immunizations, true))>
                                            <span class="form-check-label">{{ $immunization }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Catatan Kesehatan</h2>
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Alergi Makanan</label><input type="text" name="allergy_food" class="form-control" value="{{ $fieldValue('allergy_food') }}"></div>
                                <div class="col-md-4"><label class="form-label">Alergi Obat</label><input type="text" name="allergy_medicine" class="form-control" value="{{ $fieldValue('allergy_medicine') }}"></div>
                                <div class="col-md-4"><label class="form-label">Alergi Lain-lain</label><input type="text" name="allergy_other" class="form-control" value="{{ $fieldValue('allergy_other') }}"></div>
                                <div class="col-12"><label class="form-label">Penjelasan Alergi</label><textarea name="allergy_description" class="form-control" rows="3">{{ $fieldValue('allergy_description') }}</textarea></div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Riwayat Kehamilan dan Kelahiran</h2>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Konsumsi Obat-obatan Saat Kehamilan</label><textarea name="pregnancy_medicine_consumption" class="form-control" rows="3">{{ $fieldValue('pregnancy_medicine_consumption') }}</textarea></div>
                                <div class="col-md-6"><label class="form-label">Penyakit Selama Kehamilan</label><textarea name="pregnancy_diseases" class="form-control" rows="3">{{ $fieldValue('pregnancy_diseases') }}</textarea></div>
                                <div class="col-md-6"><label class="form-label">Usia Kehamilan Saat Persalinan</label><input type="text" name="gestational_age" class="form-control" value="{{ $fieldValue('gestational_age') }}"></div>
                                <div class="col-md-6"><label class="form-label">Proses Persalinan</label><input type="text" name="delivery_process" class="form-control" value="{{ $fieldValue('delivery_process') }}"></div>
                                <div class="col-md-6"><label class="form-label">Berat Bayi Setelah Lahir</label><input type="text" name="baby_birth_weight" class="form-control" value="{{ $fieldValue('baby_birth_weight') }}" placeholder="kg"></div>
                                <div class="col-md-6"><label class="form-label">Panjang Bayi Setelah Lahir</label><input type="text" name="baby_birth_length" class="form-control" value="{{ $fieldValue('baby_birth_length') }}" placeholder="cm"></div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Riwayat Kesehatan</h2>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Obat yang Rutin Dikonsumsi</label><textarea name="routine_medication" class="form-control" rows="3">{{ $fieldValue('routine_medication') }}</textarea></div>
                                <div class="col-md-6"><label class="form-label">Riwayat Operasi</label><textarea name="surgery_history" class="form-control" rows="3">{{ $fieldValue('surgery_history') }}</textarea></div>
                                <div class="col-md-6"><label class="form-label">Riwayat Rawat Inap</label><textarea name="hospitalization_history" class="form-control" rows="3">{{ $fieldValue('hospitalization_history') }}</textarea></div>
                                <div class="col-md-6"><label class="form-label">Penyakit yang Pernah Diderita</label><textarea name="previous_diseases" class="form-control" rows="3">{{ $fieldValue('previous_diseases') }}</textarea></div>
                                <div class="col-12"><label class="form-label">Riwayat Penyakit Keluarga</label><textarea name="family_medical_history" class="form-control" rows="3">{{ $fieldValue('family_medical_history') }}</textarea></div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Pernyataan</h2>
                            <p class="text-muted">Kami sebagai orang tua/wali murid memberi kuasa kepada sekolah untuk memberikan pertolongan pertama apabila terjadi keadaan gawat darurat atau kecelakaan pada anak kami di sekolah.</p>
                            <label class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="health_statement_approved" value="1" @checked(old('health_statement_approved', $application->health_statement_approved))>
                                <span class="form-check-label">Saya menyetujui pernyataan data kesehatan ini.</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Kota</label><input type="text" name="health_approved_city" class="form-control" value="{{ $fieldValue('health_approved_city') }}"></div>
                                <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="health_approved_date" class="form-control" value="{{ $dateValue('health_approved_date') }}"></div>
                                <div class="col-md-4"><label class="form-label">Nama Orang Tua/Wali</label><input type="text" name="health_approved_by" class="form-control" value="{{ $fieldValue('health_approved_by', $application->father_name ?: $application->mother_name) }}"></div>
                            </div>
                        </section>

                        <div class="bg-white rounded shadow-sm p-4 mb-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                            <a href="{{ route('parent.application.edit') }}" class="btn btn-outline-secondary rounded-pill px-4 py-3">Kembali</a>
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <button type="submit" name="action" value="draft" class="btn btn-outline-primary rounded-pill px-4 py-3">Save Draft</button>
                                <button type="submit" name="action" value="submit" class="btn btn-primary rounded-pill px-4 py-3">Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4">
                    @include('parent._admission-steps', ['application' => $application])
                </div>
            </div>
        </div>
    </main>
</body>
</html>
