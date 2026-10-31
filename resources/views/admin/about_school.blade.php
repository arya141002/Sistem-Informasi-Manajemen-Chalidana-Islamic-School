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
        <!-- Bootstrap Table with Caption -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#aboutModal"><i class="bx bx-plus"></i> Add About School</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="menu-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Title ID</th>
                                <th>Content</th>
                                <th>Content ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add About School</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="title" class="form-label">Title (EN)</label>
                            <input type="text" id="title" class="form-control" placeholder="Enter title in English" />
                        </div>
                        <div class="col-12 mb-4">
                            <label for="title_id" class="form-label">Title (ID)</label>
                            <input type="text" id="title_id" class="form-control" placeholder="Enter title in Indonesian" />
                        </div>
                        <div class="col-12 mb-4">
                            <label for="content" class="form-label">Content (EN)</label>
                            <div class="rich-editor" data-editor-target="content">
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
                            <textarea id="content" class="form-control d-none" rows="4" placeholder="Enter content in English"></textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <label for="content_id" class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="content_id">
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
                            <textarea id="content_id" class="form-control d-none" rows="4" placeholder="Enter content in Indonesian"></textarea>
                        </div>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="simpanAbout()">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editAboutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit About School</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
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
                            <textarea id="edit-content" class="form-control d-none" rows="4" placeholder="Enter content in English"></textarea>
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
                            <textarea id="edit-content_id" class="form-control d-none" rows="4" placeholder="Enter content in Indonesian"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updateAbout()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteAboutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Delete About School</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <p>Are you sure you want to delete this about school?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeleteAbout()">Delete</button>
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

        let aboutTable = $('#menu-table').DataTable({
            ajax: "{{ route('about-school.data') }}",
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

        $('#menu-table').on('click', '[data-action="toggle-readmore"]', function () {
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

        function simpanAbout() {
            let title = $('#title').val();
            let title_id = $('#title_id').val();
            let content = $('#content').val();
            let content_id = $('#content_id').val();
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: "{{ route('about-school.store') }}",
                method: 'POST',
                data: {
                    title: title,
                    title_id: title_id,
                    content: content,
                    content_id: content_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#aboutModal').modal('hide');
                    aboutTable.ajax.reload();
                    $('#button-save').prop('disabled', false).text('Save');
                    $('#title').val('');
                    $('#title_id').val('');
                    clearEditorValue('content');
                    clearEditorValue('content_id');
                    toastr.success(response.message || 'About School added successfully');
                },
                error: function (xhr) {
                    let errorMsg = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                    $('#button-save').prop('disabled', false).text('Save');
                }
            });
        }

        function editAbout(id) {
            $.ajax({
                url: `/about-school/${id}`,
                method: 'GET',
                success: function (response) {
                    $('#title').val(response.title);
                    $('#title_id').val(response.title_id);
                    setEditorValue('content', response.content);
                    setEditorValue('content_id', response.content_id);
                    $('#button-save').off('click').on('click', function () {
                        updateAbout(id);
                    }).text('Update');
                    $('#aboutModal').modal('show');
                },
                error: function () {
                    toastr.error('Failed to fetch about school data');
                }
            });
        }

        function updateAbout(id) {
            let title = $('#title').val();
            let title_id = $('#title_id').val();
            let content = $('#content').val();
            let content_id = $('#content_id').val();
            $('#button-save').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/about-school/${id}`,
                method: 'PUT',
                data: {
                    title: title,
                    title_id: title_id,
                    content: content,
                    content_id: content_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#aboutModal').modal('hide');
                    aboutTable.ajax.reload();
                    $('#button-save').prop('disabled', false).text('Save');
                    $('#title').val('');
                    $('#title_id').val('');
                    clearEditorValue('content');
                    clearEditorValue('content_id');
                    toastr.success(response.message || 'About School updated successfully');
                },
                error: function (xhr) {
                    let errorMsg = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                    $('#button-save').prop('disabled', false).text('Save');
                }
            });
        }

        let deleteId = null;
        function deleteAbout(id) {
            deleteId = id;
            $('#deleteAboutModal').modal('show');
        }
        function confirmDeleteAbout() {
            if (!deleteId) return;
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/about-school/${deleteId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#deleteAboutModal').modal('hide');
                    aboutTable.ajax.reload();
                    $('#button-delete').prop('disabled', false).text('Delete');
                    toastr.success(response.message || 'About School deleted successfully');
                    deleteId = null;
                },
                error: function (xhr) {
                    let errorMsg = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                    $('#button-delete').prop('disabled', false).text('Delete');
                }
            });
        }
    </script>
@endsection
