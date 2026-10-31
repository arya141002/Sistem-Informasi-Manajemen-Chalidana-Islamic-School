@extends('layouts.layout')
@section('content')
    <style>
        .rich-editor { border: 1px solid #d9dee3; border-radius: .375rem; background: #fff; }
        .rich-editor__toolbar { display:flex; flex-wrap:wrap; gap:.35rem; padding:.6rem; border-bottom:1px solid #eceef1; background:#f8f9fb; }
        .rich-editor__toolbar button { border:1px solid #d9dee3; border-radius:.3rem; background:#fff; color:#566a7f; padding:.25rem .55rem; font-size:.8rem; }
        .rich-editor__content { min-height:160px; padding:.85rem; outline:none; overflow-y:auto; }
        .rating-stars { display:inline-flex; gap:2px; align-items:center; }
        .rating-stars .bx { color:#f5b301; font-size:16px; }
        .rating-input { display:flex; gap:4px; }
        .rating-input input { position:absolute; left:-9999px; }
        .rating-input label { cursor:pointer; color:#d1d5db; font-size:22px; line-height:1; display:inline-flex; align-items:center; }
        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked + label,
        .rating-input input:checked + label ~ label { color:#f5b301; }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-blod py-3 mb-4">
            <span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span>{{ $title }}
        </h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header mb-0">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bx bx-plus"></i> Add {{ $title }}
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive admin-table-wrapper">
                    <table class="table align-middle" id="testimonial-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Photo</th>
                                <th>Parent Name / Rating</th>
                                <th>Student Name / Grade</th>
                                <th>Content</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add {{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="student" class="form-label">Student Name</label>
                            <input type="text" id="student" class="form-control" name="student" />
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Parent Name</label>
                            <input type="text" id="title" class="form-control" name="title" />
                        </div>
                        <div class="col-md-6">
                            <label for="grade_id" class="form-label">Grade</label>
                            <select id="grade_id" class="form-select" name="grade_id">
                                <option value="">Select grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="campus_id" class="form-label">Campus</label>
                            <select id="campus_id" class="form-select" name="campus_id">
                                <option value="">Select campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Rating</label>
                            <div class="rating-input" id="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}">
                                    <label for="rating-{{ $i }}"><i class="bx bxs-star"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="status" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
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
                            <textarea id="content" class="form-control d-none" name="content"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="images" class="form-label">Photo</label>
                            <input type="file" name="images" id="images" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="saveTestimonial()">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit {{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <input type="hidden" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_student" class="form-label">Student Name</label>
                            <input type="text" id="edit_student" class="form-control" name="edit_student" />
                        </div>
                        <div class="col-md-6">
                            <label for="edit_title" class="form-label">Parent Name</label>
                            <input type="text" id="edit_title" class="form-control" name="edit_title" />
                        </div>
                        <div class="col-md-6">
                            <label for="edit_grade_id" class="form-label">Grade</label>
                            <select id="edit_grade_id" class="form-select" name="edit_grade_id">
                                <option value="">Select grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_campus_id" class="form-label">Campus</label>
                            <select id="edit_campus_id" class="form-select" name="edit_campus_id">
                                <option value="">Select campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Rating</label>
                            <div class="rating-input" id="edit-rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="edit_rating" id="edit-rating-{{ $i }}" value="{{ $i }}">
                                    <label for="edit-rating-{{ $i }}"><i class="bx bxs-star"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="edit_status">
                                <label class="form-check-label" for="edit_status">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="edit-content" class="form-label">Content</label>
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
                            <textarea id="edit-content" class="form-control d-none" name="edit_content"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_images" class="form-label">Photo</label>
                            <input type="file" name="edit_images" id="edit_images" class="form-control" />
                            <small id="current-image" class="text-muted d-block mt-1"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-update" onclick="updateTestimonial()">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this testimonial?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="button-delete" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function imageUrl(filename) {
            if (!filename) return '';
            return `/public/images/testimonial/${filename}`;
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

        function renderStars(value) {
            const rating = parseInt(value || 0, 10);
            let html = '<span class="rating-stars">';
            for (let i = 1; i <= 5; i++) {
                html += `<i class="bx ${i <= rating ? 'bxs-star' : 'bx-star'}"></i>`;
            }
            html += '</span>';
            return html;
        }

        const columns = [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'images',
                name: 'images',
                render: function (data) {
                    if (!data) return '-';
                    return `<img src="${imageUrl(data)}" alt="Testimonial" class="img-thumbnail" style="height:52px;width:78px;object-fit:cover;">`;
                },
                orderable: false,
                searchable: false
            },
            {
                data: null,
                name: 'title',
                render: function (row) {
                    const name = row.title || '-';
                    const stars = renderStars(row.rating);
                    const ratingText = row.rating ? `<small class="text-muted ms-1">(${row.rating})</small>` : '';
                    return `<div>${name}</div><div>${stars}${ratingText}</div>`;
                }
            },
            {
                data: null,
                name: 'student',
                render: function (row) {
                    const student = row.student || '-';
                    const grade = row.grade_name || '-';
                    return `<div>${student}</div><small class="text-muted">${grade}</small>`;
                }
            },
            {
                data: 'content',
                name: 'content',
                render: function (data) {
                    if (!data) return '-';
                    return data.length > 80 ? data.substring(0, 77) + '...' : data;
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ];

        let testimonialTable = $('#testimonial-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('testimonial.data') }}',
            columns: columns,
        });

        function resetForm() {
            $('#student').val('');
            $('#title').val('');
            $('#grade_id').val('');
            $('#campus_id').val('');
            $('#rating-1').prop('checked', true);
            clearEditorValue('content');
            $('#images').val('');
            $('#status').prop('checked', true);
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

        function saveTestimonial() {
            let formData = new FormData();
            formData.append('student', $('#student').val());
            formData.append('title', $('#title').val());
            formData.append('grade_id', $('#grade_id').val());
            formData.append('campus_id', $('#campus_id').val());
            formData.append('rating', $('input[name="rating"]:checked').val() || 1);
            formData.append('content', $('#content').val());
            formData.append('status', $('#status').is(':checked') ? 1 : 0);
            formData.append('_token', '{{ csrf_token() }}');
            if ($('#images').prop('files')[0]) {
                formData.append('images', $('#images').prop('files')[0]);
            }

            $('#button-save').prop('disabled', true).text('Saving...');

            $.ajax({
                url: '{{ route('testimonial.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#createModal').modal('hide');
                    testimonialTable.ajax.reload(null, false);
                    resetForm();
                    toastr.success(response.message || 'Testimonial created successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to save testimonial'));
                },
                complete: function () {
                    $('#button-save').prop('disabled', false).text('Save');
                }
            });
        }

        function editTestimonial(id) {
            $.ajax({
                url: `/testimonial/${id}`,
                method: 'GET',
                success: function (response) {
                    $('#edit_id').val(id);
                    $('#edit_student').val(response.student);
                    $('#edit_title').val(response.title);
                    $('#edit_grade_id').val(response.grade_id);
                    $('#edit_campus_id').val(response.campus_id);
                    $(`#edit-rating-${response.rating || 1}`).prop('checked', true);
                    setEditorValue('edit-content', response.content);
                    $('#edit_status').prop('checked', !!response.status);
                    if (response.images) {
                        $('#current-image').html(`Current photo: <a href="${imageUrl(response.images)}" target="_blank">${response.images}</a>`);
                    } else {
                        $('#current-image').text('No photo uploaded.');
                    }
                    $('#editModal').modal('show');
                },
                error: function () {
                    toastr.error('Failed to fetch testimonial data');
                }
            });
        }

        function updateTestimonial() {
            let id = $('#edit_id').val();
            let url = "{{ route('testimonial.update', ':id') }}".replace(':id', id);

            let formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('student', $('#edit_student').val());
            formData.append('title', $('#edit_title').val());
            formData.append('grade_id', $('#edit_grade_id').val());
            formData.append('campus_id', $('#edit_campus_id').val());
            formData.append('rating', $('input[name="edit_rating"]:checked').val() || 1);
            formData.append('content', $('#edit-content').val());
            formData.append('status', $('#edit_status').is(':checked') ? 1 : 0);
            if ($('#edit_images').prop('files')[0]) {
                formData.append('images', $('#edit_images').prop('files')[0]);
            }

            $('#button-update').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#editModal').modal('hide');
                    testimonialTable.ajax.reload(null, false);
                    toastr.success(response.message || 'Testimonial updated successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to update testimonial'));
                },
                complete: function () {
                    $('#button-update').prop('disabled', false).text('Save');
                }
            });
        }

        function deleteTestimonial(id) {
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDelete() {
            let id = $('#delete-id').val();
            let url = "{{ route('testimonial.destroy', ':id') }}".replace(':id', id);
            $('#button-delete').prop('disabled', true).text('Deleting...');

            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    testimonialTable.ajax.reload(null, false);
                    toastr.success(response.message || 'Testimonial deleted successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to delete testimonial'));
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false).text('Delete');
                }
            });
        }

        initRichEditors();
        $('#rating-1').prop('checked', true);
        $('#edit-rating-1').prop('checked', true);
    </script>
@endsection
