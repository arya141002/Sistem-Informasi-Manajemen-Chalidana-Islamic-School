@extends('layouts.layout')
@section('content')
    <style>
        .rich-editor { border: 1px solid #d9dee3; border-radius: .375rem; background: #fff; }
        .rich-editor__toolbar { display:flex; flex-wrap:wrap; gap:.35rem; padding:.6rem; border-bottom:1px solid #eceef1; background:#f8f9fb; }
        .rich-editor__toolbar button { border:1px solid #d9dee3; border-radius:.3rem; background:#fff; color:#566a7f; padding:.25rem .55rem; font-size:.8rem; }
        .rich-editor__content { min-height:160px; padding:.85rem; outline:none; overflow-y:auto; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <!-- Bootstrap Table with Caption -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#Modal"><i class="bx bx-plus"></i> Add Teacher/Staff</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="teacher-and-staff-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Campus</th>
                                <th>Content</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Add Teacher/Staff</h3>
                    </div>
                    <form id="teacher-and-staff-form" class="row g-3">
                        <div class="col-12">
                            <label for="img" class="form-label">Image</label>
                            <input type="file" id="img" name="img" class="form-control">
                        </div>
                        <div class="col-12">
                            <label for="campus_id" class="form-label">Campus</label>
                            <select id="campus_id" name="campus_id" class="form-select">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" class="form-select">
                                <option value="">Select Role</option>
                                <option value="teacher">Teacher</option>
                                <option value="staff">Staff</option>
                                <option value="principal">Principal</option>
                                <option value="ob">Office Boy/Girl</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <div class="rich-editor" data-editor-target="content">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="bold"><b>B</b></button>
                                    <button type="button" data-command="italic"><i>I</i></button>
                                    <button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button>
                                    <button type="button" data-command="insertOrderedList">Number</button>
                                    <button type="button" data-action="link">Link</button>
                                    <button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="content" name="content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" id="submit-teacher-and-staff" onclick="simpan()" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Edit Teacher/Staff</h3>
                    </div>
                    <form id="teacher-and-staff-edit-form" class="row g-3">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-12">
                            <label for="edit_img" class="form-label">Image</label>
                            <input type="file" id="edit_img" name="img" class="form-control">
                        </div>
                        <div class="col-12">
                            <label for="edit_campus_id" class="form-label">Campus</label>
                            <select id="edit_campus_id" name="campus_id" class="form-select">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_role" class="form-label">Role</label>
                            <select id="edit_role" name="role" class="form-select">
                                <option value="">Select Role</option>
                                <option value="teacher">Teacher</option>
                                <option value="staff">Staff</option>
                                <option value="principal">Principal</option>
                                <option value="ob">Office Boy/Girl</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_content" class="form-label">Content</label>
                            <div class="rich-editor" data-editor-target="edit_content">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="bold"><b>B</b></button>
                                    <button type="button" data-command="italic"><i>I</i></button>
                                    <button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button>
                                    <button type="button" data-command="insertOrderedList">Number</button>
                                    <button type="button" data-action="link">Link</button>
                                    <button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="edit_content" name="content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-12 text-center">    
                            <button type="button" id="update-teacher-and-staff" onclick="updatex()" class="btn btn-primary me-sm-3 me-1">Update</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Delete Teacher/Staff</h3>
                        <p>Are you sure you want to delete this Teacher/Staff?</p>
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" id="confirm-delete" class="btn btn-danger me-sm-3 me-1">Delete</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const editorStore = {};

        function initRichEditors() {
            document.querySelectorAll('.rich-editor').forEach((box) => {
                const target = box.getAttribute('data-editor-target');
                const textarea = document.getElementById(target);
                const content = box.querySelector('.rich-editor__content');
                const buttons = box.querySelectorAll('.rich-editor__toolbar button');
                if (!textarea || !content) return;

                const sync = () => { textarea.value = content.innerHTML.trim(); };
                content.innerHTML = textarea.value || '';
                content.addEventListener('input', sync);
                content.addEventListener('blur', sync);

                buttons.forEach((btn) => {
                    btn.addEventListener('click', function () {
                        content.focus();
                        const command = this.getAttribute('data-command');
                        const value = this.getAttribute('data-value');
                        const action = this.getAttribute('data-action');

                        if (action === 'link') {
                            const url = prompt('Masukkan URL link:');
                            if (url) document.execCommand('createLink', false, url);
                        } else if (command) {
                            document.execCommand(command, false, value || null);
                        }
                        sync();
                    });
                });

                editorStore[target] = {
                    setValue: (html) => { content.innerHTML = html || ''; sync(); },
                    clear: () => { content.innerHTML = ''; sync(); }
                };
                sync();
            });
        }

        function setEditorValue(target, html) {
            if (editorStore[target]) editorStore[target].setValue(html);
        }

        function clearEditorValue(target) {
            if (editorStore[target]) editorStore[target].clear();
        }

        initRichEditors();

        let teacherAndStaffTable = $('#teacher-and-staff-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teacher-and-staff.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'img', name: 'img', render: function(data) {
                    return '<img src="/images/teachernstaff/' + data + '" alt="Image" width="100">';
                }},
                { data: 'campus.title', name: 'campus.title' },
                { data: 'content', name: 'content', render: function(data) { return data || ''; } },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function simpan() {
            let img = $('#img')[0].files[0];
            let campus_id = $('#campus_id').val();
            let role = $('#role').val();
            let content = $('#content').val();
            if (!img || !campus_id || !role || !content) {
                $('#img, #campus_id, #role, #content').addClass('is-invalid');
                return;
            }
            let formData = new FormData();
            formData.append('img', img);
            formData.append('campus_id', campus_id);
            formData.append('role', role);
            formData.append('content', content);
            formData.append('_token', '{{ csrf_token() }}');
            $('#submit-teacher-and-staff').prop('disabled', true).text('Saving...');
            $.ajax({
                url: "{{ route('teacher-and-staff.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#Modal').modal('hide');
                    teacherAndStaffTable.ajax.reload();
                    $('#teacher-and-staff-form')[0].reset();
                    clearEditorValue('content');
                    toastr.success('Teacher/Staff added successfully');
                    $('#submit-teacher-and-staff').prop('disabled', false).text('Submit');
                },
                error: function(xhr) {
                    toastr.error('Failed to add Teacher/Staff');
                    $('#submit-teacher-and-staff').prop('disabled', false).text('Submit');
                }
            });
        }

        function edit(id) {
            $.ajax({
                url: "/our-teacher-and-staff/" + id,
                method: 'GET',
                success: function(response) {
                    $('#edit_id').val(response.id);
                    $('#edit_campus_id').val(response.campus_id);
                    $('#edit_role').val(response.role);
                    setEditorValue('edit_content', response.content);
                    $('#EditModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Failed to fetch Teacher/Staff data');
                }
            });
        }

        function updatex() {
            let id = $('#edit_id').val();
            let img = $('#edit_img')[0].files[0];
            let campus_id = $('#edit_campus_id').val();
            let role = $('#edit_role').val();
            let content = $('#edit_content').val();
            if (!campus_id || !role || !content) {
                $('#edit_campus_id, #edit_role, #edit_content').addClass('is-invalid');
                return;
            }
            let formData = new FormData();
            if (img) {
                formData.append('img', img);
            }
            formData.append('campus_id', campus_id);
            formData.append('role', role);
            formData.append('content', content);
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');
            $('#update-teacher-and-staff').prop('disabled', true).text('Updating...');
            $.ajax({
                url: "/our-teacher-and-staff/" + id,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#EditModal').modal('hide');
                    teacherAndStaffTable.ajax.reload();
                    toastr.success('Teacher/Staff updated successfully');
                    $('#update-teacher-and-staff').prop('disabled', false).text('Update');
                },
                error: function(xhr) {
                    toastr.error('Failed to update Teacher/Staff');
                    $('#update-teacher-and-staff').prop('disabled', false).text('Update');
                }
            });
        }

        function confirmDelete(id) {
            $('#confirm-delete').data('id', id);
            $('#deleteModal').modal('show');
        }

        $('#confirm-delete').click(function() {
            let id = $(this).data('id');
            $.ajax({
                url: "/our-teacher-and-staff/" + id,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    teacherAndStaffTable.ajax.reload();
                    toastr.success('Teacher/Staff deleted successfully');
                },
                error: function(xhr) {
                    toastr.error('Failed to delete Teacher/Staff');
                }
            });
        });
    </script>
@endsection
