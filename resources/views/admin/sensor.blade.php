@extends('layouts.app-admin')
@section('content')
<div class="m-5">
        <div class="flex justify-between items-center p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700 mb-2"><i class="fa-solid fa-gear"></i> จัดการเซนเซอร์</h1>
            <button id="btn-modal-add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                <i class="fa-solid fa-plus"></i> เพิ่มเซนเซอร์
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> เซนเซอร์ทั้งหมด</div>
                <div class="text-3xl font-bold text-green-800">{{ $sensorAll }} ตัว</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> เซนเซอร์ที่ใช้งาน</div>
                <div class="text-3xl font-bold text-green-800">{{ $sensorUse }} ตัว</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> เซ็นเซอร์ที่ว่าง</div>
                <div class="text-3xl font-bold text-green-800">{{ $sensorNotUse }} ตัว</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                <thead>
                    <tr class="bg-green-100 text-green-600">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">ผู้ใช้งาน</th>
                        <th class="px-4 py-2 border-b">รหัสอุปกรณ์</th>
                        <th class="px-4 py-2 border-b">ตำแหน่ง</th>
                        <th class="px-4 py-2 border-b">ที่ตั้ง</th>
                        {{-- <th class="px-4 py-2 border-b">สถานะ</th> --}}
                        <th class="px-4 py-2 border-b">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- js -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-add" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-center text-xl font-bold mb-4">เพิ่มเซนเซอร์ให้ผู้ใช้งาน</h2>
            <div class="mb-4">
                <div class="mb-3">
                    <label for="sensor-device">อุปกรณ์ (S/N) <span class="text-sm text-red-500">(ต้องกรอก)</span></label>
                    <select id="sensor-device" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>เลือกอุปกรณ์</option>
                        <!-- js -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="sensor-username">ชื่อผู้ใช้ <span class="text-sm text-red-500">(ต้องกรอก)</span></label>
                    <select id="sensor-username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>เลือกผู้ใช้</option>
                        @foreach ($users as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-center gap-3">
                <button id="btn-add" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fa-solid fa-plus"></i> เพิ่ม
                </button>
                <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
            </div>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-center text-xl font-bold mb-4" id="text-sensor-name">ข้อมูล Sensor</h2>
            <div class="mb-4">
                <div class="mb-3">
                    <label for="device-key">รหัสอุปกรณ์</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-2" placeholder="รหัสอุปกรณ์" id="device-key" readonly>
                </div>
                <div class="mb-3">
                    <label for="device-position-lat">ตำแหน่ง Latitude</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-2" placeholder="ตำแหน่ง Lat" id="device-position-lat">
                </div>
                <div class="mb-3">
                    <label for="device-position-lon">ตำแหน่ง Longitude</label>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded mt-2" placeholder="ตำแหน่ง Lon" id="device-position-lon">
                </div>
                <div class="mb-3">
                    <label for="device-province">จังหวัด</label>
                    <select id="device-province" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" selected>เลือกจังหวัด</option>
                        @foreach ($province as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="device-district">อำเภอ</label>
                    <select id="device-district" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" selected>เลือกอำเภอ</option>
                        @foreach ($district as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="device-subdistrict">ตำบล</label>
                    <select id="device-subdistrict" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" selected>เลือกตำบล</option>
                        @foreach ($subdistrict as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" id="device-id">
            <div class="flex justify-center">
                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2" id="btn-save-edit">บันทึก</button>
                <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        var table = $('#table').DataTable({
            ajax: {
                url: '/admin/sensor',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'user_name' },
                { data: 'sensor_key' },
                { data: 'position' },
                { data: 'address' },
                /* { data: 'status' }, */
                { data: 'action' }
            ],
            responsive: true,
        });

        $('#sensor-device').select2({
            placeholder: 'เลือกอุปกรณ์',
            allowClear: true,
            width: '100%',
        });

        $('#sensor-username').select2({
            placeholder: 'เลือกผู้ใช้',
            allowClear: true,
            width: '100%',
        });

        $('#btn-modal-add').on('click', function() {
            $('#modal-add').removeClass('hidden').addClass('flex');
            $('#sensor-device').empty().append('<option value="" disabled selected>เลือกอุปกรณ์</option>');
            $('#sensor-username').val('');

            $.ajax({
                url: '/admin/sensor/data',
                type: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status) {
                        $('#modal-add').removeClass('hidden');
                        $.each(response.data, function(index, item) {
                            $('#sensor-device').append('<option value="' + item.id + '">' + item.sensor_key.key + '</option>');
                        });
                    } else {
                        Swal.fire('ผิดพลาด!', 'ไม่พบอุปกรณ์ที่สามารถเพิ่มได้', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงรายการอุปกรณ์ได้', 'error');
                }
            });
        });

        $('#btn-add').on('click', function() {
            const sensorDevice = $('#sensor-device').val();
            const sensorUsername = $('#sensor-username').val();

            if (!sensorDevice || !sensorUsername) {
                Swal.fire('ผิดพลาด', 'กรุณาเลือกอุปกรณ์และผู้ใช้', 'warning');
                return;
            }

            $.ajax({
                url: '/admin/sensors/store',
                method: 'POST',
                data: {
                    sensor_id: sensorDevice,
                    user_id: sensorUsername,
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.status) {
                        Swal.fire('สำเร็จ', 'เพิ่มเซ็นเซอร์เรียบร้อยแล้ว', 'success');
                        $('#modal-add').addClass('hidden');

                        table.ajax.reload();
                    } else {
                        Swal.fire('ผิดพลาด', res.message || 'ไม่สามารถเพิ่มเซ็นเซอร์ได้', 'error');
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

        $('.closeModal').on('click', function() {
            $('#modal-add').addClass('hidden');
            $('#modal-edit').addClass('hidden');
        });
    </script>
@endsection
