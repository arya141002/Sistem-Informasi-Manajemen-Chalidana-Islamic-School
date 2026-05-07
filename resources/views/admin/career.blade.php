@extends('layouts.layout')
@section('content')
    <style>
        #career-table { table-layout: fixed; }
        #career-table th, #career-table td { vertical-align: middle; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .rich-editor { border: 1px solid #d9dee3; border-radius: .375rem; background: #fff; }
        .rich-editor__toolbar { display:flex; flex-wrap:wrap; gap:.35rem; padding:.6rem; border-bottom:1px solid #eceef1; background:#f8f9fb; }
        .rich-editor__toolbar button { border:1px solid #d9dee3; border-radius:.3rem; background:#fff; color:#566a7f; padding:.25rem .55rem; font-size:.8rem; }
        .rich-editor__content { min-height:160px; padding:.85rem; outline:none; overflow-y:auto; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add {{ $title }}</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="career-table">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th style="width: 140px;">Date</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 220px;">Title (EN)</th>
                                <th style="width: 220px;">Title (ID)</th>
                                <th style="width: 360px;">Content (EN)</th>
                                <th style="width: 360px;">Content (ID)</th>
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
                    <h5 class="modal-title">Add Career</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="add-tanggal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="add-status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                            <textarea id="add-content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="add-content_id">
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
                            <textarea id="add-content_id" class="form-control d-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveCareer()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Career</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit-tanggal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit-status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                            <textarea id="edit-content" class="form-control d-none"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content (ID)</label>
                            <div class="rich-editor" data-editor-target="edit-content_id">
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
                            <textarea id="edit-content_id" class="form-control d-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateCareer()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Career</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this item?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteCareer()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function escapeHtml(value) { return $('<div>').text(value || '').html(); }
        function formatValidationErrors(xhr, fallback) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const messages = [];
                Object.values(xhr.responseJSON.errors).forEach((arr) => { if (Array.isArray(arr)) arr.forEach((msg) => messages.push(msg)); });
                if (messages.length) return messages.join('<br>');
            }
            if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
            return fallback;
        }

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
                editorStore[target] = { setValue: (v) => { content.innerHTML = v || ''; sync(); }, clear: () => { content.innerHTML = ''; sync(); } };
                sync();
            });
        }
        function setEditorValue(target, html) { if (editorStore[target]) editorStore[target].setValue(html); }
        function clearEditorValue(target) { if (editorStore[target]) editorStore[target].clear(); }

        function clearAddForm() {
            $('#add-tanggal, #add-title, #add-title_id').val('');
            $('#add-status').val('1');
            clearEditorValue('add-content');
            clearEditorValue('add-content_id');
        }

        const table = $('#career-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('career.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tanggal', name: 'tanggal', render: (d) => d ? d.substring(0, 10) : '-' },
                { data: 'career_status', name: 'career_status', render: (d) => String(d) === '1' ? 'Active' : 'Inactive' },
                { data: 'title', name: 'title' },
                { data: 'title_id', name: 'title_id' },
                { data: 'content', name: 'content', render: (d) => d ? (d.length > 80 ? `${d.substring(0, 77)}...` : d) : '-' },
                { data: 'content_id', name: 'content_id', render: (d) => d ? (d.length > 80 ? `${d.substring(0, 77)}...` : d) : '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function saveCareer() {
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('career.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal: $('#add-tanggal').val(),
                    career_status: $('#add-status').val(),
                    title: $('#add-title').val(),
                    title_id: $('#add-title_id').val(),
                    content: $('#add-content').val(),
                    content_id: $('#add-content_id').val()
                },
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(res.message || 'Career created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan data')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editCareer(id) {
            $.ajax({
                url: `/admin/career/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-tanggal').val(r.tanggal ? r.tanggal.substring(0, 10) : '');
                    $('#edit-status').val(String(r.career_status || 0));
                    $('#edit-title').val(r.title);
                    $('#edit-title_id').val(r.title_id);
                    setEditorValue('edit-content', r.content);
                    setEditorValue('edit-content_id', r.content_id);
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data'); }
            });
        }

        function updateCareer() {
            const id = $('#edit-id').val();
            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/admin/career/${id}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    tanggal: $('#edit-tanggal').val(),
                    career_status: $('#edit-status').val(),
                    title: $('#edit-title').val(),
                    title_id: $('#edit-title_id').val(),
                    content: $('#edit-content').val(),
                    content_id: $('#edit-content_id').val()
                },
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Career updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update data')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteCareer(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteCareer() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/admin/career/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'Career deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus data')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Delete'); }
            });
        }

        initRichEditors();
    </script>
@endsection
