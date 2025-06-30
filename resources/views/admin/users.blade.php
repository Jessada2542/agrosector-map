@extends('layouts.app-admin')
@section('content')
<div class="m-5">
        <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700 mb-2"><i class="fa-solid fa-chart-line"></i> ผู้ใช้งานระบบ</h1>
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

    <div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-lg">
            <h2 class="text-center text-2xl font-bold text-green-700 mb-6">แก้ไขข้อมูลผู้ใช้</h2>

            <form id="edit-form" class="space-y-4 text-gray-800">
                <!-- รูปโปรไฟล์ -->
                <div class="flex items-center gap-4">
                    <img id="edit-image" src="" alt="รูปโปรไฟล์" class="w-16 h-16 rounded-full object-cover border">
                    <div>
                        <label class="block text-sm font-medium">อัปโหลดรูปใหม่</label>
                        <input type="file" name="avatar" class="mt-1 text-sm text-gray-600">
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
            reponsive: true,
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
                        $('#edit-image').attr('src', data.image);
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

        $('.closeModal').on('click', function() {
            $('#modal-edit').addClass('hidden');
        });
    </script>
@endsection
