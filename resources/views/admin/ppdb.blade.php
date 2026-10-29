@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-0"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> PPDB</h4>
                <small class="text-muted">Kelola aplikasi PPDB, lihat detail, dan download PDF untuk setiap aplikasi.</small>
            </div>
            <div>
                <span class="badge bg-label-primary">{{ $applications->total() }} Data</span>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ url()->current() }}" class="row g-2 mb-4">
                    <div class="col-md-5">
                        <input type="text" name="search" value="{{ $search }}" class="form-control"
                               placeholder="Cari kode, nama, atau sekolah tujuan...">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach(['draft', 'health_draft', 'biodata_completed', 'submitted'] as $s)
                                <option value="{{ $s }}" @selected($status === $s)>
                                    {{ ucwords(str_replace('_', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bx bx-search"></i> Cari</button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
                @if($applications->isEmpty())
                    <div class="alert alert-warning mb-0">Belum ada data PPDB yang tersedia.</div>
                @else
                    <div class="text-nowrap">
                        <table class="table table-hover align-middle table-fit">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-no">#</th>
                                    <th class="col-code">Registration Code</th>
                                    <th class="col-name">Nama</th>
                                    <th class="col-status">Status</th>
                                    <th class="col-school">School Destination</th>
                                    <th class="col-date">Registered At</th>
                                    <th class="col-action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>{{ $applications->firstItem() + $loop->index }}</td>
                                        <td>{{ $application->registration_code }}</td>
                                        <td>{{ $application->full_name ?: '-' }}</td>
                                        <td>
                                            <span class="badge bg-label-{{ $application->status === 'submitted' ? 'success' : ($application->status === 'biodata_completed' ? 'info' : ($application->status === 'health_draft' ? 'warning' : 'secondary')) }}">
                                                {{ ucwords(str_replace('_', ' ', $application->status ?? 'draft')) }}
                                            </span>
                                        </td>
                                        <td>{{ $application->campus?->title ?? $application->school_destination ?? '-' }}</td>
                                        <td>{{ optional($application->registration_date)->format('d M Y') ?? optional($application->created_at)->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <a href="{{ route('ppdb.pdf', $application->id) }}"
                                                   class="btn btn-sm btn-outline-primary">PDF</a>
                                                <a href="{{ route('ppdb.health.pdf', $application->id) }}"
                                                   class="btn btn-sm btn-outline-danger"
                                                   title="Unduh Data Kesehatan">Kesehatan (PDF)</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-2">
                            <small class="text-muted">
                                Menampilkan {{ $applications->firstItem() }}-{{ $applications->lastItem() }}
                                dari {{ $applications->total() }} data
                            </small>
                            {{ $applications->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="ppdbDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Aplikasi PPDB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="ppdb-detail-content">
                        <div class="text-center py-5 text-muted">Load data...</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .table-fit {
            width: 100%;
            table-layout: fixed;
            font-size: 0.85rem;
        }
        .table-fit th,
        .table-fit td {
            white-space: normal;      /* teks boleh turun baris */
            word-break: break-word;
            vertical-align: middle;
            padding: .5rem .4rem;
        }
        .table-fit .col-no     { width: 4%; }
        .table-fit .col-code   { width: 14%; }
        .table-fit .col-name   { width: 20%; }
        .table-fit .col-status { width: 14%; }
        .table-fit .col-school { width: 20%; }
        .table-fit .col-date   { width: 12%; }
        .table-fit .col-action { width: 16%; }
    </style>
    <script>
        function formatRow(label, value) {
            return `<div class="row mb-2"><div class="col-sm-4 text-secondary">${label}</div><div class="col-sm-8">${value ?? '-'}</div></div>`;
        }

        async function showPPDBDetail(id) {
            const modal = new bootstrap.Modal(document.getElementById('ppdbDetailModal'));
            const content = document.getElementById('ppdb-detail-content');
            content.innerHTML = '<div class="text-center py-5 text-muted">Loading application data...</div>';
            modal.show();

            try {
                const response = await fetch(`/ppdb/${id}`);
                if (!response.ok) throw new Error('Gagal memuat data');
                const data = await response.json();

                const html = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Peserta</h6>
                            ${formatRow('Registration Code', data.registration_code)}
                            ${formatRow('Full Name', data.full_name)}
                            ${formatRow('Nickname', data.nickname)}
                            ${formatRow('Gender', data.gender)}
                            ${formatRow('Birth Date', data.birth_date)}
                            ${formatRow('Education Level', data.education_level)}
                            ${formatRow('School Destination', data.campus?.title ?? data.school_destination)}
                            ${formatRow('Academic Year', data.academic_year)}
                            ${formatRow('Status', data.status)}
                        </div>
                        <div class="col-md-6">
                            <h6>Kontak & Orang Tua</h6>
                            ${formatRow('Phone', data.phone)}
                            ${formatRow('Email', data.email)}
                            ${formatRow('Parent Address', data.parent_address)}
                            ${formatRow('Father Name', data.father_name)}
                            ${formatRow('Mother Name', data.mother_name)}
                            ${formatRow('Father Occupation', data.father_occupation)}
                            ${formatRow('Mother Occupation', data.mother_occupation)}
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Health & Medical</h6>
                            ${formatRow('Baby Birth Weight', data.baby_birth_weight)}
                            ${formatRow('Baby Birth Length', data.baby_birth_length)}
                            ${formatRow('Allergy Food', data.allergy_food)}
                            ${formatRow('Allergy Medicine', data.allergy_medicine)}
                            ${formatRow('Health Statement Approved', data.health_statement_approved ? 'Yes' : 'No')}
                            ${formatRow('Health Approved City', data.health_approved_city)}
                            ${formatRow('Health Approved Date', data.health_approved_date)}
                        </div>
                    </div>
                `;

                content.innerHTML = html;
            } catch (error) {
                content.innerHTML = `<div class="alert alert-danger">${error.message || 'Tidak dapat memuat detail aplikasi.'}</div>`;
            }
        }

    </script>
@endsection
