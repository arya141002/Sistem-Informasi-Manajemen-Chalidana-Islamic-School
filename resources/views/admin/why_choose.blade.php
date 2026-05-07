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
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-home-circle"></i></span> {{ $title }}</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">{{ $title }}</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" value="{{ $whyChoose->title ?? '' }}" placeholder="Enter title"/>
                        </div>
                        <div class="mb-3">
                            <label for="title_id" class="form-label">Title ID</label>
                            <input type="text" class="form-control" id="title_id" value="{{ $whyChoose->title_id ?? '' }}" placeholder="Enter title ID"/>
                        </div>
                        <div>
                            <label for="content" class="form-label">Content</label>
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
                                <div class="rich-editor__content" contenteditable="true">{!! $whyChoose->content ?? '' !!}</div>
                            </div>
                            <textarea class="form-control d-none" id="content" rows="3">{{ $whyChoose->content ?? '' }}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="content_id" class="form-label">Content ID</label>
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
                                <div class="rich-editor__content" contenteditable="true">{!! $whyChoose->content_id ?? '' !!}</div>
                            </div>
                            <textarea class="form-control d-none" id="content_id" rows="3">{{ $whyChoose->content_id ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-primary" id="button-save" onclick="simpanchoose()" >Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editors = document.querySelectorAll('.rich-editor');
            let activeEditor = null;

            const syncToTextarea = (editorContainer) => {
                const target = editorContainer.getAttribute('data-editor-target');
                const textarea = document.getElementById(target);
                const content = editorContainer.querySelector('.rich-editor__content');

                if (textarea && content) {
                    textarea.value = content.innerHTML.trim();
                }
            };

            editors.forEach((editorContainer) => {
                const content = editorContainer.querySelector('.rich-editor__content');
                const buttons = editorContainer.querySelectorAll('.rich-editor__toolbar button');

                content.addEventListener('focus', function () {
                    activeEditor = content;
                });

                content.addEventListener('input', function () {
                    syncToTextarea(editorContainer);
                });

                buttons.forEach((button) => {
                    button.addEventListener('click', function () {
                        if (!activeEditor || !editorContainer.contains(activeEditor)) {
                            activeEditor = content;
                            activeEditor.focus();
                        }

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

                        syncToTextarea(editorContainer);
                    });
                });

                syncToTextarea(editorContainer);
            });
        });

        function simpanchoose() {
            const title = document.getElementById('title').value;
            const title_id = document.getElementById('title_id').value;
            const content = document.getElementById('content').value;
            const content_id = document.getElementById('content_id').value;
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('why-choose.update') }}',
                method: 'PUT',
                data: {
                    title,
                    title_id,
                    content,
                    content_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    toastr.success(response.message || 'Why Choose updated successfully!');
                    $('#button-save').prop('disabled', false).text('Save Changes');
                },
                error: function (xhr) {
                    const errorMsg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'Error updating Why Choose. Please try again.';
                    toastr.error(errorMsg);
                    $('#button-save').prop('disabled', false).text('Save Changes');
                },
                complete: function () {
                    $('#button-save').prop('disabled', false).text('Save Changes');
                }
            });
        }
    </script>
@endsection
