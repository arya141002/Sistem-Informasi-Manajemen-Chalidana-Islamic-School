@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <!-- Bootstrap Table with Caption -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roleModal"><i class="bx bx-plus"></i> Add Role</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="menu-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Display Name</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="roleForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control" id="display_name" name="display_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="button-role" onclick="simpanrole()" class="btn btn-primary">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRoleForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_role_id">
                        <div class="mb-3">
                            <label for="edit_display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control" id="edit_display_name" name="display_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="button-edit-role" onclick="updateRole()" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this role?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-delete-role" class="btn btn-danger">Delete Role</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function resetForm() {
            $('#display_name').val('');
            $('#name').val('');
        }

        let menuTable = $('#menu-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('role.data') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'display_name', name: 'display_name' },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function editRole(id) {
            $.get('/role/' + id, function(data) {
                $('#edit_role_id').val(data.id);
                $('#edit_display_name').val(data.display_name);
                $('#edit_name').val(data.name);
                $('#editRoleModal').modal('show');
            });
        }

        function simpanrole() {
            let name = $('#name').val();
            let display_name = $('#display_name').val();
            if (!name || !display_name) {
                toastr.error('Please fill in all fields');
                return;
            }
            $('#button-role').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('role.store') }}',
                method: 'POST',
                data: {
                    name: name,
                    display_name: display_name,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $('#roleModal').modal('hide');
                    menuTable.ajax.reload();
                    resetForm();
                    toastr.success('Role saved successfully');
                },
                error: function() {
                    $('#roleModal').modal('hide');
                    resetForm();
                    toastr.error('Failed to save role');
                },
                complete: function() {
                    $('#button-role').prop('disabled', false).text('Save Role');
                }
            });
        }

        function updateRole() {
            let id = $('#edit_role_id').val();
            let name = $('#edit_name').val();
            let display_name = $('#edit_display_name').val();
            if (!name || !display_name) {
                toastr.error('Please fill in all fields');
                return;
            }
            $('#button-edit-role').prop('disabled', true).text('Updating...');
            $.ajax({
                url: '/role/' + id,
                method: 'PUT',
                data: {
                    name: name,
                    display_name: display_name,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $('#editRoleModal').modal('hide');
                    menuTable.ajax.reload();
                    toastr.success('Role updated successfully');
                },
                error: function() {
                    $('#editRoleModal').modal('hide');
                    toastr.error('Failed to update role');
                },
                complete: function() {
                    $('#button-edit-role').prop('disabled', false).text('Update Role');
                }
            });
        }

       let deleteRoleId = null;
        function deleteRole(id) {
            deleteRoleId = id;
            $('#deleteRoleModal').modal('show');
        }

        $('#button-delete-role').click(function() {
            if (!deleteRoleId) return;
            $('#button-delete-role').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: '/role/' + deleteRoleId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $('#deleteRoleModal').modal('hide');
                    menuTable.ajax.reload();
                    toastr.success('Role deleted successfully');
                },
                error: function() {
                    $('#deleteRoleModal').modal('hide');
                    toastr.error('Failed to delete role');
                },
                complete: function() {
                    $('#button-delete-role').prop('disabled', false).text('Delete Role');
                    deleteRoleId = null;
                }
            });
        });
    </script>
@endsection