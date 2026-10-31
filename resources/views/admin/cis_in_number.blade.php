@extends('layouts.layout')
@section('content')
    <style>
        #cis-number-table { table-layout: fixed; }
        #cis-number-table th, #cis-number-table td { vertical-align: middle; }
        .icon-preview { display:inline-flex; align-items:center; gap:6px; }
        .icon-preview i { font-size:18px; }
        .rich-editor { border: 1px solid #d9dee3; border-radius: .375rem; background: #fff; }
        .rich-editor__toolbar { display:flex; flex-wrap:wrap; gap:.35rem; padding:.6rem; border-bottom:1px solid #eceef1; background:#f8f9fb; }
        .rich-editor__toolbar button { border:1px solid #d9dee3; border-radius:.3rem; background:#fff; color:#566a7f; padding:.25rem .55rem; font-size:.8rem; }
        .rich-editor__content { min-height:140px; padding:.85rem; outline:none; overflow-y:auto; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bx bx-plus"></i> Add {{ $title }}</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="cis-number-table">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th style="width: 180px;">Icon</th>
                                <th style="width: 220px;">Title</th>
                                <th style="width: 140px;">Number</th>
                                <th style="width: 360px;">Content</th>
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
                    <h5 class="modal-title">Add CIS In Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" id="add-icon" placeholder="bx bxs-school">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number</label>
                            <input type="text" class="form-control" id="add-number" placeholder="1200+">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="add-title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (ID)</label>
                            <input type="text" class="form-control" id="add-title_id">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Content</label>
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
                            <textarea class="form-control d-none" id="add-content"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-save" class="btn btn-primary" onclick="saveCisNumber()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit CIS In Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" id="edit-icon">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Number</label>
                            <input type="text" class="form-control" id="edit-number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="edit-title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title (ID)</label>
                            <input type="text" class="form-control" id="edit-title_id">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Content</label>
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
                            <textarea class="form-control d-none" id="edit-content"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-update" class="btn btn-primary" onclick="updateCisNumber()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete CIS In Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this item?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="button-delete" class="btn btn-danger" onclick="confirmDeleteCisNumber()">Delete</button>
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
            $('#add-icon, #add-number, #add-title, #add-title_id, #add-content').val('');
            clearEditorValue('add-content');
        }

        const table = $('#cis-number-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('cis-in-number.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'icon', name: 'icon', render: (d) => d ? `<span class="icon-preview"><i class="${escapeHtml(d)}"></i>${escapeHtml(d)}</span>` : '-' },
                { data: 'title', name: 'title' },
                { data: 'number', name: 'number' },
                { data: 'content', name: 'content', render: (d) => d ? (d.length > 80 ? `${d.substring(0, 77)}...` : d) : '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function saveCisNumber() {
            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('cis-in-number.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    icon: $('#add-icon').val(),
                    number: $('#add-number').val(),
                    title: $('#add-title').val(),
                    title_id: $('#add-title_id').val(),
                    content: $('#add-content').val()
                },
                success: function (res) {
                    $('#addModal').modal('hide');
                    clearAddForm();
                    table.ajax.reload();
                    toastr.success(res.message || 'CIS In Number created successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan data')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editCisNumber(id) {
            $.ajax({
                url: `/cis-in-number/${id}`,
                method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-icon').val(r.icon);
                    $('#edit-number').val(r.number);
                    $('#edit-title').val(r.title);
                    $('#edit-title_id').val(r.title_id);
                    setEditorValue('edit-content', r.content);
                    $('#editModal').modal('show');
                },
                error: function () { toastr.error('Gagal mengambil data'); }
            });
        }

        function updateCisNumber() {
            const id = $('#edit-id').val();
            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/cis-in-number/${id}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    icon: $('#edit-icon').val(),
                    number: $('#edit-number').val(),
                    title: $('#edit-title').val(),
                    title_id: $('#edit-title_id').val(),
                    content: $('#edit-content').val()
                },
                success: function (res) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'CIS In Number updated successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal update data')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteCisNumber(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDeleteCisNumber() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/cis-in-number/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message || 'CIS In Number deleted successfully');
                },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Gagal hapus data')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Delete'); }
            });
        }

        initRichEditors();
    </script>
@endsection
