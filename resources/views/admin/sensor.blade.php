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
                <div class="text-3xl font-bold text-green-800" id="sensor-use">{{ $sensorUse }} ตัว</div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> เซ็นเซอร์ที่ว่าง</div>
                <div class="text-3xl font-bold text-green-800" id="sensor-not-use">{{ $sensorNotUse }} ตัว</div>
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

        $('#device-province').select2({
            placeholder: 'เลือกจังหวัด',
            allowClear: true,
            width: '100%',
        });

        $('#device-district').select2({
            placeholder: 'เลือกอำเภอ',
            allowClear: true,
            width: '100%',
        });

        $('#device-subdistrict').select2({
            placeholder: 'เลือกตำบล',
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
                            $('#sensor-device').append('<option value="' + item.id + '">' + item.key + '</option>');
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
                url: '/admin/sensor/store',
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
                        $('#sensor-use').text(parseInt($('#sensor-use').text()) + 1 + ' ตัว');
                        $('#sensor-not-use').text(parseInt($('#sensor-not-use').text()) - 1 + ' ตัว');

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

        $('#table').on('click', '.btn-edit', function() {
            var deviceId = $(this).data('id');

            $.ajax({
                url: '/admin/sensor/edit/' + deviceId,
                method: 'GET',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#modal-edit').removeClass('hidden');
                    $('#text-sensor-name').text('แก้ไข : ' + response.data.sensor_key.key);
                    $('#device-key').val(response.data.sensor_key.key);
                    $('#device-position-lat').val(response.data.lat ?? '');
                    $('#device-position-lon').val(response.data.lon ?? '');
                    $('#device-id').val(response.data.id);

                    // === โหลดจังหวัดและ trigger change เพื่อโหลดอำเภอ ===
                    $('#device-province').val(response.data.province_code).trigger('change');

                    // === โหลดอำเภอแล้ว set ค่า ===
                    $.ajax({
                        url: '/api/districts',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            province_code: response.data.province_code
                        },
                        dataType: 'json',
                        success: function(districts) {
                            $('#device-district').empty().append('<option value="" disabled selected>เลือกอำเภอ</option>');
                            $.each(districts, function(index, item) {
                                $('#device-district').append('<option value="' + item.id + '">' + item.name + '</option>');
                            });

                            $('#device-district').val(response.data.district_code).trigger('change');

                            // === โหลดตำบลแล้ว set ค่า ===
                            $.ajax({
                                url: '/api/subdistricts',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    district_code: response.data.district_code
                                },
                                dataType: 'json',
                                success: function(subdistricts) {
                                    $('#device-subdistrict').empty().append('<option value="" disabled selected>เลือกตำบล</option>');
                                    $.each(subdistricts, function(index, item) {
                                        $('#device-subdistrict').append('<option value="' + item.id + '">' + item.name + '</option>');
                                    });

                                    $('#device-subdistrict').val(response.data.subdistrict_code);
                                }
                            });
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่พบอุปกรณ์ device.', 'error');
                }
            });
        });

        $('#btn-save-edit').on('click', function() {
            const deviceId = $('#device-id').val();
            const positionLat = $('#device-position-lat').val();
            const positionLon = $('#device-position-lon').val();
            const provinceId = $('#device-province').val();
            const districtId = $('#device-district').val();
            const subdistrictId = $('#device-subdistrict').val();

            if (!positionLat || !positionLon) {
                Swal.fire('ผิดพลาด!', 'กรุณากรอกตำแหน่ง Latitude และ Longitude.', 'error');
                return;
            }

            if (!provinceId || !districtId || !subdistrictId) {
                Swal.fire('ผิดพลาด!', 'กรุณาเลือกจังหวัด, อำเภอ และตำบล.', 'error');
                return;
            }

            $.ajax({
                url: '/admin/sensor/update',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: deviceId,
                    lat: positionLat,
                    lon: positionLon,
                    province_code: provinceId,
                    district_code: districtId,
                    subdistrict_code: subdistrictId
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Success!', 'อัพเดตข้อมูลอุปกรณ์เรียบร้อยแล้ว.', 'success');
                        $('#modal-edit').addClass('hidden');

                        table.ajax.reload();
                    } else {
                        Swal.fire('ผิดพลาด!', 'ไม่สามารถอัพเดตข้อมูลอุปกรณ์ได้.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถอัพเดตข้อมูลอุปกรณ์ได้.', 'error');
                }
            });
        });

        $('#device-province').on('change', function() {
            var provinceId = $(this).val();
            $('#device-district').empty().append('<option value="" disabled selected>เลือกอำเภอ</option>');
            $('#device-subdistrict').empty().append('<option value="" disabled selected>เลือกตำบล</option>');
            if (provinceId) {
                $.ajax({
                    url: '/api/districts',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        province_code: provinceId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#device-district').append('<option value="' + item.id + '">' + item
                                .name + '</option>');
                        });
                    }
                });
            }
        });

        $('#device-district').on('change', function() {
            var districtId = $(this).val();
            $('#device-subdistrict').empty().append('<option value="" disabled selected>เลือกตำบล</option>');
            if (districtId) {
                $.ajax({
                    url: '/api/subdistricts',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        district_code: districtId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#device-subdistrict').append('<option value="' + item.id + '">' + item
                                .name + '</option>');
                        });
                    }
                });
            }
        });

        $('.closeModal').on('click', function() {
            $('#modal-add').addClass('hidden');
            $('#modal-edit').addClass('hidden');
        });
    </script>
@endsection
