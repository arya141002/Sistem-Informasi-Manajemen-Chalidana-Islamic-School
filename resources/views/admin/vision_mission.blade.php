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

        .readmore-text {
            white-space: normal;
            word-break: break-word;
        }

        .readmore-toggle {
            border: 0;
            background: transparent;
            color: #696cff;
            padding: 0;
            margin-top: 0.25rem;
            text-decoration: underline;
            font-size: 0.825rem;
            cursor: pointer;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#visionMissionModal"><i class="bx bx-plus"></i> Add Vision/Mission</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="vision-mission-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Title (ID)</th>
                                <th>Content</th>
                                <th>Content (ID)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="visionMissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Vision/Mission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="add-title" class="form-label">Title (EN)</label>
                            <input type="text" id="add-title" class="form-control" placeholder="Enter title in English" />
                        </div>
                        <div class="col-12 mb-4">
                            <label for="add-title_id" class="form-label">Title (ID)</label>
                            <input type="text" id="add-title_id" class="form-control" placeholder="Enter title in Indonesian" />
                        </div>
                        <div class="col-12 mb-4">
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
                        <div class="col-12 mb-4">
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
                    <button type="button" class="btn btn-primary" id="button-save" onclick="simpanVisionMission()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVisionMissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Vision/Mission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <input type="hidden" id="edit-id" />
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="edit-title" class="form-label">Title (EN)</label>
                            <input type="text" id="edit-title" class="form-control" placeholder="Enter title in English" />
                        </div>
                        <div class="col-12 mb-4">
                            <label for="edit-title_id" class="form-label">Title (ID)</label>
                            <input type="text" id="edit-title_id" class="form-control" placeholder="Enter title in Indonesian" />
                        </div>
                        <div class="col-12 mb-4">
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
                        <div class="col-12 mb-4">
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
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updateVisionMission()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteVisionMissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Vision/Mission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this data?</p>
                    <input type="hidden" id="delete-id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeleteVisionMission()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const READMORE_LIMIT = 140;
        const editorStore = {};

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

        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }

        function stripHtml(value) {
            return $('<div>').html(value || '').text().trim();
        }

        function renderReadMoreCell(value) {
            const plainText = stripHtml(value);
            const escapedFull = escapeHtml(plainText);

            if (plainText.length <= READMORE_LIMIT) {
                return '<div class="readmore-text">' + escapedFull + '</div>';
            }

            const shortText = escapeHtml(plainText.slice(0, READMORE_LIMIT)) + '...';
            return `
                <div class="readmore-text" data-state="collapsed">
                    <span class="readmore-short">${shortText}</span>
                    <span class="readmore-full d-none">${escapedFull}</span>
                    <button type="button" class="readmore-toggle" data-action="toggle-readmore">Read more</button>
                </div>
            `;
        }

        initRichEditors();

        let visionMissionTable = $('#vision-mission-table').DataTable({
            ajax: '{{ route('vision-mission.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'title', name: 'title' },
                { data: 'title_id', name: 'title_id' },
                {
                    data: 'content',
                    name: 'content',
                    render: function (data) {
                        return renderReadMoreCell(data);
                    }
                },
                {
                    data: 'content_id',
                    name: 'content_id',
                    render: function (data) {
                        return renderReadMoreCell(data);
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $('#vision-mission-table').on('click', '[data-action="toggle-readmore"]', function () {
            const wrapper = $(this).closest('.readmore-text');
            const shortEl = wrapper.find('.readmore-short');
            const fullEl = wrapper.find('.readmore-full');
            const isCollapsed = wrapper.attr('data-state') === 'collapsed';

            if (isCollapsed) {
                shortEl.addClass('d-none');
                fullEl.removeClass('d-none');
                wrapper.attr('data-state', 'expanded');
                $(this).text('Read less');
            } else {
                fullEl.addClass('d-none');
                shortEl.removeClass('d-none');
                wrapper.attr('data-state', 'collapsed');
                $(this).text('Read more');
            }
        });

        function simpanVisionMission() {
            const title = $('#add-title').val();
            const title_id = $('#add-title_id').val();
            const content = $('#add-content').val();
            const content_id = $('#add-content_id').val();

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('vision-mission.store') }}',
                method: 'POST',
                data: {
                    title: title,
                    title_id: title_id,
                    content: content,
                    content_id: content_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#visionMissionModal').modal('hide');
                    visionMissionTable.ajax.reload();
                    $('#button-save').prop('disabled', false).text('Save');
                    $('#add-title').val('');
                    $('#add-title_id').val('');
                    clearEditorValue('add-content');
                    clearEditorValue('add-content_id');
                    toastr.success(response.message || 'Vision/Mission added successfully');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to add Vision/Mission';
                    toastr.error(errorMsg);
                    $('#button-save').prop('disabled', false).text('Save');
                }
            });
        }

        function editVisionMission(id) {
            $.ajax({
                url: `/vision-mission/${id}`,
                method: 'GET',
                success: function (response) {
                    $('#edit-id').val(response.id);
                    $('#edit-title').val(response.title);
                    $('#edit-title_id').val(response.title_id);
                    setEditorValue('edit-content', response.content);
                    setEditorValue('edit-content_id', response.content_id);
                    $('#editVisionMissionModal').modal('show');
                },
                error: function () {
                    toastr.error('Failed to fetch Vision/Mission data');
                }
            });
        }

        function updateVisionMission() {
            const id = $('#edit-id').val();
            const title = $('#edit-title').val();
            const title_id = $('#edit-title_id').val();
            const content = $('#edit-content').val();
            const content_id = $('#edit-content_id').val();

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/vision-mission/${id}`,
                method: 'PUT',
                data: {
                    title: title,
                    title_id: title_id,
                    content: content,
                    content_id: content_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#editVisionMissionModal').modal('hide');
                    visionMissionTable.ajax.reload();
                    $('#button-update').prop('disabled', false).text('Update');
                    toastr.success(response.message || 'Vision/Mission updated successfully');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to update Vision/Mission';
                    toastr.error(errorMsg);
                    $('#button-update').prop('disabled', false).text('Update');
                }
            });
        }

        function deleteVisionMission(id) {
            $('#delete-id').val(id);
            $('#deleteVisionMissionModal').modal('show');
        }

        function confirmDeleteVisionMission() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');

            $.ajax({
                url: `/vision-mission/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#deleteVisionMissionModal').modal('hide');
                    visionMissionTable.ajax.reload();
                    toastr.success(response.message || 'Vision/Mission deleted successfully');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to delete Vision/Mission';
                    toastr.error(errorMsg);
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false).text('Delete');
                }
            });
        }
    </script>
@endsection
