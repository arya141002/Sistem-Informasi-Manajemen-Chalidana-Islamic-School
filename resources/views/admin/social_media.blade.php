@extends('layouts.layout')
@section('content')
    <style>
        #social-media-table { table-layout: fixed; }
        #social-media-table th, #social-media-table td { vertical-align: middle; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .icon-preview { display:inline-flex; align-items:center; gap:6px; }
        .icon-preview i { font-size:18px; }
        .color-chip { display:inline-block; width:18px; height:18px; border-radius:4px; border:1px solid #d9dee3; vertical-align:middle; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add {{ $title }}</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="social-media-table">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th style="width: 200px;">Title</th>
                                <th style="width: 200px;">Icon</th>
                                <th style="width: 120px;">Bg Color</th>
                                <th style="width: 260px;">Link</th>
                                <th style="width: 96px;">Action</th>
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
                    <h5 class="modal-title">Add Social Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="add-title" placeholder="Instagram">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" id="add-icon" placeholder="bx bxl-instagram">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bg Color</label>
                            <input type="text" class="form-control" id="add-bgcolor" placeholder="#f5b301">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Link</label>
                            <input type="url" class="form-control" id="add-link" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveSocialMedia()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Social Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" id="edit-icon" placeholder="bx bxl-instagram">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bg Color</label>
                            <input type="text" class="form-control" id="edit-bgcolor" placeholder="#f5b301">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Link</label>
                            <input type="url" class="form-control" id="edit-link">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateSocialMedia()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Social Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this item?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteSocialMedia()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function escapeHtml(value) { return $('<div>').text(value || '').html(); }
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
            $('#add-title, #add-icon, #add-bgcolor, #add-link').val('');
        }

        const table = $('#social-media-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('social-media.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'icon', name: 'icon', render: (d) => d ? `<span class="icon-preview"><i class="${escapeHtml(d)}"></i>${escapeHtml(d)}</span>` : '-' },
                { data: 'bgcolor', name: 'bgcolor', render: (d) => d ? `<span class="color-chip" style="background:${escapeHtml(d)}"></span> ${escapeHtml(d)}` : '-' },
                { data: 'link', name: 'link', render: (d) => d ? `<a href="${escapeHtml(d)}" target="_blank" rel="noopener">${escapeHtml(d)}</a>` : '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function saveSocialMedia() {
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('social-media.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: $('#add-title').val(),
                    icon: $('#add-icon').val(),
                    bgcolor: $('#add-bgcolor').val(),
                    link: $('#add-link').val()
                },
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(res.message || 'Social media created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan data')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editSocialMedia(id) {
            $.ajax({
                url: `/social-media/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-title').val(r.title);
                    $('#edit-icon').val(r.icon);
                    $('#edit-bgcolor').val(r.bgcolor);
                    $('#edit-link').val(r.link);
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data'); }
            });
        }

        function updateSocialMedia() {
            const id = $('#edit-id').val();
            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/social-media/${id}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    title: $('#edit-title').val(),
                    icon: $('#edit-icon').val(),
                    bgcolor: $('#edit-bgcolor').val(),
                    link: $('#edit-link').val()
                },
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Social media updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update data')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteSocialMedia(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteSocialMedia() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/social-media/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Social media deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus data')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Delete'); }
            });
        }
    </script>
@endsection
