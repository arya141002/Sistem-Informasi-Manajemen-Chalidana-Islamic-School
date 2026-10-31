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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#campusModal"><i class="bx bx-plus"></i> Add Campus</button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table" id="campus-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Header Image</th>
                                <th>Principal Image</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="campusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add Campus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Campus Area</label>
                            <select class="form-select" id="add-catcampus_id">
                                <option value="">Select Campus Area</option>
                                @foreach($campusAreas as $area)
                                    <option value="{{ $area->id }}">{{ $area->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label class="form-label">Title</label><input type="text" class="form-control" id="add-title"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">NPSN</label><input type="text" class="form-control" id="add-NPSN"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">School Tour</label><input type="text" class="form-control" id="add-school_tour"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Subject</label><input type="text" class="form-control" id="add-subject"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Slug</label><input type="text" class="form-control" id="add-slug"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Header Image</label><input type="file" class="form-control" id="add-img_header" accept="image/*"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Principal Image</label><input type="file" class="form-control" id="add-img_principal" accept="image/*"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">From (EN)</label><input type="text" class="form-control" id="add-from"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">From (ID)</label><input type="text" class="form-control" id="add-from_id"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Address</label><textarea class="form-control" id="add-address" rows="3"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Map Location</label><textarea class="form-control" id="add-maplocation" rows="3"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Message (EN)</label><textarea class="form-control" id="add-message" rows="4"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Message (ID)</label><textarea class="form-control" id="add-message_id" rows="4"></textarea></div>
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
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
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="button-save" onclick="simpanCampus()">Save</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCampusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit Campus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3 py-md-4">
                    <input type="hidden" id="edit-id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Campus Area</label>
                            <select class="form-select" id="edit-catcampus_id">
                                <option value="">Select Campus Area</option>
                                @foreach($campusAreas as $area)
                                    <option value="{{ $area->id }}">{{ $area->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label class="form-label">Title</label><input type="text" class="form-control" id="edit-title"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">NPSN</label><input type="text" class="form-control" id="edit-NPSN"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">School Tour</label><input type="text" class="form-control" id="edit-school_tour"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Subject</label><input type="text" class="form-control" id="edit-subject"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Slug</label><input type="text" class="form-control" id="edit-slug"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Current Header Image</label><div><img id="edit-header-preview" src="" class="img-thumbnail" style="max-height:120px;display:none;"><p class="text-muted mb-0" id="edit-header-empty">No image uploaded.</p></div></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Current Principal Image</label><div><img id="edit-principal-preview" src="" class="img-thumbnail" style="max-height:120px;display:none;"><p class="text-muted mb-0" id="edit-principal-empty">No image uploaded.</p></div></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Change Header Image</label><input type="file" class="form-control" id="edit-img_header" accept="image/*"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Change Principal Image</label><input type="file" class="form-control" id="edit-img_principal" accept="image/*"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">From (EN)</label><input type="text" class="form-control" id="edit-from"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">From (ID)</label><input type="text" class="form-control" id="edit-from_id"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Address</label><textarea class="form-control" id="edit-address" rows="3"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Map Location</label><textarea class="form-control" id="edit-maplocation" rows="3"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Message (EN)</label><textarea class="form-control" id="edit-message" rows="4"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Message (ID)</label><textarea class="form-control" id="edit-message_id" rows="4"></textarea></div>
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
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
                <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="button-update" onclick="updateCampus()">Update</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCampusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Delete Campus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p class="mb-0">Are you sure want to delete this campus?</p><input type="hidden" id="delete-id"></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDeleteCampus()">Delete</button></div></div></div>
    </div>

    <script>
        const editorStore = {};
        function imageUrl(name) { return name ? `/public/images/campuses/${name}` : ''; }

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
                        const cmd = this.getAttribute('data-command');
                        const val = this.getAttribute('data-value');
                        const action = this.getAttribute('data-action');
                        if (action === 'link') {
                            const url = prompt('Masukkan URL link:');
                            if (url) document.execCommand('createLink', false, url);
                        } else if (cmd) {
                            document.execCommand(cmd, false, val || null);
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
                Object.values(xhr.responseJSON.errors).forEach((arr) => { if (Array.isArray(arr)) arr.forEach((m) => messages.push(m)); });
                if (messages.length) return messages.join('<br>');
            }
            if (xhr.responseJSON && xhr.responseJSON.message) return xhr.responseJSON.message;
            return fallback;
        }

        function resetAddForm() {
            $('#add-catcampus_id, #add-title, #add-NPSN, #add-school_tour, #add-subject, #add-slug, #add-from, #add-from_id, #add-address, #add-maplocation, #add-message, #add-message_id').val('');
            $('#add-img_header, #add-img_principal').val('');
            clearEditorValue('add-content');
            clearEditorValue('add-content_id');
        }

        initRichEditors();

        const campusTable = $('#campus-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('campus.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'img_header', name: 'img_header', render: (d) => d ? `<img src="${imageUrl(d)}" class="img-thumbnail" style="height:52px;width:78px;object-fit:cover;">` : '-' },
                { data: 'img_principal', name: 'img_principal', render: (d) => d ? `<img src="${imageUrl(d)}" class="img-thumbnail" style="height:52px;width:78px;object-fit:cover;">` : '-' },
                { data: 'title', name: 'title' },
                { data: 'slug', name: 'slug' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        function simpanCampus() {
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('catcampus_id', $('#add-catcampus_id').val());
            fd.append('title', $('#add-title').val());
            fd.append('NPSN', $('#add-NPSN').val());
            fd.append('school_tour', $('#add-school_tour').val());
            fd.append('subject', $('#add-subject').val());
            fd.append('slug', $('#add-slug').val());
            fd.append('from', $('#add-from').val());
            fd.append('from_id', $('#add-from_id').val());
            fd.append('message', $('#add-message').val());
            fd.append('message_id', $('#add-message_id').val());
            fd.append('address', $('#add-address').val());
            fd.append('maplocation', $('#add-maplocation').val());
            fd.append('content', $('#add-content').val());
            fd.append('content_id', $('#add-content_id').val());
            const h = document.getElementById('add-img_header').files[0];
            const p = document.getElementById('add-img_principal').files[0];
            if (h) fd.append('img_header', h);
            if (p) fd.append('img_principal', p);

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('campus.store') }}', method: 'POST', data: fd, processData: false, contentType: false,
                success: function (res) { $('#campusModal').modal('hide'); campusTable.ajax.reload(); resetAddForm(); toastr.success(res.message || 'Campus created successfully'); },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Failed to save campus')); },
                complete: function () { $('#button-save').prop('disabled', false).text('Save'); }
            });
        }

        function editCampus(id) {
            $.ajax({
                url: `/our-campus/${id}`, method: 'GET',
                success: function (r) {
                    $('#edit-id').val(r.id);
                    $('#edit-catcampus_id').val(r.catcampus_id);
                    $('#edit-title').val(r.title);
                    $('#edit-NPSN').val(r.NPSN);
                    $('#edit-school_tour').val(r.school_tour);
                    $('#edit-subject').val(r.subject);
                    $('#edit-slug').val(r.slug);
                    $('#edit-from').val(r.from);
                    $('#edit-from_id').val(r.from_id);
                    $('#edit-address').val(r.address);
                    $('#edit-maplocation').val(r.maplocation);
                    $('#edit-message').val(r.message);
                    $('#edit-message_id').val(r.message_id);
                    setEditorValue('edit-content', r.content);
                    setEditorValue('edit-content_id', r.content_id);
                    $('#edit-img_header, #edit-img_principal').val('');

                    if (r.img_header) { $('#edit-header-preview').attr('src', imageUrl(r.img_header)).show(); $('#edit-header-empty').hide(); }
                    else { $('#edit-header-preview').hide(); $('#edit-header-empty').show(); }
                    if (r.img_principal) { $('#edit-principal-preview').attr('src', imageUrl(r.img_principal)).show(); $('#edit-principal-empty').hide(); }
                    else { $('#edit-principal-preview').hide(); $('#edit-principal-empty').show(); }

                    $('#editCampusModal').modal('show');
                },
                error: function () { toastr.error('Failed to fetch campus data'); }
            });
        }

        function updateCampus() {
            const id = $('#edit-id').val();
            const fd = new FormData();
            fd.append('_method', 'PUT');
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('catcampus_id', $('#edit-catcampus_id').val());
            fd.append('title', $('#edit-title').val());
            fd.append('NPSN', $('#edit-NPSN').val());
            fd.append('school_tour', $('#edit-school_tour').val());
            fd.append('subject', $('#edit-subject').val());
            fd.append('slug', $('#edit-slug').val());
            fd.append('from', $('#edit-from').val());
            fd.append('from_id', $('#edit-from_id').val());
            fd.append('message', $('#edit-message').val());
            fd.append('message_id', $('#edit-message_id').val());
            fd.append('address', $('#edit-address').val());
            fd.append('maplocation', $('#edit-maplocation').val());
            fd.append('content', $('#edit-content').val());
            fd.append('content_id', $('#edit-content_id').val());
            const h = document.getElementById('edit-img_header').files[0];
            const p = document.getElementById('edit-img_principal').files[0];
            if (h) fd.append('img_header', h);
            if (p) fd.append('img_principal', p);

            $('#button-update').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/our-campus/${id}`, method: 'POST', data: fd, processData: false, contentType: false,
                success: function (res) { $('#editCampusModal').modal('hide'); campusTable.ajax.reload(); toastr.success(res.message || 'Campus updated successfully'); },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Failed to update campus')); },
                complete: function () { $('#button-update').prop('disabled', false).text('Update'); }
            });
        }

        function deleteCampus(id) { $('#delete-id').val(id); $('#deleteCampusModal').modal('show'); }

        function confirmDeleteCampus() {
            const id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            $.ajax({
                url: `/our-campus/${id}`, method: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                success: function (res) { $('#deleteCampusModal').modal('hide'); campusTable.ajax.reload(); toastr.success(res.message || 'Campus deleted successfully'); },
                error: function (xhr) { toastr.error(formatValidationErrors(xhr, 'Failed to delete campus')); },
                complete: function () { $('#button-delete').prop('disabled', false).text('Delete'); }
            });
        }
    </script>
@endsection
