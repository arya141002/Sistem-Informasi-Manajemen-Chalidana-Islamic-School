@extends('layouts.layout')
@section('content')
    <style>
        #youtube-short-table { table-layout: fixed; }
        #youtube-short-table th, #youtube-short-table td { vertical-align: middle; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add Link</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="youtube-short-table">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th style="width: 200px;">Campus</th>
                                <th style="width: 320px;">Youtube Link</th>
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
                    <h5 class="modal-title">Add Youtube Short Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Campus</label>
                            <select class="form-select" id="add-campus_id">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Youtube Link</label>
                            <input type="url" class="form-control" id="add-youtube_link" placeholder="https://youtube.com/shorts/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveYoutubeShort()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Youtube Short Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Campus</label>
                            <select class="form-select" id="edit-campus_id">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Youtube Link</label>
                            <input type="url" class="form-control" id="edit-youtube_link">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateYoutubeShort()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Youtube Short Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Yakin ingin menghapus data ini?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteYoutubeShort()">Hapus</button>
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
            $('#add-campus_id, #add-title, #add-youtube_link').val('');
        }

        const table = $('#youtube-short-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('youtube-short-link.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'campus.title', name: 'campus.title', defaultContent: '-' },
                { data: 'youtube_short', name: 'youtube_short', render: (d) => d ? `<a href="${escapeHtml(d)}" target="_blank" rel="noopener">${escapeHtml(d)}</a>` : '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function saveYoutubeShort() {
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('youtube-short-link.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    campus_id: $('#add-campus_id').val(),
                    title: $('#add-title').val(),
                    youtube_link: $('#add-youtube_link').val()
                },
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(res.message || 'Youtube short link created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan data')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editYoutubeShort(id) {
            $.ajax({
                url: `/youtube-short-link/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-campus_id').val(r.campus_id);
                    $('#edit-title').val(r.title);
                    $('#edit-youtube_link').val(r.youtube_short);
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data'); }
            });
        }

        function updateYoutubeShort() {
            const id = $('#edit-id').val();
            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/youtube-short-link/${id}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    campus_id: $('#edit-campus_id').val(),
                    title: $('#edit-title').val(),
                    youtube_link: $('#edit-youtube_link').val()
                },
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Youtube short link updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update data')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteYoutubeShort(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteYoutubeShort() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/youtube-short-link/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Youtube short link deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus data')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Hapus'); }
            });
        }
    </script>
@endsection
