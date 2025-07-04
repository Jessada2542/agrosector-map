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

        document.addEventListener('DOMContentLoaded', function () {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            // 🔒 ล็อก tab ตอนโหลดหน้า
            tabLinks.forEach(tab => {
                tab.classList.add('pointer-events-none', 'opacity-50');
            });

            // ✅ Event Delegation: จับคลิกปุ่ม .btn-info ไม่ว่าจะมาจากไหน
            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('btn-info')) {
                    const deviceId = e.target.dataset.id;

                    // 🔓 ปลดล็อก tab ทุกปุ่ม
                    tabLinks.forEach(tab => {
                        tab.classList.remove('pointer-events-none', 'opacity-50');
                        tab.classList.add('hover:text-green-600', 'hover:border-green-600');
                    });

                    // ✅ ทำให้แท็บแรก active
                    tabLinks.forEach((tab, index) => {
                        tab.classList.remove('text-green-600', 'border-green-600');
                        if (index === 0) {
                            tab.classList.add('text-green-600', 'border-green-600');
                        }
                    });

                    // ✅ แสดงเนื้อหาแท็บแรก
                    tabContents.forEach((content, index) => {
                        content.classList.add('hidden');
                        if (index === 0) {
                            content.classList.remove('hidden');
                        }
                    });

                    // ✅ โหลดข้อมูลเพิ่มเติมได้ตรงนี้ (เช่นผ่าน AJAX)
                    console.log('เลือกอุปกรณ์:', deviceId);
                    // Example:
                    // fetch(`/api/device/${deviceId}`).then(...);
                }
            });

            // ✅ คลิกเปลี่ยนแท็บ
            tabLinks.forEach((tab, index) => {
                tab.addEventListener('click', function () {
                    if (tab.classList.contains('pointer-events-none')) return;

                    // ลบ active เดิม
                    tabLinks.forEach(t => t.classList.remove('text-green-600', 'border-green-600'));
                    tab.classList.add('text-green-600', 'border-green-600');

                    // ซ่อน/โชว์เนื้อหา
                    tabContents.forEach(c => c.classList.add('hidden'));
                    if (tabContents[index]) {
                        tabContents[index].classList.remove('hidden');
                    }
                });
            });
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
                    console.log(response);

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

            if ($.fn.dataTable.isDataTable('#table-sensor')) {
                $('#table-sensor').DataTable().destroy();
            }

            var tableSensor = $('#table-sensor').DataTable({
                ajax: {
                    url: '/admin/planting/data/sensor',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: deviceId
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
                $('#table-planting').DataTable().destroy();
            }

            var tablePlanting = $('#table-planting').DataTable({
                ajax: {
                    url: '/admin/planting/data/report',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: deviceId
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

        $(document).on('click', '.preview-image', function () {
            const imgSrc = $(this).attr('src');
            Swal.fire({
                imageUrl: imgSrc,
                imageAlt: 'Preview',
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                background: '#fefefe',
                width: '350px',
                padding: '1em',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        });
    </script>
@endsection
