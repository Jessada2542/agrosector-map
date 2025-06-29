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
            reponsive: true,
        });

        $(document).on('click', '.btn-info', function() {
            const id = $(this).data('id');
            console.log(id);

        });
    </script>
@endsection
