@extends('layouts.layout')
@section('content')
    <style>
        #branch-table { table-layout: fixed; }
        #branch-table th, #branch-table td { vertical-align: middle; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add Branch</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="branch-table">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th>Name</th>
                                <th style="width: 140px;">Program</th>
                                <th style="width: 120px;">Order</th>
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
                    <h5 class="modal-title">Add Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="add-name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Program</label>
                            <select class="form-select" id="add-program">
                                <option value="primary">Primary</option>
                                <option value="preschool">Preschool</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" id="add-order" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveBranch()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Program</label>
                            <select class="form-select" id="edit-program">
                                <option value="primary">Primary</option>
                                <option value="preschool">Preschool</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" id="edit-order" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateBranch()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Yakin ingin menghapus data ini?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteBranch()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => { if (Array.isArray(arr)) arr.forEach((msg) => messages.push(msg)); });
                if (messages.length) return messages.join('<br>');
            }
            if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
            return fallback;
        }

        const table = $('#branch-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('important-date-branches.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'program', name: 'program' },
                { data: 'sort_order', name: 'sort_order' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function clearAddForm() {
            $('#add-name').val('');
            $('#add-program').val('primary');
            $('#add-order').val('0');
        }

        function saveBranch() {
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('important-date-branches.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#add-name').val(),
                    program: $('#add-program').val(),
                    sort_order: $('#add-order').val()
                },
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(res.message || 'Branch created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan data')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editBranch(id) {
            $.ajax({
                url: `/important-date-branches/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-name').val(r.name);
                    $('#edit-program').val(r.program);
                    $('#edit-order').val(r.sort_order);
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data'); }
            });
        }

        function updateBranch() {
            const id = $('#edit-id').val();
            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/important-date-branches/${id}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    name: $('#edit-name').val(),
                    program: $('#edit-program').val(),
                    sort_order: $('#edit-order').val()
                },
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Branch updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update data')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteBranch(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteBranch() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/important-date-branches/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Branch deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus data')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Hapus'); }
            });
        }
    </script>
@endsection
