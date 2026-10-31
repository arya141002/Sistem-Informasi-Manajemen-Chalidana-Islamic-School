@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add Category</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="gallery-category-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Location</th>
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
                    <h5 class="modal-title">Add Gallery Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">School Branch</label>
                            <select class="form-select" id="add-campus_id">
                                <option value="">Select School Branch</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Title (EN)</label><input type="text" class="form-control" id="add-title"></div>
                        <div class="col-md-6"><label class="form-label">Title (ID)</label><input type="text" class="form-control" id="add-title_id"></div>
                        <div class="col-md-6"><label class="form-label">Location</label><input type="text" class="form-control" id="add-location"></div>
                        <div class="col-md-6"><label class="form-label">Image</label><input type="file" class="form-control" id="add-img" accept="image/*"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="saveCategory()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Gallery Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">School Branch</label>
                            <select class="form-select" id="edit-campus_id">
                                <option value="">Select School Branch</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Title (EN)</label><input type="text" class="form-control" id="edit-title"></div>
                        <div class="col-md-6"><label class="form-label">Title (ID)</label><input type="text" class="form-control" id="edit-title_id"></div>
                        <div class="col-md-6"><label class="form-label">Location</label><input type="text" class="form-control" id="edit-location"></div>
                        <div class="col-md-6"><label class="form-label">Change Image</label><input type="file" class="form-control" id="edit-img" accept="image/*"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updateCategory()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Delete Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><p class="mb-0">Yakin ingin menghapus data ini?</p><input type="hidden" id="delete-id"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeleteCategory()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function imageUrl(path) { return path ? `/public/images/gallery/${path}` : ''; }
        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => { if (Array.isArray(arr)) arr.forEach((msg) => messages.push(msg)); });
                if (messages.length) return messages.join('<br>');
            }
            if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
            return fallback;
        }

        const table = $('#gallery-category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('gallery-category.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'img', name: 'img', render: (d) => d ? `<img src="${imageUrl(d)}" class="img-thumbnail" style="height:52px;width:78px;object-fit:cover;">` : '-' },
                { data: 'title', name: 'title' },
                { data: 'location', name: 'location' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function saveCategory() {
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('campus_id', $('#add-campus_id').val());
            fd.append('title', $('#add-title').val());
            fd.append('title_id', $('#add-title_id').val());
            fd.append('location', $('#add-location').val());
            const img = document.getElementById('add-img').files[0];
            if (img) fd.append('img', img);

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('gallery-category.store') }}',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#addModal').modal('hide');
                    $('#add-campus_id, #add-title, #add-title_id, #add-location').val('');
                    $('#add-img').val('');
                    table.ajax.reload();
                    toastr.success(res.message || 'Gallery category created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan gallery category')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editCategory(id) {
            $.ajax({
                url: `/gallery-category/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-campus_id').val(r.campus_id);
                    $('#edit-title').val(r.title);
                    $('#edit-title_id').val(r.title_id);
                    $('#edit-location').val(r.location);
                    $('#edit-img').val('');
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data gallery category'); }
            });
        }

        function updateCategory() {
            const id = $('#edit-id').val();
            const fd = new FormData();
            fd.append('_method', 'PUT');
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('campus_id', $('#edit-campus_id').val());
            fd.append('title', $('#edit-title').val());
            fd.append('title_id', $('#edit-title_id').val());
            fd.append('location', $('#edit-location').val());
            const img = document.getElementById('edit-img').files[0];
            if (img) fd.append('img', img);

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/gallery-category/${id}`,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Gallery category updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update gallery category')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteCategory(id) { $('#delete-id').val(id); $('#deleteModal').modal('show'); }

        function confirmDeleteCategory() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/gallery-category/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Gallery category deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menghapus gallery category')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Hapus'); }
            });
        }
    </script>
@endsection
