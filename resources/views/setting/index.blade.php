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
                <div id="sensor-content" class="mb-4">กำลังโหลด...</div>
                <div class="flex justify-center">
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
                        $('#text-sensor-name').text('Edit Device: ' + response.data.sensor_key.key);
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

            $(document).on('click', '.closeModal', function() {
                $('#modal-edit-sensor').addClass('hidden');
            });
        });
    </script>
@endsection
