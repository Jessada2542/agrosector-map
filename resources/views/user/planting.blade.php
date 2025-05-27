@extends('layouts.app')
@section('content')
    <div class="m-5">
        <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
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
        });
    </script>
@endsection
