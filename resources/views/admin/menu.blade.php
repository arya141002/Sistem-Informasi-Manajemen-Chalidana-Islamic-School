@extends('layouts.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><i class="bx bx-chevron-right bx-xs"></i></span> {{ $title }}</h4>
        <!-- Bootstrap Table with Caption -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#menuModal"><i class="bx bx-plus"></i> Add Menu</button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table" id="menu-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Parent</th>
                                <th>URL</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Add New Menu</h3>
                        <p class="mb-4">Isi form berikut untuk menambahkan menu baru.</p>
                    </div>
                    <form class="row g-3">
                        <div class="col-6">
                            <label class="form-label" for="order">Order</label>
                            <input type="number" id="order" name="order" class="form-control" placeholder="Masukkan urutan menu (opsional)" />
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="icon">Icon</label>
                            <select id="icon" name="icon" class="form-select">
                                <option value="">-- Pilih Icon --</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Masukkan judul menu" required />
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="parent_id">Parent Menu</label>
                            <select id="parent_id" name="parent_id" class="form-select">
                                <option value="">-- Pilih Parent Menu (Opsional) --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="url">URL</label>
                            <input type="text" id="url" name="url" class="form-control" placeholder="Masukkan URL menu (opsional untuk parent)" />
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="roles">Roles</label>
                            <select id="roles" name="roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" id="button-menu" class="btn btn-primary me-sm-3 me-1" onclick="simpan()">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Edit Menu</h3>
                        <p class="mb-4">Ubah form berikut untuk memperbarui menu.</p>
                    </div>
                    <form class="row g-3">
                        <input type="hidden" id="edit_menu_id" name="edit_menu_id" />
                        <div class="col-6">
                            <label class="form-label" for="edit_order">Order</label>
                            <input type="number" id="edit_order" name="edit_order" class="form-control" placeholder="Masukkan urutan menu (opsional)" />
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="edit_icon">Icon</label>
                            <select id="edit_icon" name="edit_icon" class="form-select">
                                <option value="">-- Pilih Icon --</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="edit_title">Title</label>
                            <input type="text" id="edit_title" name="edit_title" class="form-control" placeholder="Masukkan judul menu" required />
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="edit_parent_id">Parent Menu</label>
                            <select id="edit_parent_id" name="edit_parent_id" class="form-select">
                                <option value="">-- Pilih Parent Menu (Opsional) --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="edit_url">URL</label>
                            <input type="text" id="edit_url" name="edit_url" class="form-control" placeholder="Masukkan URL menu (opsional untuk parent)" />
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="edit_roles">Roles</label>
                            <select id="edit_roles" name="edit_roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" id="button-edit-menu" class="btn btn-primary me-sm-3 me-1" onclick="updateMenu()">Update</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirm-delete-btn" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function reset(){
            $('#order').val('');
            $('#icon').val('');
            $('#title').val('');
            $('#parent_id').val('');
            $('#url').val('');
            $('#roles').val([]);
            $('#order, #icon, #title, #url, #roles').removeClass('is-invalid');
            $('#button-menu').prop('disabled', false).text('Submit');
        }

        function appendIconOption(select, value, label) {
            if (!select.querySelector(`option[value="${value}"]`)) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;
                select.appendChild(option);
            }
        }

        async function populateIcons(selectedEditIcon = null) {
            const iconSelect = document.getElementById('icon');
            const editIconSelect = document.getElementById('edit_icon');
            while (iconSelect.options.length > 1) {
                iconSelect.remove(1);
            }
            while (editIconSelect.options.length > 1) {
                editIconSelect.remove(1);
            }

            try {
                const response = await fetch('https://cdn.jsdelivr.net/npm/boxicons@latest/svg.json');
                if (!response.ok) throw new Error('Failed to fetch');
                
                const data = await response.json();
                const iconNames = Object.keys(data.categories).slice(0, 100); // Ambil 100 icon pertama
                
                iconNames.forEach(iconName => {
                    const value = 'bx bx-' + iconName;
                    const label = 'bx-' + iconName;
                    appendIconOption(iconSelect, value, label);
                    appendIconOption(editIconSelect, value, label);
                });
            } catch (error) {
                console.log('API fetch failed, using local list');
                const localIcons = [
                    'home', 'menu', 'grid-alt', 'user', 'users', 'cog', 'bell',
                    'envelope', 'file', 'folder', 'trash', 'edit', 'plus', 'minus',
                    'search', 'arrow-back', 'arrow-forward', 'chevron-right', 'check',
                    'x', 'download', 'upload', 'bar-chart-alt-2', 'calendar', 'lock',
                    'unlock', 'phone', 'printer', 'database', 'server', 'wifi',
                    'compass', 'map', 'link', 'share-alt', 'save', 'refresh',
                    'time', 'timer', 'filter', 'sort', 'layout', 'image',
                    'video', 'music', 'list-ul', 'list-ol', 'table', 'coin-stack',
                    'cart', 'purchase-tag', 'heart', 'star', 'award', 'credit-card'
                ];
                
                localIcons.forEach(icon => {
                    const value = 'bx bx-' + icon;
                    const label = 'bx-' + icon;
                    appendIconOption(iconSelect, value, label);
                    appendIconOption(editIconSelect, value, label);
                });
            }

            if (selectedEditIcon) {
                appendIconOption(editIconSelect, selectedEditIcon, selectedEditIcon.replace('bx ', ''));
                editIconSelect.value = selectedEditIcon;
            }
        }

        // Load icons when modal opens
        document.getElementById('menuModal').addEventListener('show.bs.modal', function() {
            populateIcons();
        });

        let tabel = $('#menu-table').DataTable({
            serverSide: true,
            ajax: '{{ route('menu.data') }}',
            columns: [
                { data: 'order', name: 'order' },
                { data: 'icon', name: 'icon', render: function(data) {
                    return `<i class="${data}"></i>`;
                }},
                { data: 'title', name: 'title' },
                { data: 'parent_title', name: 'parent_title' },
                { data: 'url', name: 'url' },
                { data: 'role_access', name: 'role_access' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

        function simpan(){
            let order = $('#order').val();
            let icon = $('#icon').val();
            let title = $('#title').val();
            let parent_id = $('#parent_id').val();
            let url = $('#url').val();
            let roles = $('#roles').val();
            if (!title) {
                $('#title').addClass('is-invalid');
                toastr.error('Title wajib diisi.');
                return;
            }
            $('#button-menu').prop('disabled', true).text('Saving...');
            $.ajax({
                url: "{{ route('menu.store') }}",
                method: "POST",
                data: {
                    order: order,
                    icon: icon,
                    title: title,
                    parent_id: parent_id,
                    url: url,
                    roles: roles,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    $('#menuModal').modal('hide');
                    reset();
                    tabel.ajax.reload();
                    toastr.success('Menu berhasil disimpan!');
                },
                error: function(xhr){
                    $('#menuModal').modal('hide');
                    reset();
                    toastr.error(formatValidationErrors(xhr, 'Gagal menyimpan menu.'));
                },
                complete: function(){
                    $('#button-menu').prop('disabled', false).text('Submit');
                }
            });
        }

        function editMenu(id){
            $.ajax({
                url: `/menu/${id}`,
                method: "GET",
                success: async function(response){
                    $('#edit_menu_id').val(response.id);
                    $('#edit_order').val(response.order);
                    $('#edit_title').val(response.title);
                    $('#edit_parent_id option').prop('disabled', false);
                    $('#edit_parent_id option[value="' + response.id + '"]').prop('disabled', true);
                    $('#edit_parent_id').val(response.parent_id);
                    $('#edit_url').val(response.url);
                    await populateIcons(response.icon);
                    if (response.role_access) {
                        let rolesArray = typeof response.role_access === 'string' 
                            ? response.role_access.split(',') 
                            : response.role_access;
                        $('#edit_roles').val(rolesArray).trigger('change');
                    } else {
                        $('#edit_roles').val([]).trigger('change');
                    }
                    $('#editMenuModal').modal('show');
                },
                error: function(){
                    toastr.error('Gagal mengambil data menu. Silakan coba lagi.');
                }
            });
        }

        function updateMenu(){
            let id = $('#edit_menu_id').val();
            let order = $('#edit_order').val();
            let icon = $('#edit_icon').val();
            let title = $('#edit_title').val();
            let parent_id = $('#edit_parent_id').val();
            let url = $('#edit_url').val();
            let roles = $('#edit_roles').val();
            if (!title) {
                $('#edit_title').addClass('is-invalid');
                toastr.error('Title wajib diisi.');
                return;
            }
            if (Number(parent_id) === Number(id)) {
                toastr.error('Menu tidak boleh menjadi parent untuk dirinya sendiri.');
                return;
            }
            $('#button-edit-menu').prop('disabled', true).text('Updating...');
            $.ajax({
                url: `/menu/${id}`,
                method: "PUT",
                data: {
                    order: order,
                    icon: icon,
                    title: title,
                    parent_id: parent_id,
                    url: url,
                    roles: roles,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    $('#editMenuModal').modal('hide');
                    tabel.ajax.reload();
                    toastr.success('Menu berhasil diperbarui!');
                },
                error: function(xhr){
                    $('#editMenuModal').modal('hide');
                    toastr.error(formatValidationErrors(xhr, 'Gagal memperbarui menu.'));
                },
                complete: function(){
                    $('#button-edit-menu').prop('disabled', false).text('Update');
                }
            });
        }

        let deleteId = null;

        function deleteMenu(id) {
            deleteId = id; // Simpan ID yang akan dihapus
            $('#confirmDeleteModal').modal('show'); // Tampilkan modal
        }

        // Event saat tombol "Hapus" di dalam modal diklik
        $('#confirm-delete-btn').on('click', function() {
            $.ajax({
                url: `/menu/${deleteId}`,
                method: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    $('#confirmDeleteModal').modal('hide');
                    tabel.ajax.reload();
                    toastr.success('Menu berhasil dihapus!');
                }
            });
        });
    </script>
@endsection
