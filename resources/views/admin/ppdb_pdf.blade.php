<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Formulir Pendaftaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    @media print {
      @page {
        size: 210mm 330mm; /* F4 paper size */
        margin: 20mm;
      }
    }
    
    body {
      font-size: 12pt;
      margin: 20px;
    }
    .kop {
      text-align: center;
      border-bottom: 2px solid #000;
      margin-bottom: 10px;
      padding-bottom: 10px;
    }
    .kop img {
      height: 80px;
      float: left;
      margin-right: 15px;
    }
    .kop h1, .kop h4, .kop p {
      margin: 0;
    }
    .form-label {
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="container">

    <!-- KOP -->
   <div class="kop" style="text-align: center">
        <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/icon/CIS_Branding_Logo.webp'))) }}" alt="Logo" style="height:40px;">
        <br><br>
    </div>


    <!-- SURAT PENGANTAR -->
    <h3 style="text-align: center; margin-bottom: 30px;">SURAT PENGANTAR</h3>
    <table class="table table-borderless">
        <tr>
        <td class="text-center"> Perihal : Surat Pengantar</td>
        </tr>
        <br>
        <tr>
        <td class="text-center"> Kepada Yth. </td>
        </tr>
        <tr>
        <td class="text-center">Pimpinan Chalidana Islamic School</td>
        </tr>
        <tr>
        <td class="text-center"> di Tempat</td>
        </tr>
        <br>
        <br>
        <br>
        <tr>
        <td class="text-center" style="font-style: italic">Assalamu'alaikum Warahmatullahi Wabarakatuh</td>
        </tr>
        <br>
        <tr>
        <td class="text-center">Bersama ini kami mengajukan pendaftaran peserta didik baru ke Chalidana Islamic School:</td>
        </tr>
    </table>
  <br>
    <table class="table table-bordered">
        <tr>
            <td>Nama Lengkap</td>
            <td>:</td>
            <td>{{ $application->full_name }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $application->birth_place }}, {{ optional($application->birth_date)->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Jenjang Pendidikan</td>
            <td>:</td>
            <td>{{ $application->education_level }}</td>
        </tr>
        <tr>
            <td>Sekolah Tujuan</td>
            <td>:</td>
            <td>{{ $application->campus?->title ?? $application->school_destination }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $application->academic_year }}</td>
        </tr>
        <br>
    </table>
    <br>
    <table class="table table-borderless">
        <tr>
            <td class="text-center">Seluruh persyaratan pendaftaran peserta didik baru sebagaimana
            tercantum dalam dokumen terlampir akan kami penuhi semestinya.</td>
        </tr>
        <br>
        <tr>
            <td style="font-style: italic">Wassalamu'alaikum Warahmatullahi Wabarakatuh</td>
        </tr>
        <br>
        <br>
    </table>
    <div class="d-flex justify-content-end">
        <table class="table table-borderless" style="width: 350px;">
            <tr>
                <td class="text-center">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="text-center">Orang Tua</td>
            </tr>
            <tr>
                <td style="height: 60px;"></td>
            </tr>
            <tr>
                <td class="text-center">{{ $application->father_name }}</td>
            </tr>
        </table>
    </div>
    
    <div style="page-break-before: always;"></div>
    <div class="kop" style="text-align: center">
        <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/icon/CIS_Branding_Logo.webp'))) }}" alt="Logo" style="height:40px;">
        <br><br>
    </div>
  <!-- Registration Info -->
    <table class="table table-borderless" style="width: 100%; font-size: 12pt; margin-bottom: 20px;">
        <tr>
        <td style="width: 23%; text-align: left;">
            <strong>Kode Registrasi</strong><br>
            <em style="font-size: 10pt;">Registration Code</em>
        </td>
        <td style="width: 2%;">
            :
        </td>
        <td style="width: 23%;">
            {{ $application->registration_code ?? '-' }}
        </td>
        <td style="width: 23%; text-align: left;">
            <strong>Tanggal Pendaftaran</strong><br>
            <em style="font-size: 10pt;">Registration Date</em>
        </td>
        <td style="width: 2%;">
            :
        </td>
        <td style="width: 27%;">
            {{ $application->registration_date ? $application->registration_date->locale('id')->translatedFormat('d F Y') : '-' }}
        </td>
        </tr>
    </table>

  <!-- FORMULIR -->
    <h4>A. Keterangan Calon Peserta Didik</h4>
    <p style="margin-top: -10px; margin-bottom: 15px;"><em style="font-size: 10pt;">Student Candidate Information</em></p>
    <table class="table table-borderless" style="width: 100%; font-size: 12pt;">
        
        <tr style="border-bottom: 1px solid #000;">
            <td style="width: 30%;">Nama Lengkap<br><em style="font-size: 10pt;">Full Name</em></td>
            <td style="width: 3%;">:</td>
            <td style="width: 62%;">{{ $application->full_name }}</td>
        </tr>
        <tr>
            <td>Nama Panggilan<br><em style="font-size: 10pt;">Nickname</em></td>
            <td>:</td>
            <td>{{ $application->nickname ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin<br><em style="font-size: 10pt;">Gender</em></td>
            <td>:</td>
            <td>{{ $application->gender }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir<br><em style="font-size: 10pt;">Place, Date of Birth</em></td>
            <td>:</td>
            <td>{{ $application->birth_place }}, {{ optional($application->birth_date)->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Berat Badan / Tinggi Badan<br><em style="font-size: 10pt;">Weight / Height</em></td>
            <td>:</td>
            <td>{{ $application->weight ?? '-' }} kg / {{ $application->height ?? '-' }} cm</td>
        </tr>
        <tr>
            <td>Lingkar Kepala<br><em style="font-size: 10pt;">Head Circumference</em></td>
            <td>:</td>
            <td>{{ $application->head_circumference ?? '-' }} cm</td>
        </tr>
        <tr>
            <td>Agama<br><em style="font-size: 10pt;">Religion</em></td>
            <td>:</td>
            <td>{{ $application->religion ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat<br><em style="font-size: 10pt;">Address</em></td>
            <td>:</td>
            <td>{{ $application->address ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Telepon / HP<br><em style="font-size: 10pt;">Phone Number</em></td>
            <td>:</td>
            <td>{{ $application->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td>Asal Sekolah<br><em style="font-size: 10pt;">Previous School</em></td>
            <td>:</td>
            <td>{{ $application->previous_school ?? '-' }}</td>
        </tr>
        <tr>
            <td>Anak Ke<br><em style="font-size: 10pt;">Child Number</em></td>
            <td>:</td>
            <td>{{ $application->child_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>Bahasa Pengantar di Rumah<br><em style="font-size: 10pt;">Language at Home</em></td>
            <td>:</td>
            <td>{{ $application->language_at_home ?? '-' }}</td>
        </tr>
        <tr>
            <td>Bahasa Lainnya<br><em style="font-size: 10pt;">Other Languages</em></td>
            <td>:</td>
            <td>{{ $application->other_languages ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tinggal Bersama<br><em style="font-size: 10pt;">Living With</em></td>
            <td>:</td>
            <td>{{ $application->living_with ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jarak Tempat Tinggal ke Sekolah<br><em style="font-size: 10pt;">Distance to School</em></td>
            <td>:</td>
            <td>{{ $application->distance_to_school ?? '-' }} km</td>
        </tr>
        <tr>
            <td>Alamat Email<br><em style="font-size: 10pt;">Email Address</em></td>
            <td>:</td>
            <td>{{ $application->email ?? '-' }}</td>
        </tr>
        {{-- <tr>
            <td>Status Dalam Keluarga<br><em style="font-size: 10pt;">Family Status</em></td>
            <td>:</td>
            <td>{{ $application->status }}</td>
        </tr> --}}
    </table>
    <div style="page-break-before: always;"></div>
    
    <!-- KOP Halaman 2 -->
    <div class="kop" style="text-align: center; margin-bottom: 20px;">
        <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/icon/CIS_Branding_Logo.webp'))) }}" alt="Logo" style="height:40px;">
        <br><br>
    </div>
    <h5 style="margin-top: 20px; margin-bottom: 15px;">Informasi Saudara Kandung<br><em style="font-size: 10pt;">Sibling Information</em></h5>
    <table class="table table-borderless" style="width: 100%; font-size: 12pt; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%;">Jumlah Saudara Kandung<br><em style="font-size: 10pt;">Total Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->total_siblings ?? '-' }}</td>
            <td style="width: 25%;">Kakak Kandung<br><em style="font-size: 10pt;">Older Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->older_siblings ?? '-' }}</td>
            <td style="width: 25%;">Adik Kandung<br><em style="font-size: 10pt;">Younger Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->younger_siblings ?? '-' }}</td>
        </tr>
    </table>
    
    <h5 style="margin-bottom: 15px;">Informasi Saudara Tiri<br><em style="font-size: 10pt;">Step-Sibling Information</em></h5>
    <table class="table table-borderless" style="width: 100%; font-size: 12pt; margin-bottom: 15px;">
        <tr>
            <td style="width: 25%;">Jumlah Saudara Tiri<br><em style="font-size: 10pt;">Total Step-Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->total_step_siblings ?? '-' }}</td>
            <td style="width: 25%;">Kakak Tiri<br><em style="font-size: 10pt;">Older Step-Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->older_step_siblings ?? '-' }}</td>
            <td style="width: 25%;">Adik Tiri<br><em style="font-size: 10pt;">Younger Step-Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->younger_step_siblings ?? '-' }}</td>
        </tr>
    </table>
    
    <h5 style="margin-bottom: 15px;">Informasi Saudara Angkat<br><em style="font-size: 10pt;">Adopted Sibling Information</em></h5>
    <table class="table table-borderless" style="width: 100%; font-size: 12pt; margin-bottom: 20px;">
        <tr>
            <td style="width: 25%;">Jumlah Saudara Angkat<br><em style="font-size: 10pt;">Total Adopted Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->total_adopted_siblings ?? '-' }}</td>
            <td style="width: 25%;">Kakak Angkat<br><em style="font-size: 10pt;">Older Adopted Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->older_adopted_siblings ?? '-' }}</td>
            <td style="width: 25%;">Adik Angkat<br><em style="font-size: 10pt;">Younger Adopted Siblings</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $application->younger_adopted_siblings ?? '-' }}</td>
        </tr>
    </table>
    <br>
    <table class="table table-borderless" style="width: 100%; font-size: 12pt;">
    <h5 style="margin-top: 20px; margin-bottom: 15px;">Riwayat Pendidikan dan Terapi<br><em style="font-size: 10pt;">Educational and Therapy History</em></h5>
    
    <table class="table table-borderless" style="width: 100%; font-size: 12pt; margin-bottom: 15px;">
        <tr>
            <td style="width: 50%;">Apakah Siswa Pernah Tidak Sekolah Sebelumnya?<br><em style="font-size: 10pt;">Has the student ever been out of school before?</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;">
                <span style="margin-right: 20px;">
                    <input type="checkbox" {{ $application->ever_out_of_school ? 'checked' : '' }} disabled style="margin-right: 5px;">
                    Ya <em style="font-size: 9pt;">(Yes)</em>
                </span>
                <span>
                    <input type="checkbox" {{ !$application->ever_out_of_school ? 'checked' : '' }} disabled style="margin-right: 5px;">
                    Tidak <em style="font-size: 9pt;">(No)</em>
                </span>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">Jika Ya, Berapa Lama Tidak Sekolah?<br><em style="font-size: 10pt;">If yes, how long was out of school?</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%; padding: 8px; background-color: #f8f9fa; border-radius: 4px;">
                {{ $application->out_of_school_duration ?? '-' }}
            </td>
        </tr>
    </table>
    
    <table class="table table-borderless" style="width: 100%; font-size: 12pt; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%;">Apakah siswa pernah menjalani terapi khusus sehubungan dengan hambatan fisik / tingkah laku?<br><em style="font-size: 10pt;">Has the student ever undergone special therapy related to physical / behavioral barriers?</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;">
                <span style="margin-right: 20px;">
                    <input type="checkbox" {{ $application->had_special_therapy ? 'checked' : '' }} disabled style="margin-right: 5px;">
                    Ya <em style="font-size: 9pt;">(Yes)</em>
                </span>
                <span>
                    <input type="checkbox" {{ !$application->had_special_therapy ? 'checked' : '' }} disabled style="margin-right: 5px;">
                    Tidak <em style="font-size: 9pt;">(No)</em>
                </span>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">Jika Ya, apakah jenis terapinya?<br><em style="font-size: 10pt;">If yes, what type of therapy?</em></td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%; padding: 8px; background-color: #f8f9fa; border-radius: 4px;">
                {{ $application->therapy_type ?? '-' }}
            </td>
        </tr>
    </table>

    <div style="page-break-before: always;"></div>
    <div class="kop" style="text-align: center">
        <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/icon/CIS_Branding_Logo.webp'))) }}" alt="Logo" style="height:40px;">
        <br><br>
    </div>
    <h4>B. Keterangan Orang Tua</h4>
    <p style="margin-top: -10px; margin-bottom: 15px;"><em style="font-size: 10pt;">Parent Information</em></p>
    
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <!-- Informasi Ayah -->
        <div style="flex: 1;">
            <h5 style="margin-bottom: 15px; color: #2c3e50;">Informasi Ayah<br><em style="font-size: 10pt;">Father Information</em></h5>
            <table class="table table-borderless" style="width: 100%; font-size: 12pt;">
                <tr>
                    <td style="width: 40%;">Nama Ayah<br><em style="font-size: 10pt;">Father's Name</em></td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 55%;">{{ $application->father_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir<br><em style="font-size: 10pt;">Place, Date of Birth</em></td>
                    <td>:</td>
                    <td>{{ $application->father_birth_place ?? '-' }}, {{ optional($application->father_birth_date)->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan<br><em style="font-size: 10pt;">Occupation</em></td>
                    <td>:</td>
                    <td>{{ $application->father_occupation ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Penghasilan<br><em style="font-size: 10pt;">Income</em></td>
                    <td>:</td>
                    <td>{{ $application->father_income ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Keterangan<br><em style="font-size: 10pt;">Notes</em></td>
                    <td>:</td>
                    <td>{{ $application->father_notes ?? '-' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Informasi Ibu -->
        <div style="flex: 1;">
            <h5 style="margin-bottom: 15px; color: #2c3e50;">Informasi Ibu<br><em style="font-size: 10pt;">Mother Information</em></h5>
            <table class="table table-borderless" style="width: 100%; font-size: 12pt;">
                <tr>
                    <td style="width: 40%;">Nama Ibu<br><em style="font-size: 10pt;">Mother's Name</em></td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 55%;">{{ $application->mother_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir<br><em style="font-size: 10pt;">Place, Date of Birth</em></td>
                    <td>:</td>
                    <td>{{ $application->mother_birth_place ?? '-' }}, {{ optional($application->mother_birth_date)->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan<br><em style="font-size: 10pt;">Occupation</em></td>
                    <td>:</td>
                    <td>{{ $application->mother_occupation ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Penghasilan<br><em style="font-size: 10pt;">Income</em></td>
                    <td>:</td>
                    <td>{{ $application->mother_income ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Keterangan<br><em style="font-size: 10pt;">Notes</em></td>
                    <td>:</td>
                    <td>{{ $application->mother_notes ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px;">
        <h6 style="margin-bottom: 10px; color: #2c3e50;">Alamat Orang Tua<br><em style="font-size: 10pt;">Parent Address</em></h6>
        <p style="margin: 0; font-size: 12pt; line-height: 1.5;">
            {{ $application->parent_address ?? '-' }}
        </p>
    </div>
    <br>
    <div style="page-break-before: always;"></div>
    <div class="kop" style="text-align: center">
        <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/icon/CIS_Branding_Logo.webp'))) }}" alt="Logo" style="height:40px;">
        <br><br>
    </div>
    <h4>C. Keterangan Wali</h4>
    <p style="margin-top: -10px; margin-bottom: 15px;"><em style="font-size: 10pt;">Guardian Information</em></p>
    
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <table class="table table-borderless" style="width: 100%; font-size: 12pt;">
            <tr>
                <td style="width: 30%;">Nama Wali<br><em style="font-size: 10pt;">Guardian's Name</em></td>
                <td style="width: 3%;">:</td>
                <td style="width: 67%;">{{ $application->guardian_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin Wali<br><em style="font-size: 10pt;">Guardian's Gender</em></td>
                <td>:</td>
                <td>{{ $application->guardian_gender ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir Wali<br><em style="font-size: 10pt;">Guardian's Place, Date of Birth</em></td>
                <td>:</td>
                <td>
                    @if($application->guardian_birth_place || $application->guardian_birth_date)
                        {{ $application->guardian_birth_place ?? '-' }}, {{ optional($application->guardian_birth_date)->locale('id')->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Agama Wali<br><em style="font-size: 10pt;">Guardian's Religion</em></td>
                <td>:</td>
                <td>{{ $application->guardian_religion ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pekerjaan Wali<br><em style="font-size: 10pt;">Guardian's Occupation</em></td>
                <td>:</td>
                <td>{{ $application->guardian_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Wali<br><em style="font-size: 10pt;">Guardian's Address</em></td>
                <td>:</td>
                <td>{{ $application->guardian_address ?? '-' }}</td>
            </tr>
            <tr>
                <td>Penghasilan Wali<br><em style="font-size: 10pt;">Guardian's Income</em></td>
                <td>:</td>
                <td>
                    @if($application->guardian_income)
                        {{ $application->guardian_income }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <br>
    <table class="table table-borderless" style="width: 100%; font-size: 12pt;">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 30%; text-align: center;">
                <p>............ , {{ date('d F Y') }}</p>
                <p style="margin-bottom: 15px;">Orang Tua / Wali<br><em style="font-size: 10pt;">Parent / Guardian</em></p>
                <br>
                <div style="display: inline-block; border: 2px dashed #999; width: 80px; height: 80px; margin-bottom: 15px; position: relative; background-color: #fafafa;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 8pt; color: #999; text-align: center; line-height: 1.2;">
                        MATERAI<br>
                        <em style="font-size: 7pt;">STAMP</em>
                    </div>
                </div>
                <br>
                <p style="margin-bottom: 15px;">{{ $application->father_name ?? $application->guardian_name ?? '-' }}</p>
            </td>
        </tr>
    </table>
    <div style="page-break-before: always;"></div>
    <!-- =============================== -->
    <!-- BAGIAN LAMPIRAN -->
    <!-- =============================== -->

    <h4 style="margin-top: 30px;">D. Lampiran Dokumen</h4>
    <p style="margin-top: -10px; margin-bottom: 15px;"><em style="font-size: 10pt;">Document Attachments</em></p>

    @php
        $attachmentImages = function (string $field) use ($application): array {
            $path = $application->{$field . '_path'};
            if (!$path) {
                return [];
            }
    
            $absolutePath = storage_path('app/private/' . $path);
            if (!is_file($absolutePath)) {
                return [];
            }
    
            $maxDimension = 1600;
            $maxPdfPages  = 3;
            $cacheDir     = storage_path('framework/cache/pdf');
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
    
            $key  = sha1($absolutePath . filemtime($absolutePath));
            $mime = mime_content_type($absolutePath);
            $toUri = fn (string $file) => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($file));
            $images = [];
    
            try {
                // ---------- PDF -> JPEG per halaman ----------
                if ($mime === 'application/pdf') {
                    if (!class_exists(\Imagick::class)) {
                        return [];
                    }
    
                    $probe = new \Imagick();
                    $probe->pingImage($absolutePath);
                    $pages = min($probe->getNumberImages(), $maxPdfPages);
                    $probe->clear();
    
                    for ($i = 0; $i < $pages; $i++) {
                        $cached = "{$cacheDir}/{$key}-p{$i}.jpg";
    
                        if (!is_file($cached)) {
                            $im = new \Imagick();
                            $im->setResolution(130, 130);
                            $im->readImage($absolutePath . '[' . $i . ']');
                            $im->setImageBackgroundColor('white');
                            $im->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                            $im->setImageFormat('jpeg');
                            $im->setImageCompressionQuality(82);
                            $im->thumbnailImage($maxDimension, $maxDimension, true);
                            $im->writeImage($cached);
                            $im->clear();
                        }
    
                        $images[] = $toUri($cached);
                    }
    
                    return $images;
                }
    
                // ---------- Gambar (jpg/png) ----------
                $info = @getimagesize($absolutePath);
                if (!$info) {
                    return [];
                }
                [$width, $height] = $info;
    
                $cached = "{$cacheDir}/{$key}.jpg";
                if (($width > $maxDimension || $height > $maxDimension) && !is_file($cached)) {
                    $source = imagecreatefromstring(file_get_contents($absolutePath));
                    $scale = min($maxDimension / $width, $maxDimension / $height);
                    $newW = (int) ($width * $scale);
                    $newH = (int) ($height * $scale);
    
                    $resized = imagecreatetruecolor($newW, $newH);
                    imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255)); // latar putih untuk PNG transparan
                    imagecopyresampled($resized, $source, 0, 0, 0, 0, $newW, $newH, $width, $height);
                    imagejpeg($resized, $cached, 82);
                    imagedestroy($source);
                    imagedestroy($resized);
                }
    
                $file = is_file($cached) ? $cached : $absolutePath;
                $outMime = mime_content_type($file) ?: 'image/jpeg';
                $images[] = 'data:' . $outMime . ';base64,' . base64_encode(file_get_contents($file));
            } catch (\Throwable $e) {
                report($e);
            }
    
            return $images;
        };
    
        $fatherIdCard = $attachmentImages('father_id_card');
        $motherIdCard = $attachmentImages('mother_id_card');
        $familyCard   = $attachmentImages('family_card');
        $akta         = $attachmentImages('akta');
    @endphp
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <strong>Foto KTP Ayah</strong><br>
                <em style="font-size: 10pt;">Father's ID Card Photo</em><br>
                @if(!empty($fatherIdCard))
                    <img src="{{ $fatherIdCard[0] }}" alt="KTP Ayah"
                         style="width: 90%; max-width: 250px; border: 1px solid #000; margin-top: 5px; border-radius: 5px;">
                @else
                    <em style="color: #666;">Tidak ada lampiran KTP Ayah<br><span style="font-size: 9pt;">No Father's ID Card attachment</span></em>
                @endif
            </td>
            <td style="width: 50%; text-align: center; vertical-align: top;">
                <strong>Foto KTP Ibu</strong><br>
                <em style="font-size: 10pt;">Mother's ID Card Photo</em><br>
                @if(!empty($motherIdCard))
                    <img src="{{ $motherIdCard[0] }}" alt="KTP Ibu"
                         style="width: 90%; max-width: 250px; border: 1px solid #000; margin-top: 5px; border-radius: 5px;">
                @else
                    <em style="color: #666;">Tidak ada lampiran KTP Ibu<br><span style="font-size: 9pt;">No Mother's ID Card attachment</span></em>
                @endif
            </td>
        </tr>
    </table>
    
    <div style="page-break-before: always; text-align: center; margin-top: 20px;">
        <strong>Kartu Keluarga (KK)</strong><br>
        @forelse($familyCard as $img)
            <img src="{{ $img }}" alt="Kartu Keluarga"
                 style="max-width: 100%; height: auto; max-height: 240mm; margin-top: 10px; border: 1px solid #000;">
        @empty
            <em>Tidak ada lampiran KK</em>
        @endforelse
    </div>
    
    <div style="page-break-before: always; text-align: center; margin-top: 20px;">
        <strong>Akta</strong><br>
        @forelse($akta as $img)
            <img src="{{ $img }}" alt="Akta"
                 style="max-width: 100%; height: auto; max-height: 240mm; margin-top: 10px; border: 1px solid #000;">
        @empty
            <em>Tidak ada lampiran Akta</em>
        @endforelse
    </div>

</div>

</body>
</html>
