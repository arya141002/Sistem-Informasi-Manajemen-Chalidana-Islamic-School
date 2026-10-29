@php
    use Carbon\Carbon;

    // ---------- Logo: coba beberapa lokasi umum, pakai yang pertama ditemukan ----------
    $logoCandidates = [
        public_path('assets/logo-cis.png'),
        public_path('assets/logo.png'),
        public_path('assets/images/logo.png'),
        public_path('icon/logo.png'),
        public_path('uploads/logo.png'),
    ];
    $logoFile = collect($logoCandidates)->first(fn ($p) => is_file($p));
    $logoUri  = $logoFile
        ? 'data:' . mime_content_type($logoFile) . ';base64,' . base64_encode(file_get_contents($logoFile))
        : null;

    // ---------- Helper ----------
    $val = fn ($v) => filled($v) && trim((string) $v) !== '' ? trim((string) $v) : '-';

    // Ya/Tidak + penjelasan. Kosong => "-", kata negatif => "Tidak", selain itu => "Ya" + penjelasan
    $yesNo = function ($v): array {
        $v = trim((string) $v);
        if ($v === '' || $v === '-') {
            return ['-', null];
        }
        if (in_array(mb_strtolower($v), ['tidak', 'tidak ada', 'no', 'none', 'nihil'], true)) {
            return ['Tidak', null];
        }
        return ['Ya', $v];
    };

    $tglIndo = fn ($d) => $d ? Carbon::parse($d)->locale('id')->translatedFormat('d F Y') : '-';

    // ---------- Imunisasi ----------
    $stored = is_array($application->basic_immunizations)
        ? $application->basic_immunizations
        : (json_decode($application->basic_immunizations ?? '[]', true) ?: []);

    $immunList = [
        ['id' => 'BCG',                          'en' => null,      'keys' => ['bcg']],
        ['id' => 'Hepatitis B',                  'en' => null,      'keys' => ['hepatitis b']],
        ['id' => 'Diphtheria/Tetanus/Pertussis', 'en' => null,      'keys' => ['dpt', 'diphtheria', 'difteri', 'tetanus', 'pertussis']],
        ['id' => 'Polio',                        'en' => null,      'keys' => ['polio']],
        ['id' => 'Campak',                       'en' => 'Measles', 'keys' => ['campak', 'measles']],
        ['id' => 'Hepatitis A',                  'en' => null,      'keys' => ['hepatitis a']],
        ['id' => 'MMR',                          'en' => null,      'keys' => ['mmr']],
        ['id' => 'Influenza',                    'en' => null,      'keys' => ['influenza', 'flu']],
    ];

    $has = function (array $keys) use ($stored): bool {
        foreach ($stored as $s) {
            foreach ($keys as $k) {
                if (stripos((string) $s, $k) !== false) {
                    return true;
                }
            }
        }
        return false;
    };

    // nilai yang tidak cocok dengan daftar baku => masuk "Lain-lain"
    $allKeys = collect($immunList)->pluck('keys')->flatten()->all();
    $others = collect($stored)->filter(function ($s) use ($allKeys) {
        foreach ($allKeys as $k) {
            if (stripos((string) $s, $k) !== false) {
                return false;
            }
        }
        return true;
    })->values()->all();

    // ---------- Alergi: Ya/Tidak per baris, isi lengkap di "Penjelasan" ----------
    $allergyState = function ($v) use ($yesNo): array {
        [$s, $d] = $yesNo($v);
        return [$s === '-' ? 'Tidak' : $s, $d];
    };
    [$af, $afD] = $allergyState($application->allergy_food);
    [$am, $amD] = $allergyState($application->allergy_medicine);
    [$ao, $aoD] = $allergyState($application->allergy_other);

    $allergyDesc = collect([
        $afD ? 'Makanan: ' . $afD : null,
        $amD ? 'Obat: ' . $amD : null,
        $aoD ? 'Lain-lain: ' . $aoD : null,
        in_array(trim((string) $application->allergy_description), ['', '-'], true)
            ? null
            : trim((string) $application->allergy_description),
    ])->filter()->implode('; ');

    // ---------- Kehamilan & riwayat penyakit ----------
    [$pm, $pmDesc] = $yesNo($application->pregnancy_medicine_consumption);
    [$pd, $pdDesc] = $yesNo($application->pregnancy_diseases);
    [$rm, $rmDesc] = $yesNo($application->routine_medication);
    [$sg, $sgDesc] = $yesNo($application->surgery_history);
    [$hs, $hsDesc] = $yesNo($application->hospitalization_history);
    [$pv, $pvDesc] = $yesNo($application->previous_diseases);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Kesehatan - {{ $application->full_name }}</title>
    <style>
        @page { margin: 125px 45px 45px 45px; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #111; }

        #header { position: fixed; top: -105px; left: 0; right: 0; height: 80px; border-bottom: 2px solid #111; }
        #header img { height: 55px; }

        .title { text-align: center; margin-top: 30px; }
        .title h2 { margin: 0; font-size: 15pt; }
        .title .en { font-style: italic; font-size: 12pt; margin-top: 8px; }

        .en { font-style: italic; font-size: 9pt; color: #333; }
        .page-break { page-break-before: always; }
        .avoid { page-break-inside: avoid; }

        /* judul seksi: garis biru di kiri + latar abu-abu */
        .section { width: 100%; border-collapse: collapse; margin: 18px 0 12px; background: #f4f5f7; }
        .section td { padding: 9px 14px; border-left: 4px solid #0d6efd; }
        .section .id { font-weight: bold; font-size: 11pt; color: #2d3b55; }
        .section .en { font-weight: bold; font-size: 10.5pt; color: #2d3b55; }

        .box { background: #f8f9fa; padding: 12px 16px; }
        .kv { width: 100%; border-collapse: collapse; }
        .kv td { vertical-align: middle; padding: 4px 0; }
        .kv td.l { width: 36%; }
        .kv td.c { width: 3%; }
        .kv td.v { width: 61%; }

        .hl { background: #fff1cf; padding: 6px 10px; }

        .grid { width: 100%; border-collapse: collapse; }
        .grid th { background: #e9ecef; padding: 9px 6px; text-align: center; }
        .grid td { padding: 9px 6px; text-align: center; }

        .immun { width: 100%; border-collapse: separate; border-spacing: 0 4px; }
        .immun td { padding: 8px 8px; background: #f4f5f7; vertical-align: middle; }
        .immun td.no { width: 5%; background: #fff; text-align: center; }
        .immun td.yes { width: 10%; text-align: center; }

        .materai { width: 85px; height: 85px; border: 1px dashed #aaa; background: #f6f6f6; margin: 6px auto;
                   text-align: center; font-size: 8pt; color: #999; line-height: 85px; }
    </style>
</head>
<body>

<div id="header">
    <div class="kop" style="text-align: center">
        <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/icon/CIS_Branding_Logo.webp'))) }}" alt="Logo" style="height:40px;">
        <br><br>
    </div>
</div>

{{-- ===================== HALAMAN 1 ===================== --}}
<div class="title">
    <h2>DATA KESEHATAN SISWA</h2>
    <div class="en">STUDENT HEALTH DATA</div>
</div>

<table class="section" style="margin-top:35px;"><tr><td>
    <div class="id">Informasi Dasar Siswa</div><div class="en">Basic Student Information</div>
</td></tr></table>

<div class="box">
    <table class="kv">
        <tr><td class="l">Nama Lengkap Siswa<div class="en">Student's Full Name</div></td><td class="c">:</td><td class="v">{{ $val($application->full_name) }}</td></tr>
        <tr><td class="l">Nama Panggilan<div class="en">Nickname</div></td><td class="c">:</td><td class="v">{{ $val($application->nickname) }}</td></tr>
        <tr><td class="l">Jenis Kelamin<div class="en">Gender</div></td><td class="c">:</td><td class="v">{{ ucfirst($val($application->gender)) }}</td></tr>
        <tr><td class="l">Tempat, Tanggal Lahir<div class="en">Place, Date of Birth</div></td><td class="c">:</td>
            <td class="v">{{ $val($application->birth_place) }}, {{ $tglIndo($application->birth_date) }}</td></tr>
        <tr><td class="l">Nama Ayah<div class="en">Father's Name</div></td><td class="c">:</td><td class="v">{{ $val($application->father_name) }}</td></tr>
        <tr><td class="l">Nama Ibu<div class="en">Mother's Name</div></td><td class="c">:</td><td class="v">{{ $val($application->mother_name) }}</td></tr>
        <tr><td class="l">No. Telp/HP Orang Tua/Wali<div class="en">Parent/Guardian Phone Number</div></td><td class="c">:</td><td class="v">{{ $val($application->phone) }}</td></tr>
    </table>
</div>

<table class="section"><tr><td>
    <div class="id">Kontak Darurat</div><div class="en">Emergency Contacts</div>
</td></tr></table>

<div>Keadaan darurat yang harus dihubungi selain orang tua/wali:</div>
<div class="en" style="margin-bottom:10px;">Emergency contacts other than parents/guardians:</div>

<table class="grid">
    <tr>
        <th style="width:17%;">Nama<div class="en">Name</div></th>
        <th style="width:20%;">No. Telepon<div class="en">Phone Number</div></th>
        <th>Dokter<div class="en">Doctor</div></th>
        <th style="width:22%;">Rumah Sakit<div class="en">Hospital</div></th>
    </tr>
    <tr>
        <td>{{ $val($application->emergency_contact_1_name) }}</td>
        <td>{{ $val($application->emergency_contact_1_phone) }}</td>
        <td>{{ $val($application->emergency_contact_1_doctor) }}</td>
        <td>{{ $val($application->emergency_contact_1_hospital) }}</td>
    </tr>
    <tr>
        <td>{{ $val($application->emergency_contact_2_name) }}</td>
        <td>{{ $val($application->emergency_contact_2_phone) }}</td>
        <td>{{ $val($application->emergency_contact_2_doctor) }}</td>
        <td>{{ $val($application->emergency_contact_2_hospital) }}</td>
    </tr>
</table>

{{-- ===================== HALAMAN 2 ===================== --}}
<div class="page-break"></div>

<table class="section" style="margin-top:0;"><tr><td>
    <div class="id">Imunisasi Dasar</div><div class="en">Basic Immunization</div>
</td></tr></table>

<table style="width:100%; border-collapse:collapse;">
<tr>
    {{-- kolom kiri: 1-5 --}}
    <td style="width:50%; vertical-align:top; padding-right:4px;">
        <table class="immun">
            @foreach(array_slice($immunList, 0, 5) as $i => $row)
                <tr>
                    <td class="no">{{ $i + 1 }}.</td>
                    <td>{{ $row['id'] }}@if($row['en'])<div class="en">{{ $row['en'] }}</div>@endif</td>
                    <td class="yes">{{ $has($row['keys']) ? 'YA' : '' }}</td>
                </tr>
            @endforeach
        </table>
    </td>
    {{-- kolom kanan: 6-9 --}}
    <td style="width:50%; vertical-align:top; padding-left:4px;">
        <table class="immun">
            @foreach(array_slice($immunList, 5) as $i => $row)
                <tr>
                    <td class="no">{{ $i + 6 }}.</td>
                    <td>{{ $row['id'] }}@if($row['en'])<div class="en">{{ $row['en'] }}</div>@endif</td>
                    <td class="yes">{{ $has($row['keys']) ? 'YA' : '' }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="no">9.</td>
                <td>Lain-lain<div class="en">Others</div></td>
                <td class="yes" style="font-size:10pt;">{{ count($others) ? implode(', ', $others) : '' }}</td>
            </tr>
        </table>
    </td>
</tr>
</table>

<table class="section"><tr><td>
    <div class="id">Catatan Kesehatan</div><div class="en">Health Notes</div>
</td></tr></table>

<div class="box avoid">
    <div style="font-weight:bold; font-size:9pt;">Alergi</div>
    <div class="en" style="font-size:11pt; font-weight:bold; margin-bottom:6px;">Allergies</div>
    <table class="kv">
        <tr><td class="l">Makanan<div class="en">Food</div></td><td class="c">:</td><td class="v">{{ $af }}</td></tr>
        <tr><td class="l">Obat<div class="en">Medicine</div></td><td class="c">:</td><td class="v">{{ $am }}</td></tr>
        <tr><td class="l">Lain-lain<div class="en">Others</div></td><td class="c">:</td><td class="v">{{ $ao }}</td></tr>
        <tr><td class="l">Penjelasan<div class="en">Description</div></td><td class="c">:</td>
            <td class="v">@if($allergyDesc)<div class="hl">{{ $allergyDesc }}</div>@else - @endif</td></tr>
    </table>
</div>

<table class="section"><tr><td>
    <div class="id">Riwayat Kehamilan</div><div class="en">Pregnancy History</div>
</td></tr></table>

<div class="box avoid">
    <table class="kv">
        <tr><td class="l">Konsumsi Obat-obatan Pada Saat Kehamilan<div class="en">Medicine Consumption During Pregnancy</div></td><td class="c">:</td><td class="v">{{ $pm }}</td></tr>
        @if($pmDesc)
        <tr><td class="l">Penjelasan<div class="en">Description</div></td><td class="c">:</td><td class="v"><div class="hl">{{ $pmDesc }}</div></td></tr>
        @endif
        <tr><td class="l">Penyakit Selama Kehamilan<div class="en">Diseases During Pregnancy</div></td><td class="c">:</td><td class="v">{{ $pd }}</td></tr>
        @if($pdDesc)
        <tr><td class="l">Penjelasan<div class="en">Description</div></td><td class="c">:</td><td class="v"><div class="hl">{{ $pdDesc }}</div></td></tr>
        @endif
    </table>
</div>

{{-- ===================== HALAMAN 3 ===================== --}}
<div class="page-break"></div>

<table class="section" style="margin-top:0;"><tr><td>
    <div class="id">Riwayat Kelahiran</div><div class="en">Birth History</div>
</td></tr></table>

<div class="box avoid">
    <table class="kv">
        <tr><td class="l">Usia Kehamilan pada saat persalinan<div class="en">Gestational age at delivery</div></td><td class="c">:</td><td class="v">{{ $val($application->gestational_age) }}</td></tr>
        <tr><td class="l">Proses Persalinan<div class="en">Delivery Process</div></td><td class="c">:</td><td class="v">{{ $val($application->delivery_process) }}</td></tr>
        <tr><td class="l">Kondisi Bayi Setelah Lahir<div class="en">Baby Condition After Birth</div></td><td class="c">:</td>
            <td class="v">
                Berat: {{ $val($application->baby_birth_weight) }}<div class="en">Weight</div>
                Panjang: {{ $val($application->baby_birth_length) }}<div class="en">Length</div>
            </td></tr>
        <tr><td class="l">Apakah ada obat-obatan yang secara rutin dikonsumsi oleh ananda?<div class="en">Are there any medications that your child takes regularly?</div></td><td class="c">:</td>
            <td class="v">{{ $rm }}@if($rmDesc)<div class="hl" style="margin-top:4px;">{{ $rmDesc }}</div>@endif</td></tr>
    </table>
</div>

<table class="section"><tr><td>
    <div class="id">Riwayat Penyakit Dahulu</div><div class="en">Previous Medical History</div>
</td></tr></table>

<div class="box avoid">
    <table class="kv">
        <tr><td class="l">Pernahkah Ananda Di Operasi<div class="en">Has your child ever had surgery</div></td><td class="c">:</td>
            <td class="v">{{ $sg }}@if($sgDesc)<div class="hl" style="margin-top:4px;">{{ $sgDesc }}</div>@endif</td></tr>
        <tr><td class="l">Pernahkah Ananda Dirawat di Rumah Sakit<div class="en">Has your child ever been hospitalized</div></td><td class="c">:</td><td class="v">{{ $hs }}</td></tr>
        @if($hsDesc)
        <tr><td class="l">Penjelasan<div class="en">Description</div></td><td class="c">:</td><td class="v"><div class="hl">{{ $hsDesc }}</div></td></tr>
        @endif
        <tr><td class="l">Penyakit yang pernah diderita oleh Ananda sebelumnya<div class="en">Previous diseases suffered by your child</div></td><td class="c">:</td>
            <td class="v">{{ $pv }}@if($pvDesc)<div class="hl" style="margin-top:4px;">{{ $pvDesc }}</div>@endif</td></tr>
        <tr><td class="l">Riwayat Penyakit Keluarga<div class="en">Family Medical History</div></td><td class="c">:</td><td class="v">{{ $val($application->family_medical_history) }}</td></tr>
    </table>
</div>

{{-- ===================== HALAMAN 4 ===================== --}}
<div class="page-break"></div>

<table class="section" style="margin-top:0;"><tr><td>
    <div class="id">PERNYATAAN</div><div class="en">STATEMENT</div>
</td></tr></table>

<p style="text-align:justify; line-height:1.9; margin-top:18px;">
    Kami sebagai orang tua/wali murid <strong>{{ $application->full_name }}</strong> memberi kuasa kepada sekolah untuk
    memberikan pertolongan pertama apabila terjadi keadaan gawat darurat atau kecelakaan pada anak kami di sekolah,
    yang memerlukan penanganan (perawatan) secepatnya. Demikian data kesehatan ini kami isi dengan benar agar dapat
    dipergunakan semestinya untuk kepentingan anak kami selama dalam proses pendidikan di Chalidana Islamic School.
</p>

<table style="width:100%; margin-top:40px; border-collapse:collapse;">
    <tr>
        <td style="width:50%;"></td>
        <td style="width:50%; text-align:center;">
            <div>{{ $val($application->health_approved_city) }}, {{ $tglIndo($application->health_approved_date) }}</div>
            <div style="margin-top:14px;">Yang Menyetujui,</div>
            <div class="en">Approved by,</div>
            <div class="materai">MATERAI</div>
            <div style="margin-top:22px; font-weight:bold; border-bottom:1px solid #111; display:inline-block; padding:0 14px 2px;">
                {{ $val($application->health_approved_by) }}
            </div>
            <div class="en" style="margin-top:10px;">Parent/Guardian Signature</div>
        </td>
    </tr>
</table>

</body>
</html>
