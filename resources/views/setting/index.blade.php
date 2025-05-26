@extends('layouts.app')
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
                        <th class="px-4 py-2 border-b">สถานะ</th>
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
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ],
                reponsive: true,
            });

            $('#table').on('click', '.btn-edit', function() {
                var deviceId = $(this).data('id');
                console.log('Edit device with ID:', deviceId);

                $.ajax({
                    url: '/setting/edit/device/' + deviceId,
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);

                        $('#modal-edit-sensor').removeClass('hidden');
                        $('#text-sensor-name').text('แก้ไข : ' + response.data.sensor_key.key);
                        $('#device-key').val(response.data.sensor_key.key);
                        $('#device-position-lat').val(response.data.lat);
                        $('#device-position-lng').val(response.data.lon);
                        $('#device-id').val(response.data.id);
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'ไม่พบอุปกรณ์ device.', 'error');
                    }
                });

                /* Swal.fire({
                    title: 'Edit Device',
                    text: 'You can edit the device here.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Device edited:', deviceId);
                    }
                }); */
            });

            $('#btn-save-sensor').on('click', function() {
                var deviceId = $('#device-id').val();
                var positionLat = $('#device-position-lat').val();
                var positionLon = $('#device-position-lon').val();

                if (!positionLat || !positionLon) {
                    Swal.fire('Error!', 'กรุณากรอกตำแหน่ง Latitude และ Longitude.', 'error');
                    return;
                }

                $.ajax({
                    url: '/setting/update/device/',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: deviceId,
                        lat: positionLat,
                        lon: positionLon
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
