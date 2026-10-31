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
        <style>
            #events-table { table-layout: fixed; }
            #events-table th, #events-table td { vertical-align: middle; }
        </style>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add Event</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="events-table">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th style="width: 84px;">Image</th>
                                <th style="width: 110px;">Date</th>
                                <th>Title</th>
                                <th style="width: 96px;">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Study</label>
                            <select class="form-select" id="add-study_id">
                                <option value="">Select Study</option>
                                @foreach($studies as $study)
                                    <option value="{{ $study->id }}">{{ $study->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="add-tanggal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" id="add-img" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="add-title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (ID)</label>
                            <input type="text" class="form-control" id="add-title_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content (EN)</label>
                            <div class="rich-editor" data-editor-target="add-content">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="bold"><b>B</b></button><button type="button" data-command="italic"><i>I</i></button><button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button><button type="button" data-command="insertOrderedList">Number</button><button type="button" data-action="link">Link</button><button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="add-content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="add-content_id">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="bold"><b>B</b></button><button type="button" data-command="italic"><i>I</i></button><button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button><button type="button" data-command="insertOrderedList">Number</button><button type="button" data-action="link">Link</button><button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="add-content_id" class="form-control d-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveEvent()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Study</label>
                            <select class="form-select" id="edit-study_id">
                                <option value="">Select Study</option>
                                @foreach($studies as $study)
                                    <option value="{{ $study->id }}">{{ $study->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit-tanggal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Change Image</label>
                            <input type="file" class="form-control" id="edit-img" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="edit-title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (ID)</label>
                            <input type="text" class="form-control" id="edit-title_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content (EN)</label>
                            <div class="rich-editor" data-editor-target="edit-content">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="bold"><b>B</b></button><button type="button" data-command="italic"><i>I</i></button><button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button><button type="button" data-command="insertOrderedList">Number</button><button type="button" data-action="link">Link</button><button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="edit-content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="edit-content_id">
                                <div class="rich-editor__toolbar">
                                    <button type="button" data-command="bold"><b>B</b></button><button type="button" data-command="italic"><i>I</i></button><button type="button" data-command="underline"><u>U</u></button>
                                    <button type="button" data-command="insertUnorderedList">Bullet</button><button type="button" data-command="insertOrderedList">Number</button><button type="button" data-action="link">Link</button><button type="button" data-command="removeFormat">Clear</button>
                                </div>
                                <div class="rich-editor__content" contenteditable="true"></div>
                            </div>
                            <textarea id="edit-content_id" class="form-control d-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateEvent()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Yakin ingin menghapus data ini?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteEvent()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const editorStore = {};
        function imageUrl(path) { return path ? `/public/images/events/${path}` : ''; }

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
                editorStore[target] = { setValue: (v) => { content.innerHTML = v || ''; sync(); }, clear: () => { content.innerHTML = ''; sync(); } };
                sync();
            });
        }
        function setEditorValue(target, html) { if (editorStore[target]) editorStore[target].setValue(html); }
        function clearEditorValue(target) { if (editorStore[target]) editorStore[target].clear(); }

        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => { if (Array.isArray(arr)) arr.forEach((msg) => messages.push(msg)); });
                if (messages.length) return messages.join('<br>');
            }
            if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
            return fallback;
        }

        function clearAddForm() {
            $('#add-study_id, #add-tanggal, #add-slug, #add-title, #add-title_id').val('');
            $('#add-img').val('');
            clearEditorValue('add-content');
            clearEditorValue('add-content_id');
        }

        initRichEditors();

        function makeSlug(text) {
            return (text || '')
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        $('#add-title').on('input', function () {
            $('#add-slug').val(makeSlug(this.value));
        });
        $('#edit-title').on('input', function () {
            $('#edit-slug').val(makeSlug(this.value));
        });

        const table = $('#events-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('events.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'img', name: 'img', render: (d) => d ? `<img src="${imageUrl(d)}" class="img-thumbnail" style="height:52px;width:78px;object-fit:cover;">` : '-' },
                { data: 'tanggal', name: 'tanggal', render: (d) => d ? d.substring(0, 10) : '-' },
                { data: 'title', name: 'title', render: (d) => d ? $('<div>').text(d).html() : '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function saveEvent() {
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('study_id', $('#add-study_id').val());
            fd.append('tanggal', $('#add-tanggal').val());
            fd.append('title', $('#add-title').val());
            fd.append('title_id', $('#add-title_id').val());
            fd.append('content', $('#add-content').val());
            fd.append('content_id', $('#add-content_id').val());
            const img = document.getElementById('add-img').files[0];
            if (img) fd.append('img', img);

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('events.store') }}',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(res.message || 'Event created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan data event')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editEvent(id) {
            $.ajax({
                url: `{{ url('/admin/events') }}/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-study_id').val(r.study_id);
                    $('#edit-tanggal').val(r.tanggal ? r.tanggal.substring(0, 10) : '');
                    $('#edit-title').val(r.title);
                    $('#edit-title_id').val(r.title_id);
                    setEditorValue('edit-content', r.content);
                    setEditorValue('edit-content_id', r.content_id);
                    $('#edit-img').val('');
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data event'); }
            });
        }

        function updateEvent() {
            const id = $('#edit-id').val();
            const fd = new FormData();
            fd.append('_method', 'PUT');
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('study_id', $('#edit-study_id').val());
            fd.append('tanggal', $('#edit-tanggal').val());
            fd.append('title', $('#edit-title').val());
            fd.append('title_id', $('#edit-title_id').val());
            fd.append('content', $('#edit-content').val());
            fd.append('content_id', $('#edit-content_id').val());
            const img = document.getElementById('edit-img').files[0];
            if (img) fd.append('img', img);

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `{{ url('/admin/events') }}/${id}`,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Event updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update data event')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteEvent(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteEvent() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `{{ url('/admin/events') }}/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Event deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus data event')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Hapus'); }
            });
        }
    </script>
@endsection
