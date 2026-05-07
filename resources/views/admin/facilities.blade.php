@extends('layouts.layout')
@section('content')
    <style>
        .rich-editor {
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            background: #fff;
        }

        .rich-editor__toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            padding: 0.6rem;
            border-bottom: 1px solid #eceef1;
            background: #f8f9fb;
        }

        .rich-editor__toolbar button {
            border: 1px solid #d9dee3;
            border-radius: 0.3rem;
            background: #fff;
            color: #566a7f;
            padding: 0.25rem 0.55rem;
            font-size: 0.8rem;
            line-height: 1.3;
        }

        .rich-editor__toolbar button:hover {
            background: #f1f3f5;
        }

        .rich-editor__content {
            min-height: 180px;
            padding: 0.85rem;
            outline: none;
            overflow-y: auto;
        }

        .rich-editor__content ul,
        .rich-editor__content ol {
            padding-left: 1.5rem;
        }

        .rich-editor__content ul {
            list-style: disc;
        }

        .rich-editor__content ol {
            list-style: decimal;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#facilityModal"><i class="bx bx-plus"></i> Add Facility</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="facility-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Campus</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="facilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Facility</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="add-campus_id" class="form-label">Campus</label>
                            <select class="form-select" id="add-campus_id">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-img" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add-img" accept="image/*">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-title" class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="add-title" placeholder="Enter title in English">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-title_id" class="form-label">Title (ID)</label>
                            <input type="text" class="form-control" id="add-title_id" placeholder="Enter title in Indonesian">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-content" class="form-label">Content (EN)</label>
                            <div class="rich-editor" data-editor-target="add-content">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="formatBlock" data-value="h2">H2</button>
                                    <button type="button" data-command="bold"><b>B</b></button>
                                    <button type="button" data-command="italic"><i>I</i></button>
                                    <button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button>
                                    <button type="button" data-command="insertOrderedList">Number</button>
                                    <button type="button" data-command="formatBlock" data-value="blockquote">Quote</button>
                                    <button type="button" data-action="link">Link</button>
                                    <button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="add-content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="add-content_id" class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="add-content_id">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="formatBlock" data-value="h2">H2</button>
                                    <button type="button" data-command="bold"><b>B</b></button>
                                    <button type="button" data-command="italic"><i>I</i></button>
                                    <button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button>
                                    <button type="button" data-command="insertOrderedList">Number</button>
                                    <button type="button" data-command="formatBlock" data-value="blockquote">Quote</button>
                                    <button type="button" data-action="link">Link</button>
                                    <button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="add-content_id" class="form-control d-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="simpanFacility()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFacilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Facility</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <input type="hidden" id="edit-id">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="edit-campus_id" class="form-label">Campus</label>
                            <select class="form-select" id="edit-campus_id">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img id="edit-img-preview" src="" alt="Facility image" class="img-thumbnail" style="max-height: 120px; display: none;">
                                <p class="text-muted mb-0" id="edit-img-empty">No image uploaded.</p>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-img" class="form-label">Change Image</label>
                            <input type="file" class="form-control" id="edit-img" accept="image/*">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-title" class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="edit-title" placeholder="Enter title in English">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-title_id" class="form-label">Title (ID)</label>
                            <input type="text" class="form-control" id="edit-title_id" placeholder="Enter title in Indonesian">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-content" class="form-label">Content (EN)</label>
                            <div class="rich-editor" data-editor-target="edit-content">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="formatBlock" data-value="h2">H2</button>
                                    <button type="button" data-command="bold"><b>B</b></button>
                                    <button type="button" data-command="italic"><i>I</i></button>
                                    <button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button>
                                    <button type="button" data-command="insertOrderedList">Number</button>
                                    <button type="button" data-command="formatBlock" data-value="blockquote">Quote</button>
                                    <button type="button" data-action="link">Link</button>
                                    <button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="edit-content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit-content_id" class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="edit-content_id">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="formatBlock" data-value="h2">H2</button>
                                    <button type="button" data-command="bold"><b>B</b></button>
                                    <button type="button" data-command="italic"><i>I</i></button>
                                    <button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button>
                                    <button type="button" data-command="insertOrderedList">Number</button>
                                    <button type="button" data-command="formatBlock" data-value="blockquote">Quote</button>
                                    <button type="button" data-action="link">Link</button>
                                    <button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="edit-content_id" class="form-control d-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updateFacility()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteFacilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Facility</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this facility?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeleteFacility()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const editorStore = {};

        function imageUrl(filename) {
            if (!filename) return '';
            return `/images/facility/${filename}`;
        }

        function initRichEditors() {
            const editors = document.querySelectorAll('.rich-editor');

            editors.forEach((editorContainer) => {
                const target = editorContainer.getAttribute('data-editor-target');
                const textarea = document.getElementById(target);
                const content = editorContainer.querySelector('.rich-editor__content');
                const buttons = editorContainer.querySelectorAll('.rich-editor__toolbar button');

                if (!textarea || !content) {
                    return;
                }

                const sync = function () {
                    textarea.value = content.innerHTML.trim();
                };

                content.innerHTML = textarea.value || '';

                content.addEventListener('input', sync);
                content.addEventListener('blur', sync);

                buttons.forEach((button) => {
                    button.addEventListener('click', function () {
                        content.focus();
                        const command = this.getAttribute('data-command');
                        const value = this.getAttribute('data-value');
                        const action = this.getAttribute('data-action');

                        if (action === 'link') {
                            const url = prompt('Masukkan URL link:');
                            if (url) {
                                document.execCommand('createLink', false, url);
                            }
                        } else if (command) {
                            document.execCommand(command, false, value || null);
                        }

                        sync();
                    });
                });

                editorStore[target] = {
                    setValue: function (html) {
                        content.innerHTML = html || '';
                        sync();
                    },
                    clear: function () {
                        content.innerHTML = '';
                        sync();
                    }
                };

                sync();
            });
        }

        function setEditorValue(target, html) {
            if (editorStore[target]) {
                editorStore[target].setValue(html);
                return;
            }

            const textarea = document.getElementById(target);
            if (textarea) {
                textarea.value = html || '';
            }
        }

        function clearEditorValue(target) {
            if (editorStore[target]) {
                editorStore[target].clear();
                return;
            }

            const textarea = document.getElementById(target);
            if (textarea) {
                textarea.value = '';
            }
        }

        function resetAddForm() {
            $('#add-campus_id').val('');
            $('#add-img').val('');
            $('#add-title').val('');
            $('#add-title_id').val('');
            clearEditorValue('add-content');
            clearEditorValue('add-content_id');
        }

        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => {
                    if (Array.isArray(arr)) {
                        arr.forEach((msg) => messages.push(msg));
                    }
                });
                if (messages.length) {
                    return messages.join('<br>');
                }
            }

            if (xhr.responseJSON && xhr.responseJSON.message) {
                return xhr.responseJSON.message;
            }

            return fallback;
        }

        initRichEditors();

        const facilityTable = $('#facility-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('facilities.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {
                    data: 'img',
                    name: 'img',
                    render: function (data) {
                        if (!data) return '-';
                        return `<img src="${imageUrl(data)}" alt="Facility" class="img-thumbnail" style="height: 52px; width: 78px; object-fit: cover;">`;
                    }
                },
                { data: 'title', name: 'title' },
                { data: 'campus.title', name: 'campus.title', defaultContent: '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function simpanFacility() {
            const formData = new FormData();
            const fileInput = document.getElementById('add-img');

            formData.append('campus_id', $('#add-campus_id').val());
            formData.append('title', $('#add-title').val());
            formData.append('title_id', $('#add-title_id').val());
            formData.append('content', $('#add-content').val());
            formData.append('content_id', $('#add-content_id').val());
            formData.append('_token', '{{ csrf_token() }}');

            if (fileInput.files[0]) {
                formData.append('img', fileInput.files[0]);
            }

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('facilities.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#facilityModal').modal('hide');
                    facilityTable.ajax.reload();
                    resetAddForm();
                    toastr.success(response.message || 'Facility created successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to save facility'));
                },
                complete: function () {
                    $('#button-save').prop('disabled', false).text('Save');
                }
            });
        }

        function editFacility(id) {
            $.ajax({
                url: `/school-facility/${id}`,
                method: 'GET',
                success: function (response) {
                    $('#edit-id').val(response.id);
                    $('#edit-campus_id').val(response.campus_id);
                    $('#edit-img').val('');
                    $('#edit-title').val(response.title);
                    $('#edit-title_id').val(response.title_id);
                    setEditorValue('edit-content', response.content);
                    setEditorValue('edit-content_id', response.content_id);

                    if (response.img) {
                        $('#edit-img-preview').attr('src', imageUrl(response.img)).show();
                        $('#edit-img-empty').hide();
                    } else {
                        $('#edit-img-preview').hide();
                        $('#edit-img-empty').show();
                    }

                    $('#editFacilityModal').modal('show');
                },
                error: function () {
                    toastr.error('Failed to fetch facility data');
                }
            });
        }

        function updateFacility() {
            const id = $('#edit-id').val();
            const formData = new FormData();
            const fileInput = document.getElementById('edit-img');

            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('campus_id', $('#edit-campus_id').val());
            formData.append('title', $('#edit-title').val());
            formData.append('title_id', $('#edit-title_id').val());
            formData.append('content', $('#edit-content').val());
            formData.append('content_id', $('#edit-content_id').val());

            if (fileInput.files[0]) {
                formData.append('img', fileInput.files[0]);
            }

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/school-facility/${id}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#editFacilityModal').modal('hide');
                    facilityTable.ajax.reload();
                    toastr.success(response.message || 'Facility updated successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to update facility'));
                },
                complete: function () {
                    $('#button-update').prop('disabled', false).text('Update');
                }
            });
        }

        function deleteFacility(id) {
            $('#delete-id').val(id);
            $('#deleteFacilityModal').modal('show');
        }

        function confirmDeleteFacility() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');

            $.ajax({
                url: `/school-facility/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#deleteFacilityModal').modal('hide');
                    facilityTable.ajax.reload();
                    toastr.success(response.message || 'Facility deleted successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to delete facility'));
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false).text('Delete');
                }
            });
        }
    </script>
@endsection
