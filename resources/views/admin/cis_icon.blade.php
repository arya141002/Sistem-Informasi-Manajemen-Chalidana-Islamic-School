@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-blod py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span>{{ $title }}</h4>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#Modal"><i class="bx bx-plus"></i> Add {{ $title }}</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="cisicon-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Add {{$title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" class="form-control" name="title" />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="file" class="form-label">Image</label>
                            <input type="file" name="file" id="file" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-save" onclick="simpan()">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Edit {{$title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3 py-md-5">
                    <div class="row">
                        <input type="hidden" id="edit-id">
                        <div class="col-12 mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" id="edit_title" class="form-control" name="edit_title" />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit_file" class="form-label">Image</label>
                            <input type="file" name="edit_file" id="edit_file" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="button-edit" onclick="updatex()">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete CIS Icon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure want to delete this icon?</p>
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
            return `/images/icon/${filename}`;
        }

        let tableku = $('#cisicon-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('cis-icon.data')}}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {
                    data: 'file',
                    name: 'file',
                    render: function (data) {
                        if (!data) return '-';
                        return `<img src="${imageUrl(data)}" alt="Facility" class="img-thumbnail" style="height: 52px; width: 78px; object-fit: cover;">`;
                    }
                },
                { data: 'title', name: 'title' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        })

        function resetform(){
            $('#title').val('');
            $('file').val('')
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

        function simpan(){
            let title = $('#title').val();
            let img = $('#file').prop('files')[0];
            if (!title || !img) {
                toastr.error('Judul dan File wajib diisi!');
                return;
            }

            let fq = new FormData();
            fq.append('title', title);
            fq.append('file', img);
            fq.append('_token', '{{ csrf_token() }}');

            $('#button-save').prop('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('cis-icon.store')}}',
                method: 'POST',
                data: fq,
                processData: false,
                contentType: false,
                success: function (response){
                    $('#Modal').modal('hide');
                    tableku.ajax.reload();
                    resetform();
                    toastr.success(response.message || 'CIS Icon created successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to save CIS Icon'));
                    resetform()
                },
                complete: function () {
                    $('#button-save').prop('disabled', false).text('Save');
                }
            })
        }

        function editcisicon(id){
            $.ajax({
                url: `{{ url('/admin/cis-icon') }}/${id}`,
                method: 'GET',
                success: function (response){
                    $('#edit-id').val(id);
                    $('#edit_title').val(response.title);
                    if (response.file) {
                        $('#old_file_name').html('File saat ini: <a href="/uploads/' + response.file + '" target="_blank">' + response.file + '</a>');
                    } else {
                        $('#old_file_name').text('Belum ada file.');
                    }
                    $('#EditModal').modal('show');
                },
                error: function () {
                    toastr.error('Failed to fetch cis icon data');
                }
            })
        }

        function updatex(){
            let id = $('#edit-id').val();
            let title = $('#edit_title').val();
            let file = $('#edit_file').prop('files')[0];
            let url = "{{ route('cis-icon.update', ':id') }}";
            url = url.replace(':id', id);

            let ffq = new FormData();
            ffq.append('_method', 'PUT');
            ffq.append('id', id);
            ffq.append('title', title);
            ffq.append('_token', '{{ csrf_token() }}');
            if(file){
                ffq.append('file', file);
            }
            $('#button-update').prop('disabled', true).text('Saving...');
            $.ajax({
                url: url,
                method: 'POST',
                data: ffq,
                processData: false, 
                contentType: false,
                success: function (response){
                    $('#EditModal').modal('hide');
                    tableku.ajax.reload();
                    toastr.success(response.message || 'CIS Icon updated successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to update facility'));
                },
                complete: function () {
                    $('#button-update').prop('disabled', false).text('Update');
                }
            })
        }

        function deletecisicon(id){
            $('#delete-id').val(id);
            $('#deleteModal').modal('show');
        }

        function confirmDelete(){
            let id = $('#delete-id').val();
            $('#button-delete').prop('disabled', true).text('Deleting...');
            let url = "{{ route('cis-icon.destroy', ':id')}}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#deleteModal').modal('hide');
                    tableku.ajax.reload();
                    toastr.success(response.message || 'Icon deleted successfully');
                },
                error: function (xhr) {
                    toastr.error(formatValidationErrors(xhr, 'Failed to delete Icon'));
                },
                complete: function () {
                    $('#button-delete').prop('disabled', false).text('Delete');
                }
            })
        }
    </script>
@endsection
