@extends('layouts.app-admin')
@section('content')
<div class="m-5">
        <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700 mb-2"><i class="fa-solid fa-chart-line"></i> รายงาน</h1>
            <p class="text-green-900">สรุปข้อมูลภาพรวมของระบบ</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                <thead>
                    <tr class="bg-green-100 text-green-600">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">ชื่อผู้ใช้</th>
                        <th class="px-4 py-2 border-b">ชื่อในการปลูก</th>
                        <th class="px-4 py-2 border-b">N (mg/kg)</th>
                        <th class="px-4 py-2 border-b">P (mg/kg)</th>
                        <th class="px-4 py-2 border-b">K (mg/lg)</th>
                        <th class="px-4 py-2 border-b">pH</th>
                        <th class="px-4 py-2 border-b">อัพเดทล่าสุด</th>
                        <th class="px-4 py-2 border-b">วันที่เริ่ม</th>
                        <th class="px-4 py-2 border-b">วันที่สิ้นสุด</th>
                        <th class="px-4 py-2 border-b">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- js -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-info" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-lg">
            <h2 class="text-center text-2xl font-bold text-green-700 mb-6">ข้อมูล</h2>
            <div class="space-y-3 text-gray-800">
                <div><span class="font-semibold">ชื่อผู้ใช้:</span> <span id="user_name"></span></div>
                <div><span class="font-semibold">ชื่อ:</span> <span id="name"></span></div>
                <div><span class="font-semibold">พิกัด:</span> <span id="position"></span></div>
                <div><span class="font-semibold">สถานที่:</span> <span id="address"></span></div>
                <div><span class="font-semibold">รายละเอียด:</span> <span id="detail"></span></div>
                <div><span class="font-semibold">Sensor S/N:</span> <span id="sensor"></span></div>
                <div><span class="font-semibold">N:</span> <span id="n"></span></div>
                <div><span class="font-semibold">P:</span> <span id="p"></span></div>
                <div><span class="font-semibold">K:</span> <span id="k"></span></div>
                <div><span class="font-semibold">pH:</span> <span id="ph"></span></div>
                <div><span class="font-semibold">เวลาอ่านค่า:</span> <span id="datetime"></span></div>
                <div><span class="font-semibold">เริ่ม:</span> <span id="date_start"></span></div>
                <div><span class="font-semibold">สิ้นสุด:</span> <span id="date_end"></span></div>
            </div>
            <div class="flex justify-center mt-6">
                <button class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 closeModal">
                    ปิด
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        var table = $('#table').DataTable({
            ajax: {
                url: '/admin/dashboard',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'user_name' },
                { data: 'name' },
                { data: 'n' },
                { data: 'p' },
                { data: 'k' },
                { data: 'ph' },
                { data: 'datetime' },
                { data: 'date_start' },
                { data: 'date_end' },
                { data: 'action' }
            ],
            responsive: true,
        });

        $(document).on('click', '.btn-info', function() {
            const id = $(this).data('id');

            $.ajax({
                url: '/admin/dashboard/data',
                type: 'GET',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    if (response.status) {
                        const fields = ['user_name', 'name', 'position', 'address', 'detail', 'sensor', 'n', 'p', 'k', 'ph', 'datetime', 'date_start', 'date_end'];

                        fields.forEach(fieldId => {
                            const el = document.getElementById(fieldId);
                            if (el) {
                                el.textContent = response.data[fieldId] ?? '';
                            }
                        });

                        $('#modal-info').removeClass('hidden').addClass('flex');
                    } else {
                        Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงข้อมูลได้', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถดึงข้อมูลได้', 'error');
                }
            });
        });

        $('.closeModal').on('click', function() {
            $('#modal-info').addClass('hidden');
        });
    </script>
@endsection
