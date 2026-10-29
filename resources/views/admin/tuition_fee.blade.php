@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> Tuition Fee
        </h4>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tuition Fee Management</h5>
            </div>
            <div class="card-body">
                <p class="mb-4">Halaman ini untuk mengelola informasi biaya sekolah, jadwal pembayaran, dan ketentuan administrasi biaya.</p>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Informasi Biaya</h6>
                                <p class="card-text">Gunakan halaman ini untuk menampilkan rincian biaya uang pangkal, SPP, biaya program, dan termin pembayaran.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">URL Menu</h6>
                                <p class="card-text"><code>{{ url('/tuition-fee') }}</code></p>
                                <small class="text-muted">Menu ini sudah terdaftar di database dan kini memiliki halaman admin.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6>Konten yang bisa ditambahkan</h6>
                    <ul>
                        <li>Rincian biaya per kelas/jenjang.</li>
                        <li>Instruksi pembayaran dan rekening bank.</li>
                        <li>Jadwal termin pembayaran.</li>
                        <li>Diskon atau skema beasiswa jika ada.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
