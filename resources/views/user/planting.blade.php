@extends('layouts.app')
@section('content')
    <div class="m-5">
        <div class="flex justify-between items-center p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700"><i class="fa-solid fa-seedling"></i> การปลูก</h1>
            <button id="btn-add-device" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                <i class="fa-solid fa-plus"></i> เพิ่มอุปกรณ์
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                <thead>
                    <tr class="bg-green-100 text-green-600">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">รหัสอุปกรณ์</th>
                        <th class="px-4 py-2 border-b">ชื่อในการปลูก</th>
                        <th class="px-4 py-2 border-b">รายละเอียด</th>
                        <th class="px-4 py-2 border-b">วันที่เริ่ม</th>
                        <th class="px-4 py-2 border-b">วันที่สิ้นสุด</th>
                        <th class="px-4 py-2 border-b">สถานะ</th>
                        <th class="px-4 py-2 border-b">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- js -->
                </tbody>
            </table>
        </div>

        <div id="modal-planting" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h2 class="text-center text-xl font-bold mb-4">กรุณาเลือกอุปกรณ์ที่ต้องการเพิ่มในการปลูก</h2>
                <div id="modal-content" class="mb-4">
                    <div class="mb-3">
                        <label for="planting-device">อุปกรณ์ (S/N)</label>
                        <select id="planting-device" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" selected>เลือกอุปกรณ์</option>
                            @foreach ($plantingData as $item)
                                <option value="{{ $item->id }}">{{ $item->sensor_key->key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
                    url: '/user/planting',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'device_key' },
                    { data: 'name' },
                    { data: 'detail' },
                    { data: 'date_start' },
                    { data: 'date_end' },
                    { data: 'status' },
                    { data: 'action' }
                ],
                reponsive: true,
            });

            $('#table').on('click', '.btn-edit', function() {
                var deviceId = $(this).data('id');
                console.log('Edit device with ID:', deviceId);

                Swal.fire({
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
                });
            });

            $('#planting-device').select2({
                placeholder: 'เลือกอุปกรณ์',
                allowClear: true,
                width: '100%',
            });
        });

        $('#btn-add-device').on('click', function() {
            $.ajax({
                url: '/user/planting/data/' + '{{ auth()->user()->id }}',
                type: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงรายการอุปกรณ์ได้', 'error');
                }
            });
            /* $.ajax({
                url: '/user/planting/add',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ...result.value
                },
                success: function(response) {
                    Swal.fire('สำเร็จ!', 'เพิ่มอุปกรณ์เรียบร้อยแล้ว', 'success');
                    table.ajax.reload();
                },
                error: function(xhr, status, error) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถเพิ่มอุปกรณ์ได้', 'error');
                }
            }); */
        });
    </script>
@endsection
