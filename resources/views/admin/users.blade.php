@extends('layouts.app-admin')
@section('content')
<div class="m-5">
        <div class="flex justify-between items-center p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700 mb-2"><i class="fa-solid fa-chart-line"></i> ผู้ใช้งานระบบ</h1>
            <button id="btn-modal-add-user" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                <i class="fa-solid fa-plus"></i> เพิ่มผู้ใช้
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                <thead>
                    <tr class="bg-green-100 text-green-600">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">รูปภาพ</th>
                        <th class="px-4 py-2 border-b">ชื่อ</th>
                        <th class="px-4 py-2 border-b">ชื่อผู้ใช้ (Username)</th>
                        <th class="px-4 py-2 border-b">อีเมล</th>
                        <th class="px-4 py-2 border-b">เบอร์โทร</th>
                        <th class="px-4 py-2 border-b">ที่อยู่</th>
                        <th class="px-4 py-2 border-b">วันที่สร้าง</th>
                        <th class="px-4 py-2 border-b">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- js -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-add-user" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-center text-xl font-bold mb-4">สร้างผู้ใช้ใหม่</h2>
            <div class="mb-4">
                <div class="flex items-center gap-4 mb-3">
                    <img id="user-image" src="{{ asset('images/avatars/No_image.png') }}" alt="รูปโปรไฟล์" class="w-16 h-16 rounded-full object-cover border">
                    <div>
                        <label class="block text-sm font-medium">อัปโหลดรูปใหม่</label>
                        <input type="file" id="user-avatar" class="mt-1 text-sm text-gray-600">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="user-name">ชื่อ <span class="text-sm text-red-500">(ต้องกรอก)</span></label>
                    <input type="text" id="user-name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="user-username">ชื่อผู้ใช้ (Username) <span class="text-sm text-red-500">(ต้องกรอก)</span></label>
                    <input type="text" id="user-username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="user-password">รหัสผ่าน (Password) <span class="text-sm text-red-500">(ต้องกรอก)</span></label>
                    <input type="password" id="user-password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="user-email">อีเมล</label>
                    <input type="email" id="user-email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="user-phone">เบอร์โทร</label>
                    <input type="text" id="user-phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="user-address">ที่อยู่</label>
                    <input type="text" id="user-address" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-center gap-3">
                <button id="btn-add-user" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fa-solid fa-plus"></i> เพิ่ม
                </button>
                <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
            </div>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-lg">
            <h2 class="text-center text-2xl font-bold text-green-700 mb-6">แก้ไขข้อมูลผู้ใช้</h2>

            <form id="edit-form" enctype="multipart/form-data" class="space-y-4 text-gray-800">
                @csrf
                <input type="hidden" name="id" id="edit-id">
                <!-- รูปโปรไฟล์ -->
                <div class="flex items-center gap-4">
                    <img id="edit-image" src="" alt="รูปโปรไฟล์" class="w-16 h-16 rounded-full object-cover border">
                    <div>
                        <label class="block text-sm font-medium">อัปโหลดรูปใหม่</label>
                        <input type="file" name="avatar" id="avatar-input" class="mt-1 text-sm text-gray-600">
                    </div>
                </div>

                <!-- ชื่อ -->
                <div>
                    <label for="edit-name" class="block text-sm font-medium">ชื่อ</label>
                    <input type="text" id="edit-name" name="name" class="w-full px-3 py-2 border rounded-lg" required>
                </div>

                <!-- Username (ห้ามแก้) -->
                <div>
                    <label class="block text-sm font-medium">ชื่อผู้ใช้ (Username)</label>
                    <input type="text" id="edit-username" class="w-full px-3 py-2 border rounded-lg bg-gray-100" readonly>
                </div>

                <!-- Email (ห้ามแก้) -->
                <div>
                    <label class="block text-sm font-medium">อีเมล</label>
                    <input type="email" id="edit-email" class="w-full px-3 py-2 border rounded-lg bg-gray-100" readonly>
                </div>

                <!-- โทรศัพท์ (ห้ามแก้) -->
                <div>
                    <label class="block text-sm font-medium">เบอร์โทร</label>
                    <input type="text" id="edit-phone" class="w-full px-3 py-2 border rounded-lg bg-gray-100" readonly>
                </div>

                <!-- ที่อยู่ -->
                <div>
                    <label for="edit-address" class="block text-sm font-medium">ที่อยู่</label>
                    <textarea id="edit-address" name="address" rows="3" class="w-full px-3 py-2 border rounded-lg resize-none"></textarea>
                </div>

                <!-- วันที่สร้าง (แสดงเฉย ๆ) -->
                <div>
                    <label class="block text-sm font-medium">วันที่สมัคร</label>
                    <input type="text" id="edit-created-at" class="w-full px-3 py-2 border rounded-lg bg-gray-100" readonly>
                </div>
            </form>

            <div class="flex justify-center gap-4 mt-6">
                <button type="submit" form="edit-form" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    อัปเดต
                </button>
                <button type="button" class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 closeModal">
                    ปิด
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        var table = $('#table').DataTable({
            ajax: {
                url: '/admin/users',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'image' },
                { data: 'name' },
                { data: 'username' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'address' },
                { data: 'created_at' },
                { data: 'action' }
            ],
            responsive: true,
        });

        $('#btn-modal-add-user').on('click', function() {
            $('#modal-add-user').removeClass('hidden').addClass('flex');

            $('#user-image').attr('src', '/images/avatars/No_image.png');
            $('#user-avatar').val('');
            $('#user-name').val('');
            $('#user-username').val('');
            $('#user-password').val('');
            $('#user-email').val('');
            $('#user-phone').val('');
            $('#user-address').val('');
        });

        $('#user-avatar').on('change', function (event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    $('#user-image').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        });

        $('#btn-add-user').on('click', function() {
            const avatarInput = $('#user-avatar')[0];
            const name = $('#user-name').val();
            const username = $('#user-username').val();
            const password = $('#user-password').val();
            const email = $('#user-email').val();
            const phone = $('#user-phone').val();
            const address = $('#user-address').val();

            if (!name || !username || !password) {
                Swal.fire('ผิดพลาด', 'กรุณากรอกข้อมูลที่จำเป็น', 'error');
                return;
            }

            const formData = new FormData();
            if (avatarInput.files.length > 0) {
                formData.append('avatar', avatarInput.files[0]);
            }
            formData.append('name', name);
            formData.append('username', username);
            formData.append('password', password);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);

            $.ajax({
                url: '/admin/users/store',
                method: 'POST',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function (res) {
                    if (res.status) {
                        Swal.fire('สำเร็จ', 'เพิ่มผู้ใช้ใหม่เรียบร้อยแล้ว', 'success');
                        $('#modal-add-user').addClass('hidden');

                        table.ajax.reload();
                    } else {
                        Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถเพิ่มผู้ใช้ได้', 'error');
                    }
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message || 'เกิดข้อผิดพลาด';
                    Swal.fire('ผิดพลาด', msg, 'error');
                }
            });
        });

        $(document).on('click', '.btn-info', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '/admin/users/data',
                method: 'GET',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.status) {
                        const data = res.data;
                        $('#edit-id').val(id);
                        $('#edit-image').attr('src', data.image);
                        $('#avatar-input').val('');
                        $('#edit-name').val(data.name);
                        $('#edit-username').val(data.username);
                        $('#edit-email').val(data.email);
                        $('#edit-phone').val(data.phone);
                        $('#edit-address').val(data.address);
                        $('#edit-created-at').val(data.created_at);

                        $('#modal-edit').removeClass('hidden').addClass('flex');
                    } else {
                        Swal.fire('ผิดพลาด', 'ไม่สามารถโหลดข้อมูลได้', 'error');
                    }
                },
                error: function () {
                    Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                }
            });
        });

        $('#avatar-input').on('change', function (event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    $('#edit-image').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        });

        $('#edit-form').on('submit', function (e) {
            e.preventDefault();

            const form = $(this)[0];
            const formData = new FormData(form);

            $.ajax({
                url: '/admin/users/update',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res.status) {
                        Swal.fire('สำเร็จ', 'อัปเดตข้อมูลเรียบร้อยแล้ว', 'success');
                        $('#modal-edit').addClass('hidden');

                        table.ajax.reload();
                    } else {
                        Swal.fire('ผิดพลาด', res.message || 'อัปเดตไม่สำเร็จ', 'error');
                    }
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message || 'เกิดข้อผิดพลาด';
                    Swal.fire('ผิดพลาด', msg, 'error');
                }
            });
        });

        $('.closeModal').on('click', function() {
            $('#modal-add-user').addClass('hidden');
            $('#modal-edit').addClass('hidden');
        });
    </script>
@endsection
