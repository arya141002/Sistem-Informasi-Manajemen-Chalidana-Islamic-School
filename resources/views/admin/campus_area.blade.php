@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#campusAreaModal">
                    <i class="bx bx-plus"></i> Add Campus Area
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="campus-area-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Phone</th>
                                <th>Image</th>
                                <th>Address</th>
                                <th>Address (ID)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="campusAreaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Campus Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="add-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="add-title" placeholder="Enter campus title">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="add-slug" placeholder="enter-campus-slug">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-pagehead" class="form-label">Page Head</label>
                            <input type="text" class="form-control" id="add-pagehead" placeholder="Enter page heading">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="add-phone" placeholder="Enter phone number">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-address" class="form-label">Address</label>
                            <textarea class="form-control" id="add-address" rows="3" placeholder="Enter address"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-address-id" class="form-label">Address (ID)</label>
                            <textarea class="form-control" id="add-address-id" rows="3" placeholder="Enter Indonesian address"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-img" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add-img" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="storeCampusArea()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCampusAreaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Campus Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <input type="hidden" id="edit-id">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-title" placeholder="Enter campus title">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="edit-slug" placeholder="enter-campus-slug">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-pagehead" class="form-label">Page Head</label>
                            <input type="text" class="form-control" id="edit-pagehead" placeholder="Enter page heading">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit-phone" placeholder="Enter phone number">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-address" class="form-label">Address</label>
                            <textarea class="form-control" id="edit-address" rows="3" placeholder="Enter address"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-address-id" class="form-label">Address (ID)</label>
                            <textarea class="form-control" id="edit-address-id" rows="3" placeholder="Enter Indonesian address"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img id="edit-img-preview" src="" alt="Campus image" class="img-thumbnail" style="max-height: 120px; display: none;">
                                <p class="text-muted mb-0" id="edit-img-empty">No image uploaded.</p>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-img" class="form-label">Change Image</label>
                            <input type="file" class="form-control" id="edit-img" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updateCampusArea()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCampusAreaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Campus Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this campus area?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeleteCampusArea()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }

        function imageUrl(path) {
            if (!path) return '';
            return `{{ webp_asset('/images/campus_area') }}/${path}`;
        }

        function renderAddress(value) {
            const text = escapeHtml(value);
            return `<div style="white-space: normal; max-width: 260px;">${text}</div>`;
        }

        const campusAreaTable = $('#campus-area-table').DataTable({
            ajax: '{{ route('campus-area.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'title', name: 'title' },
                { data: 'slug', name: 'slug' },
                { data: 'phone', name: 'phone' },
                {
                    data: 'img',
                    name: 'img',
                    render: function (data) {
                        if (!data) return '-';
                        return `<img src="${imageUrl(data)}" alt="Campus" class="img-thumbnail" style="height: 46px; width: 64px; object-fit: cover;">`;
                    }
                },
                {
                    data: 'address',
                    name: 'address',
                    render: function (data) {
                        return renderAddress(data);
                    }
                },
                {
                    data: 'address_id',
                    name: 'address_id',
                    render: function (data) {
                        return renderAddress(data);
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function clearAddForm() {
            $('#add-title').val('');
            $('#add-slug').val('');
            $('#add-pagehead').val('');
            $('#add-phone').val('');
            $('#add-address').val('');
            $('#add-address-id').val('');
            $('#add-img').val('');
        }

        function storeCampusArea() {
            const formData = new FormData();
            formData.append('title', $('#add-title').val());
            formData.append('slug', $('#add-slug').val());
            formData.append('pagehead', $('#add-pagehead').val());
            formData.append('phone', $('#add-phone').val());
            formData.append('address', $('#add-address').val());
            formData.append('address_id', $('#add-address-id').val());
            formData.append('_token', '{{ csrf_token() }}');

            const fileInput = document.getElementById('add-img');
            if (fileInput.files[0]) {
                formData.append('img', fileInput.files[0]);
            }

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('campus-area.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#campusAreaModal').modal('hide');
                    campusAreaTable.ajax.reload();
                    clearAddForm();
                    toastr.success(response.message || 'Campus area created successfully');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to create campus area';
                    toastr.error(errorMsg);
                },
                complete: function () {
                    $('#button-save').prop('disabled', false).text('Save');
                }
            });
        }

        function editForm(id) {
            $.ajax({
                url: `/campus-area/${id}`,
                method: 'GET',
                success: function (response) {
                    $('#edit-id').val(response.id);
                    $('#edit-title').val(response.title);
                    $('#edit-slug').val(response.slug);
                    $('#edit-pagehead').val(response.pagehead);
                    $('#edit-phone').val(response.phone);
                    $('#edit-address').val(response.address);
                    $('#edit-address-id').val(response.address_id);
                    $('#edit-img').val('');

                    if (response.img) {
                        $('#edit-img-preview').attr('src', imageUrl(response.img)).show();
                        $('#edit-img-empty').hide();
                    } else {
                        $('#edit-img-preview').hide();
                        $('#edit-img-empty').show();
                    }

                    $('#editCampusAreaModal').modal('show');
                },
                error: function () {
                    toastr.error('Failed to fetch campus area data');
                }
            });
        }

        function updateCampusArea() {
            const id = $('#edit-id').val();
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('title', $('#edit-title').val());
            formData.append('slug', $('#edit-slug').val());
            formData.append('pagehead', $('#edit-pagehead').val());
            formData.append('phone', $('#edit-phone').val());
            formData.append('address', $('#edit-address').val());
            formData.append('address_id', $('#edit-address-id').val());

            const fileInput = document.getElementById('edit-img');
            if (fileInput.files[0]) {
                formData.append('img', fileInput.files[0]);
            }

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/campus-area/${id}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#editCampusAreaModal').modal('hide');
                    campusAreaTable.ajax.reload();
                    toastr.success(response.message || 'Campus area updated successfully');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to update campus area';
                    toastr.error(errorMsg);
                },
                complete: function () {
                    $('#button-update').prop('disabled', false).text('Update');
                }
            });
        }

        function deleteData(id) {
            $('#delete-id').val(id);
            $('#deleteCampusAreaModal').modal('show');
        }

        function confirmDeleteCampusArea() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');

            $.ajax({
                url: `/campus-area/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#deleteCampusAreaModal').modal('hide');
                    campusAreaTable.ajax.reload();
                    toastr.success(response.message || 'Campus area deleted successfully');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to delete campus area';
                    toastr.error(errorMsg);
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false).text('Delete');
                }
            });
        }
    </script>
@endsection
