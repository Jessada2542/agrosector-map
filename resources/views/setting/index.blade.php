@extends('layouts.app')
<style>
    .select2-container .select2-selection--single {
        height: 40px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #000000 !important;
        line-height: 38px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #000000 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }
</style>
@section('content')
    <div class="m-5">
        <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700 mb-2"><i class="fa-solid fa-gear"></i> ตั้งค่า</h1>
            <p class="text-green-900">อุปกรณ์ใช้งานในระบบ</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                <thead>
                    <tr class="bg-green-100 text-green-600">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">รหัสอุปกรณ์</th>
                        <th class="px-4 py-2 border-b">ตำแหน่ง</th>
                        <th class="px-4 py-2 border-b">ที่ตั้ง</th>
                        <th class="px-4 py-2 border-b">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- js -->
                </tbody>
            </table>
        </div>

        <div id="modal-edit-sensor" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
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
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2" id="btn-save-sensor">บันทึก</button>
                    <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                ajax: {
                    url: '/setting',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'device_key'
                    },
                    {
                        data: 'position'
                    },
                    {
                        data: 'address',
                        width: '30%'
                    },
                    /* {
                        data: 'status'
                    }, */
                    {
                        data: 'action'
                    }
                ],
                reponsive: true,
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

            $('#table').on('click', '.btn-edit', function() {
                var deviceId = $(this).data('id');

                $.ajax({
                    url: '/setting/edit/device/' + deviceId,
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modal-edit-sensor').removeClass('hidden');
                        $('#text-sensor-name').text('แก้ไข : ' + response.data.sensor_key.key);
                        $('#device-key').val(response.data.sensor_key.key);
                        $('#device-position-lat').val(response.data.lat ?? '');
                        $('#device-position-lon').val(response.data.lon ?? '');
                        $('#device-id').val(response.data.id);
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'ไม่พบอุปกรณ์ device.', 'error');
                    }
                });
            });

            $('#btn-save-sensor').on('click', function() {
                var deviceId = $('#device-id').val();
                var positionLat = $('#device-position-lat').val();
                var positionLon = $('#device-position-lon').val();
                var provinceId = $('#device-province').val();
                var districtId = $('#device-district').val();
                var subdistrictId = $('#device-subdistrict').val();

                if (!positionLat || !positionLon) {
                    Swal.fire('Error!', 'กรุณากรอกตำแหน่ง Latitude และ Longitude.', 'error');
                    return;
                }

                if (!provinceId || !districtId || !subdistrictId) {
                    Swal.fire('Error!', 'กรุณาเลือกจังหวัด, อำเภอ และตำบล.', 'error');
                    return;
                }

                $.ajax({
                    url: '/setting/update/device/',
                    method: 'PUT',
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
                            table.ajax.reload();
                            $('#modal-edit-sensor').addClass('hidden');
                        } else {
                            Swal.fire('Error!', 'ไม่สามารถอัพเดตข้อมูลอุปกรณ์ได้.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'ไม่สามารถอัพเดตข้อมูลอุปกรณ์ได้.', 'error');
                    }
                });
            });

            $(document).on('click', '.closeModal', function() {
                $('#modal-edit-sensor').addClass('hidden');
            });
        });
    </script>
@endsection
