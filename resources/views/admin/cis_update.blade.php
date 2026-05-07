@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bx bx-plus"></i> Add CIS Update
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="cis-update-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>URL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add CIS Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Title (EN)</label><input type="text" class="form-control" id="add-title"></div>
                        <div class="col-md-6"><label class="form-label">Title (ID)</label><input type="text" class="form-control" id="add-title_id"></div>
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <label class="form-label">Image File</label>
                                <input type="file" class="form-control" id="add-img" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6"><label class="form-label">Status</label>
                            <select class="form-select" id="add-status">
                                <option value="1">On</option>
                                <option value="0">Off</option>
                            </select>
                        </div>
                        <div class="col-md-12"><label class="form-label">URL</label><input type="url" class="form-control" id="add-url" placeholder="https://..."></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="savecisupdate()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit CIS Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Title (EN)</label><input type="text" class="form-control" id="edit-title"></div>
                        <div class="col-md-6"><label class="form-label">Title (ID)</label><input type="text" class="form-control" id="edit-title_id"></div>
                        <div class="col-md-6">
                            <label class="form-label">Ganti Image (Kosongkan jika tidak diubah)</label>
                            <input type="file" class="form-control" id="edit-img" accept="image/*">
                            <small id="edit-img-preview" class="text-muted"></small>
                        </div>
                        <div class="col-md-6"><label class="form-label">Status</label>
                            <select class="form-select" id="edit-status">
                                <option value="1">On</option>
                                <option value="0">Off</option>
                            </select>
                        </div>
                        <div class="col-md-12"><label class="form-label">URL</label><input type="url" class="form-control" id="edit-url" placeholder="https://..."></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updatecisupdate()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete CIS Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Yakin ingin menghapus data ini?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeletecisupdate()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const storageBase = '{{ asset('images/popup') }}';
        function imageUrl(path) {
            if (!path) return '';
            if (path.startsWith('http://') || path.startsWith('https://')) return path;
            return `${storageBase}/${path}`;
        }

        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }

        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => { if (Array.isArray(arr)) arr.forEach((msg) => messages.push(msg)); });
                if (messages.length) return messages.join('<br>');
            }
            if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
            return fallback;
        }

        function clearAddForm() {
            $('#add-title, #add-title_id, #add-img, #add-url').val('');
            $('#add-status').val('1');
        }

        let cistable = $('#cis-update-table').DataTable({
            ajax: '{{ route('cis-update.data')}}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'title', name: 'title'},
                { data: 'img', name: 'img',
                    render: function (data){
                        if (!data) return '-';
                        return `<img src="${imageUrl(data)}" alt="CIS" class="img-thumbnail" style="height: 46px; width: 64px; object-fit: cover;">`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        const isOn = String(data) === '1';
                        const label = isOn ? 'On' : 'Off';
                        const btnClass = isOn ? 'btn-success' : 'btn-secondary';
                        const next = isOn ? 0 : 1;
                        return `<button type="button" class="btn btn-sm ${btnClass}" data-action="toggle-status" data-id="${row.id}" data-next="${next}">${label}</button>`;
                    }
                },
                { data: 'url', name: 'url',
                    render: function (data) {
                        if (!data) return '-';
                        const safe = escapeHtml(data);
                        let label = safe;
                        try {
                            const u = new URL(data);
                            label = u.hostname + (u.pathname && u.pathname !== '/' ? '/…' : '');
                        } catch (e) {
                            if (safe.length > 28) label = safe.slice(0, 28) + '...';
                        }
                        return `<a href="${safe}" target="_blank" rel="noopener">${label}</a>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $('#cis-update-table').on('click', '[data-action="toggle-status"]', function () {
            const btn = $(this);
            const id = btn.data('id');
            const next = btn.data('next');
            btn.prop('disabled', true).text('...');
            $.ajax({
                url: `/cis-update/${id}/status`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', status: next },
                success: function (res) {
                    cistable.ajax.reload(null, false);
                    toastr.success(res.message || 'Status updated successfully');
                },
                error: function (xhr) {
                    cistable.ajax.reload(null, false);
                    toastr.error(formatValidationErrors(xhr, 'Gagal update status'));
                }
            });
        });

        function savecisupdate() {
            // 1. Gunakan FormData untuk menangani upload file
            let fd = new FormData();
            fd.append('title', $('#add-title').val());
            fd.append('title_id', $('#add-title_id').val());
            fd.append('status', $('#add-status').val());
            fd.append('url', $('#add-url').val());
            fd.append('_token', '{{ csrf_token() }}');

            // Ambil file dari input file (pastikan ID-nya benar)
            let imgFile = $('#add-img').prop('files')[0];
            if (imgFile) {
                fd.append('img', imgFile);
            }

            $('#button-save').prop('disabled', true).text('Saving...');

            $.ajax({
                url: '{{ route('cis-update.store') }}',
                method: 'POST',
                data: fd, // Kirim objek FormData
                processData: false, // WAJIB: Jangan proses data jadi string
                contentType: false, // WAJIB: Biar browser atur header boundary-nya
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    cistable.ajax.reload();
                    toastr.success(res.message || 'CIS Update created successfully');
                },
                error: function (xhr) { 
                    toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan CIS Update')); 
                },
                complete: function () { 
                    $('#button-save').prop('disabled', false).text('Save'); 
                }
            });
        }

        function editcisupdate(id) {
            $.ajax({
                url: `/cis-update/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-title').val(r.title);
                    $('#edit-title_id').val(r.title_id);
                    $('#edit-status').val(String(r.status));
                    $('#edit-url').val(r.url);
                    
                    // Reset input file
                    $('#edit-img').val('');
                    
                    // Tampilkan info file lama
                    if(r.img) {
                        $('#edit-img-preview').html(`File saat ini: ${r.img.split('/').pop()}`);
                    }

                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data'); }
            });
        }

        function updatecisupdate() {
            const id = $('#edit-id').val();
            
            // Gunakan FormData untuk mendukung upload file
            let fd = new FormData();
            fd.append('_method', 'PUT'); // Method spoofing untuk Laravel
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('title', $('#edit-title').val());
            fd.append('title_id', $('#edit-title_id').val());
            fd.append('status', $('#edit-status').val());
            fd.append('url', $('#edit-url').val());

            // Ambil file jika ada yang dipilih
            let imgFile = $('#edit-img').prop('files')[0];
            if (imgFile) {
                fd.append('img', imgFile);
            }

            $('#button-update').prop('disabled', true).text('Updating...');

            $.ajax({
                url: `/cis-update/${id}`,
                method: 'POST', // Gunakan POST + _method PUT
                data: fd,
                processData: false, // WAJIB
                contentType: false, // WAJIB
                success: function (res) {
                    $('#editModal').modal('hide');
                    cistable.ajax.reload();
                    toastr.success(res.message || 'CIS Update updated successfully');
                },
                error: function (xhr) { 
                    toastr.error(formatValidationErrors(xhr, 'Gagal update CIS Update')); 
                },
                complete: function () { 
                    $('#button-update').prop('disabled', false).text('Update'); 
                }
            });
        }

        function deletecisupdate(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeletecisupdate() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/cis-update/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    cistable.ajax.reload();
                    toastr.success(res.message || 'CIS Update deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus CIS Update')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Hapus'); }
            });
        }
    </script>
@endsection
