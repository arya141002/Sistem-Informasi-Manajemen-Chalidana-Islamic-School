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
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="category-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Slug</th>
                                <th>Title</th>
                                <th>Title ID</th>
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
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" id="slug" class="form-control" placeholder="Masukkan Slug" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" id="title" class="form-control" placeholder="Masukkan Title" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="title_id">Title ID</label>
                            <input type="text" id="title_id" class="form-control" placeholder="Masukkan Title ID" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveCategory()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit_id" />
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="edit_slug">Slug</label>
                            <input type="text" id="edit_slug" class="form-control" placeholder="Masukkan Slug" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="edit_title">Title</label>
                            <input type="text" id="edit_title" class="form-control" placeholder="Masukkan Title" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="edit_title_id">Title ID</label>
                            <input type="text" id="edit_title_id" class="form-control" placeholder="Masukkan Title ID" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateCategory()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Yakin ingin menghapus data ini?</p>
                    <input type="hidden" id="delete_id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteCategory()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const table = $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('whatsup-category.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'slug', name: 'slug' },
                { data: 'title', name: 'title' },
                { data: 'title_id', name: 'title_id' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => {
                    if (Array.isArray(arr)) arr.forEach((msg) => messages.push(msg));
                });
                if (messages.length) return messages.join('<br>');
            }

            if (xhr.responseJSON && xhr.responseJSON.message) {
                return xhr.responseJSON.message;
            }

            return fallback;
        }

        function clearAddForm() {
            $('#slug, #title, #title_id').val('').removeClass('is-invalid');
        }

        function saveCategory() {
            const slug = $('#slug').val().trim();
            const title = $('#title').val().trim();
            const title_id = $('#title_id').val().trim();

            $('#slug, #title, #title_id').removeClass('is-invalid');
            if (!slug || !title || !title_id) {
                if (!slug) $('#slug').addClass('is-invalid');
                if (!title) $('#title').addClass('is-invalid');
                if (!title_id) $('#title_id').addClass('is-invalid');
                return;
            }

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('whatsup-category.store') }}',
                method: 'POST',
                data: {
                    slug: slug,
                    title: title,
                    title_id: title_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(response.message || 'Category created successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan category'));
                },
                complete: function () {
                    $('#button-save').prop('disabled', false).text('Submit');
                }
            });
        }

        function editx(id) {
            $.ajax({
                url: `/whatsup-category/${id}`,
                method: 'GET',
                success: function (response) {
                    $('#edit_id').val(response.id);
                    $('#edit_slug').val(response.slug);
                    $('#edit_title').val(response.title);
                    $('#edit_title_id').val(response.title_id);
                    $('#editModal').modal('show');
                },
                error: function () {
                    toastr.error('Gagal mengambil data category');
                }
            });
        }

        function updateCategory() {
            const id = $('#edit_id').val();
            const slug = $('#edit_slug').val().trim();
            const title = $('#edit_title').val().trim();
            const title_id = $('#edit_title_id').val().trim();

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/whatsup-category/${id}`,
                method: 'PUT',
                data: {
                    slug: slug,
                    title: title,
                    title_id: title_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message || 'Category updated successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Gagal mengupdate category'));
                },
                complete: function () {
                    $('#button-update').prop('disabled', false).text('Update');
                }
            });
        }

        function deletex(id) {
            $('#delete_id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteCategory() {
            const id = $('#delete_id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/whatsup-category/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message || 'Category deleted successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Gagal menghapus category'));
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false).text('Hapus');
                }
            });
        }
    </script>
@endsection
