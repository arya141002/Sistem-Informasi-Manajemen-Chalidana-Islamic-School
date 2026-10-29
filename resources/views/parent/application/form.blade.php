<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admission Form | {{ config('app.name') }}</title>
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
                <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">Dashboard</a>
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
        $hasFile = fn ($field) => !empty($application?->{$field . '_path'});
    @endphp

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-xl-8">
                    <div class="mb-4">
                        <h1 class="h3 mb-2">Admission Application Form</h1>
                        <p class="text-muted mb-0">Lengkapi data sesuai formulir pendaftaran. Dokumen disimpan secara private dan hanya untuk proses admission.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Mohon periksa kembali form.</strong>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('parent.application.store') }}" enctype="multipart/form-data">
                        @csrf

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <div class="d-flex justify-content-between flex-wrap gap-3 mb-4">
                                <div>
                                    <h2 class="h5 mb-1">A. Keterangan Calon Peserta Didik</h2>
                                    <p class="text-muted mb-0">Student candidate information</p>
                                </div>
                                @if($application)
                                    <span class="badge bg-primary align-self-start px-3 py-2">{{ $application->registration_code }}</span>
                                @endif
                            </div>

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" value="{{ $fieldValue('full_name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nama Panggilan</label>
                                    <input type="text" name="nickname" class="form-control" value="{{ $fieldValue('nickname') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="laki-laki" @selected($fieldValue('gender') === 'laki-laki')>Laki-laki</option>
                                        <option value="perempuan" @selected($fieldValue('gender') === 'perempuan')>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="birth_place" class="form-control" value="{{ $fieldValue('birth_place') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control" value="{{ $dateValue('birth_date') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jenjang Pendidikan</label>
                                    <input type="text" name="education_level" class="form-control" value="{{ $fieldValue('education_level') }}" placeholder="Preschool / Primary / Secondary" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sekolah Tujuan</label>
                                    <select name="campus_id" class="form-select" required>
                                        <option value="">Pilih sekolah tujuan</option>
                                        @foreach($campuses as $campus)
                                            <option value="{{ $campus->id }}" @selected((string) $fieldValue('campus_id', $application?->campus_id) === (string) $campus->id || (!$application?->campus_id && $fieldValue('school_destination') === $campus->title))>{{ $campus->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tahun Ajaran</label>
                                    <input type="text" name="academic_year" class="form-control" value="{{ $fieldValue('academic_year') }}" placeholder="2026 / 2027" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Berat Badan</label>
                                    <input type="text" name="weight" class="form-control" value="{{ $fieldValue('weight') }}" placeholder="kg">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tinggi Badan</label>
                                    <input type="text" name="height" class="form-control" value="{{ $fieldValue('height') }}" placeholder="cm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Lingkar Kepala</label>
                                    <input type="text" name="head_circumference" class="form-control" value="{{ $fieldValue('head_circumference') }}" placeholder="cm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Agama</label>
                                    <input type="text" name="religion" class="form-control" value="{{ $fieldValue('religion') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ $fieldValue('address') }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nomor Telepon / HP</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $fieldValue('phone') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Asal Sekolah</label>
                                    <input type="text" name="previous_school" class="form-control" value="{{ $fieldValue('previous_school') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Anak Ke</label>
                                    <input type="text" name="child_number" class="form-control" value="{{ $fieldValue('child_number') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bahasa Pengantar di Rumah</label>
                                    <input type="text" name="language_at_home" class="form-control" value="{{ $fieldValue('language_at_home') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bahasa Lainnya</label>
                                    <input type="text" name="other_languages" class="form-control" value="{{ $fieldValue('other_languages') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tinggal Bersama</label>
                                    <input type="text" name="living_with" class="form-control" value="{{ $fieldValue('living_with') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jarak Tempat Tinggal ke Sekolah</label>
                                    <input type="text" name="distance_to_school" class="form-control" value="{{ $fieldValue('distance_to_school') }}" placeholder="Contoh: 4 km">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $fieldValue('email', auth()->user()->email) }}" required>
                                </div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">Informasi Saudara dan Riwayat</h2>
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Jumlah Saudara Kandung</label><input type="text" name="total_siblings" class="form-control" value="{{ $fieldValue('total_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Kakak Kandung</label><input type="text" name="older_siblings" class="form-control" value="{{ $fieldValue('older_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Adik Kandung</label><input type="text" name="younger_siblings" class="form-control" value="{{ $fieldValue('younger_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Jumlah Saudara Tiri</label><input type="text" name="total_step_siblings" class="form-control" value="{{ $fieldValue('total_step_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Kakak Tiri</label><input type="text" name="older_step_siblings" class="form-control" value="{{ $fieldValue('older_step_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Adik Tiri</label><input type="text" name="younger_step_siblings" class="form-control" value="{{ $fieldValue('younger_step_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Jumlah Saudara Angkat</label><input type="text" name="total_adopted_siblings" class="form-control" value="{{ $fieldValue('total_adopted_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Kakak Angkat</label><input type="text" name="older_adopted_siblings" class="form-control" value="{{ $fieldValue('older_adopted_siblings') }}"></div>
                                <div class="col-md-4"><label class="form-label">Adik Angkat</label><input type="text" name="younger_adopted_siblings" class="form-control" value="{{ $fieldValue('younger_adopted_siblings') }}"></div>
                                <div class="col-md-6">
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ever_out_of_school" value="1" @checked(old('ever_out_of_school', $application?->ever_out_of_school))>
                                        <span class="form-check-label">Pernah tidak sekolah sebelumnya?</span>
                                    </label>
                                    <input type="text" name="out_of_school_duration" class="form-control mt-2" value="{{ $fieldValue('out_of_school_duration') }}" placeholder="Jika ya, berapa lama?">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check">
                                        <input class="form-check-input" type="checkbox" name="had_special_therapy" value="1" @checked(old('had_special_therapy', $application?->had_special_therapy))>
                                        <span class="form-check-label">Pernah menjalani terapi khusus?</span>
                                    </label>
                                    <input type="text" name="therapy_type" class="form-control mt-2" value="{{ $fieldValue('therapy_type') }}" placeholder="Jika ya, jenis terapinya">
                                </div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">B. Keterangan Orang Tua</h2>
                            <div class="row g-3">
                                <div class="col-12"><h3 class="h6 text-primary mb-0">Informasi Ayah</h3></div>
                                <div class="col-md-6"><label class="form-label">Nama Ayah</label><input type="text" name="father_name" class="form-control" value="{{ $fieldValue('father_name') }}" required></div>
                                <div class="col-md-3"><label class="form-label">Tempat Lahir Ayah</label><input type="text" name="father_birth_place" class="form-control" value="{{ $fieldValue('father_birth_place') }}"></div>
                                <div class="col-md-3"><label class="form-label">Tanggal Lahir Ayah</label><input type="date" name="father_birth_date" class="form-control" value="{{ $dateValue('father_birth_date') }}"></div>
                                <div class="col-md-4"><label class="form-label">Pekerjaan Ayah</label><input type="text" name="father_occupation" class="form-control" value="{{ $fieldValue('father_occupation') }}"></div>
                                <div class="col-md-4"><label class="form-label">Penghasilan Ayah</label><input type="text" name="father_income" class="form-control" value="{{ $fieldValue('father_income') }}"></div>
                                <div class="col-md-4"><label class="form-label">Keterangan Ayah</label><input type="text" name="father_notes" class="form-control" value="{{ $fieldValue('father_notes') }}"></div>

                                <div class="col-12 mt-4"><h3 class="h6 text-primary mb-0">Informasi Ibu</h3></div>
                                <div class="col-md-6"><label class="form-label">Nama Ibu</label><input type="text" name="mother_name" class="form-control" value="{{ $fieldValue('mother_name') }}" required></div>
                                <div class="col-md-3"><label class="form-label">Tempat Lahir Ibu</label><input type="text" name="mother_birth_place" class="form-control" value="{{ $fieldValue('mother_birth_place') }}"></div>
                                <div class="col-md-3"><label class="form-label">Tanggal Lahir Ibu</label><input type="date" name="mother_birth_date" class="form-control" value="{{ $dateValue('mother_birth_date') }}"></div>
                                <div class="col-md-4"><label class="form-label">Pekerjaan Ibu</label><input type="text" name="mother_occupation" class="form-control" value="{{ $fieldValue('mother_occupation') }}"></div>
                                <div class="col-md-4"><label class="form-label">Penghasilan Ibu</label><input type="text" name="mother_income" class="form-control" value="{{ $fieldValue('mother_income') }}"></div>
                                <div class="col-md-4"><label class="form-label">Keterangan Ibu</label><input type="text" name="mother_notes" class="form-control" value="{{ $fieldValue('mother_notes') }}"></div>
                                <div class="col-12"><label class="form-label">Alamat Orang Tua</label><textarea name="parent_address" class="form-control" rows="3" required>{{ $fieldValue('parent_address') }}</textarea></div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-4">C. Keterangan Wali</h2>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Nama Wali</label><input type="text" name="guardian_name" class="form-control" value="{{ $fieldValue('guardian_name') }}"></div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin Wali</label>
                                    <select name="guardian_gender" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="laki-laki" @selected($fieldValue('guardian_gender') === 'laki-laki')>Laki-laki</option>
                                        <option value="perempuan" @selected($fieldValue('guardian_gender') === 'perempuan')>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label">Tempat Lahir Wali</label><input type="text" name="guardian_birth_place" class="form-control" value="{{ $fieldValue('guardian_birth_place') }}"></div>
                                <div class="col-md-4"><label class="form-label">Tanggal Lahir Wali</label><input type="date" name="guardian_birth_date" class="form-control" value="{{ $dateValue('guardian_birth_date') }}"></div>
                                <div class="col-md-4"><label class="form-label">Agama Wali</label><input type="text" name="guardian_religion" class="form-control" value="{{ $fieldValue('guardian_religion') }}"></div>
                                <div class="col-md-6"><label class="form-label">Pekerjaan Wali</label><input type="text" name="guardian_occupation" class="form-control" value="{{ $fieldValue('guardian_occupation') }}"></div>
                                <div class="col-md-6"><label class="form-label">Penghasilan Wali</label><input type="text" name="guardian_income" class="form-control" value="{{ $fieldValue('guardian_income') }}"></div>
                                <div class="col-12"><label class="form-label">Alamat Wali</label><textarea name="guardian_address" class="form-control" rows="3">{{ $fieldValue('guardian_address') }}</textarea></div>
                            </div>
                        </section>

                        <section class="bg-white rounded shadow-sm p-4 p-lg-5 mb-4">
                            <h2 class="h5 mb-2">D. Lampiran Dokumen</h2>
                            <p class="text-muted mb-4">Format JPG, PNG, atau PDF. Maksimal 5 MB per file.</p>
                            <div class="row g-3">
                                @foreach([
                                    'father_id_card' => 'Foto KTP Ayah',
                                    'mother_id_card' => 'Foto KTP Ibu',
                                    'family_card' => 'Kartu Keluarga (KK)',
                                    'birth_certificate' => 'Akta Kelahiran',
                                    'akta' => 'Akta',
                                ] as $field => $label)
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                                        <input type="file" name="{{ $field }}" class="form-control" accept=".jpg,.jpeg,.png,.pdf" @required(!$hasFile($field))>
                                        @if($hasFile($field))
                                            <div class="small text-success mt-1"><span class="fa fa-check-circle me-1"></span>Dokumen sudah diunggah</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <div class="bg-white rounded shadow-sm p-4 mb-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                            <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-3">Cancel</a>
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <button type="submit" name="action" value="draft" formnovalidate class="btn btn-outline-primary rounded-pill px-4 py-3">Save Draft</button>
                                <button type="submit" name="action" value="continue" class="btn btn-primary rounded-pill px-4 py-3">Lanjutkan</button>
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
