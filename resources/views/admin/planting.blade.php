@extends('layouts.app-admin')
@section('content')
    <div class="m-5">
        <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700"><i class="fa-solid fa-seedling"></i> การปลูก</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                <thead>
                    <tr class="bg-green-100 text-green-600">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">ผู้ใช้</th>
                        <th class="px-4 py-2 border-b">รหัสอุปกรณ์</th>
                        <th class="px-4 py-2 border-b">ชื่อในการปลูก</th>
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

    <div class="m-6 p-6 rounded-xl shadow-sm border border-green-200">
        <div class="border-b border-gray-200 mb-4">
            <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                <button
                    class="tab-link text-gray-500 hover:text-green-600 hover:border-green-600 border-b-2 border-transparent py-2 px-4 text-sm font-medium">
                    ข้อมูลทั่วไป
                </button>
                <button
                    class="tab-link text-gray-500 hover:text-green-600 hover:border-green-600 border-b-2 border-transparent py-2 px-4 text-sm font-medium">
                    ค่าดิน
                </button>
                <button
                    class="tab-link text-gray-500 hover:text-green-600 hover:border-green-600 border-b-2 border-transparent py-2 px-4 text-sm font-medium">
                    รายงานการปลูก
                </button>
            </nav>
        </div>

        <div class="tab-content hidden" id="general-info">
            ข้อมูลทั่วไปของการปลูก เช่น พื้นที่ปลูก, ประเภทพืช, วันที่เริ่มปลูก, วันที่คาดว่าจะเก็บเกี่ยว
        </div>
        <div class="tab-content hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-[900px] bg-white border border-green-200 rounded-lg shadow-lg" id="table-sensor">
                    <thead>
                        <tr class="bg-green-100 text-green-600 text-sm">
                            <th class="px-4 py-2 border-b whitespace-nowrap">#</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Nitrogen (N) mg/kg</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Phosphorus (P) mg/kg</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Potassium (K) mg/kg</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">pH</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">EC</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Temperature</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Humidity</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Datetime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- js -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-content hidden" id="report-planting">
            <div class="w-full overflow-x-auto">
                <table class="min-w-[900px] bg-white border border-green-200 rounded-lg shadow-lg" id="table-planting">
                    <thead>
                        <tr class="bg-green-100 text-green-600 text-sm">
                            <th class="px-4 py-2 border-b whitespace-nowrap">#</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">รูปภาพ</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">รายละเอียด</th>
                            <th class="px-4 py-2 border-b whitespace-nowrap">Datetime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- js -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectButtons = document.querySelectorAll('.btn-select');
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            // 🔒 ล็อกปุ่ม tab ตอนเริ่มต้น
            tabLinks.forEach(tab => {
                tab.classList.add('pointer-events-none', 'opacity-50');
            });

            // ✅ เมื่อกดเลือกอุปกรณ์
            selectButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const deviceId = this.dataset.id;

                    // 🔓 ปลดล็อกปุ่ม tab
                    tabLinks.forEach(tab => {
                        tab.classList.remove('pointer-events-none', 'opacity-50');
                        tab.classList.add('hover:text-green-600', 'hover:border-green-600');
                    });

                    // ✅ ทำให้แท็บแรก active และแสดงเนื้อหา
                    tabLinks.forEach((tab, index) => {
                        tab.classList.remove('text-green-600', 'border-green-600');
                        if (index === 0) {
                            tab.classList.add('text-green-600', 'border-green-600');
                        }
                    });

                    tabContents.forEach((content, index) => {
                        content.classList.add('hidden');
                        if (index === 0) {
                            content.classList.remove('hidden');
                        }
                    });

                    // ✅ ถ้าต้องการ: ส่ง deviceId ไปโหลดข้อมูลแบบ AJAX เพิ่มเติมตรงนี้
                });
            });

            // ✅ เพิ่ม event คลิก tab เพื่อสลับเนื้อหา
            tabLinks.forEach((tab, index) => {
                tab.addEventListener('click', function() {
                    if (tab.classList.contains('pointer-events-none')) return;

                    tabLinks.forEach(t => t.classList.remove('text-green-600', 'border-green-600'));
                    tab.classList.add('text-green-600', 'border-green-600');

                    tabContents.forEach(c => c.classList.add('hidden'));
                    if (tabContents[index]) {
                        tabContents[index].classList.remove('hidden');
                    }
                });
            });
        });

        var table = $('#table').DataTable({
            ajax: {
                url: '/admin/planting',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'user_name' },
                { data: 'device_key' },
                { data: 'name' },
                { data: 'date_start' },
                { data: 'date_end' },
                { data: 'status' },
                { data: 'action' }
            ],
            responsive: true,
        });

        $(document).on('click', '.btn-info', function() {
            var deviceId = $(this).data('id');
            $('#device_id').val(deviceId);
            $('#general-info').html('<p>กำลังโหลดข้อมูล...</p>');

            $.ajax({
                url: '/admin/planting/data',
                method: 'GET',
                data: {
                    id: deviceId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        $('#general-info').html(`
                            <h2 class="text-lg font-semibold mb-4">${response.data.name}</h2>
                            <p>${response.data.detail}</p>
                        `);
                    } else {
                        Swal.fire('ผิดพลาด!', 'ไม่พบข้อมูลของอุปกรณ์นี้.', 'error');
                        return;
                    }
                },
                error: function(xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่พบอุปกรณ์ device.', 'error');
                }
            });

            // ตรวจสอบว่า DataTable ถูกสร้างไว้แล้วหรือยัง
            if ($.fn.dataTable.isDataTable('#table-sensor')) {
                // ถ้ามีแล้ว ให้ทำการล้างและโหลดใหม่
                $('#table-sensor').DataTable().destroy();
            }

            var tableSensor = $('#table-sensor').DataTable({
                ajax: {
                    url: '/dashboard',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        device_id: deviceId
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'n'
                    },
                    {
                        data: 'p'
                    },
                    {
                        data: 'k'
                    },
                    {
                        data: 'ph'
                    },
                    {
                        data: 'ec'
                    },
                    {
                        data: 'temperature'
                    },
                    {
                        data: 'humidity'
                    },
                    {
                        data: 'datetime'
                    }
                ],
                responsive: true,
                scrollX: true,
            });

            if ($.fn.dataTable.isDataTable('#table-planting')) {
                // ถ้ามีแล้ว ให้ทำการล้างและโหลดใหม่
                $('#table-planting').DataTable().destroy();
            }

            var tablePlanting = $('#table-planting').DataTable({
                ajax: {
                    url: '/dashboard/planting/report/data',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        device_id: deviceId
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'image',
                    },
                    {
                        data: 'detail'
                    },
                    {
                        data: 'datetime'
                    }
                ],
                responsive: true,
                scrollX: true,
            });
        });
    </script>
@endsection
