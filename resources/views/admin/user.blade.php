@extends('layouts.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <!-- Bootstrap Table with Caption -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal"><i class="bx bx-plus"></i> Add User</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="menu-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Add New User</h3>
                        <p class="mb-4">Please fill the form to add a new user.</p>
                    </div>
                    <form id="addUserForm">
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter user name" required />
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter user email" required />
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter user password" required />
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="role_id">Role</label>
                            <select id="role_id" name="role_id" class="form-select" required>
                                <option value="" disabled selected>Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-12 mt-3">
                            <button type="button" id="button-add-user" onclick="simpanuser()" class="btn btn-primary me-1">Add User</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Edit User</h3>
                        <p class="mb-4">Please fill the form to edit the user.</p>
                    </div>
                    <form id="editUserForm">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="edit_name">Name</label>
                            <input type="text" id="edit_name" name="name" class="form-control" placeholder="Enter user name" required />
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="edit_email">Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control" placeholder="Enter user email" required />
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="edit_role_id">Role</label>
                            <select id="edit_role_id" name="role_id" class="form-select" required>
                                <option value="" disabled selected>Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-12 mt-3">
                            <button type="button" id="button-edit-user" onclick="updateuser()" class="btn btn-primary me-1">Update User</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-4">
                        <h3 class="mb-2">Delete User</h3>
                        <p class="mb-4">Are you sure you want to delete this user?</p>
                    </div>
                    <form id="deleteUserForm">
                        <input type="hidden" id="delete_user_id" name="user_id">
                        <div class="col-12 col-md-12 mt-3">
                            <button type="button" id="button-delete-user" onclick="deleteuser($('#delete_user_id').val())" class="btn btn-danger me-1">Delete User</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="passwordUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-4">
                        <h3 class="mb-2">Change Password</h3>
                        <p class="mb-4">Please fill the form to change the user's password.</p>
                    </div>
                    <form id="passwordUserForm">
                        <input type="hidden" id="password_user_id" name="user_id">
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required />
                        </div>
                        <div class="col-12 col-md-12 mt-3">
                            <label class="form-label" for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required />
                        </div>
                        <div class="col-12 col-md-12 mt-3">
                            <button type="button" id="button-password-user" onclick="changepassword()" class="btn btn-primary me-1">Change Password</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function resetForm() {
            $('#addUserForm')[0].reset();
            $('#button-add-user').prop('disabled', false).text('Add User');
        }

        let tabelku = $('#menu-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role.display_name', name: 'role.display_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function simpanuser(){
            let name = $('#name').val();
            let email = $('#email').val();
            let password = $('#password').val();
            let role_id = $('#role_id').val();
            if (!name || !email || !password || !role_id) {
                toastr.error('Please fill all fields');
                return;
            }
            $('#button-add-user').prop('disabled', true).text('Adding...');
            $.ajax({
                url: "{{ route('user.store') }}",
                method: 'POST',
                data: {
                    name: name,
                    email: email,
                    password: password,
                    role_id: role_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#userModal').modal('hide');
                    tabelku.ajax.reload();
                    resetForm();
                    toastr.success("User added successfully!");
                },
                error: function(xhr) {
                    toastr.error("Failed to add user. Please try again.");
                    resetForm();
                }
            });
        }

        function editUser(id){
            $.ajax({
                url: "{{ url('user') }}/" + id,
                method: 'GET',
                success: function(response) {
                    $('#edit_user_id').val(response.id);
                    $('#edit_name').val(response.name);
                    $('#edit_email').val(response.email);
                    $('#edit_role_id').val(response.role_id);
                    $('#editUserModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error("Failed to fetch user data. Please try again.");
                }
            });
        }

        function updateuser(){
            let user_id = $('#edit_user_id').val();
            let name = $('#edit_name').val();
            let email = $('#edit_email').val();
            let role_id = $('#edit_role_id').val();
            if (!name || !email || !role_id) {
                toastr.error('Please fill all fields except password');
                return;
            }
            $('#button-edit-user').prop('disabled', true).text('Updating...');
            $.ajax({
                url: "{{ url('user') }}/" + user_id,
                method: 'PUT',
                data: {
                    name: name,
                    email: email,
                    role_id: role_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    tabelku.ajax.reload();
                    resetForm();
                    toastr.success("User updated successfully!");
                },
                error: function(xhr) {
                    toastr.error("Failed to update user. Please try again.");
                    resetForm();
                }
            });
        }

        let deleteUserId = null;
        function deleteUser(id){
            deleteUserId = id;
            $('#delete_user_id').val(id);
            $('#deleteUserModal').modal('show');
        }

        function deleteuser(id){
            $('#button-delete-user').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: "{{ url('user') }}/" + id,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteUserModal').modal('hide');
                    tabelku.ajax.reload();
                    toastr.success("User deleted successfully!");
                },
                error: function(xhr) {
                    toastr.error("Failed to delete user. Please try again.");
                }
            });
        }

        function changePassword(id){
            $('#password_user_id').val(id);
            $('#passwordUserModal').modal('show');
        }

        function changepassword(){
            let user_id = $('#password_user_id').val();
            let new_password = $('#new_password').val();
            let confirm_password = $('#confirm_password').val();
            if (!new_password) {
                toastr.error('Please enter a new password');
                return;
            }
            $('#button-password-user').prop('disabled', true).text('Changing...');
            $.ajax({
                url: "{{ url('user') }}/" + user_id + "/change-password",
                method: 'POST',
                data: {
                    password: new_password,
                    password_confirmation: confirm_password,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#passwordUserModal').modal('hide');
                    toastr.success("Password changed successfully!");
                },
                error: function(xhr) {
                    toastr.error("Failed to change password. Please try again.");
                }
            });
        }
    </script>
@endsection