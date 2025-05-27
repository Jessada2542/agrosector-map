@extends('layouts.app')
@section('content')
    <div class="m-5">
        <div class="p-6 rounded-xl shadow-sm border border-green-200 mb-6">
            <h1 class="text-2xl font-bold text-green-700 mb-2"><i class="fa-solid fa-chart-line"></i> รายงาน</h1>
            <p class="text-green-900">สรุปข้อมูลภาพรวมของระบบ</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($sensor as $item)
                <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                    <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> {{ $item->name }}</div>
                    <div class="text-1xl font-bold text-green-800">อัพเดทล่าสุด {{ $item->latestSensor->created_at ? $item->latestSensor->created_at->format('Y-m-d H:i') : 'ไม่มีข้อมูล Sensor' }}</div>
                    <p class="text-green-700 mt-2">Nitrogen (N) : {{ $item->latestSensor->n ? $item->latestSensor->n . ' mg/kg' : '' }}</p>
                    <p class="text-green-700 mt-2">Phosphorus (P) : {{ $item->latestSensor->p ? $item->latestSensor->p . ' mg/kg' : '' }}</p>
                    <p class="text-green-700 mt-2">Potassium (K) : {{ $item->latestSensor->k ? $item->latestSensor->k  . ' mg/kg' : '' }}</p>
                    <p class="text-green-700 mt-2">pH : {{ $item->latestSensor->ph ?? '' }}</p>
                    {{-- <p class="text-green-700 mt-2">ec : {{ $sensor->ec }}</p>
                    <p class="text-green-700 mt-2">Temperature : {{ $sensor->temperature }}</p>
                    <p class="text-green-700 mt-2">Humidity : {{ $sensor->humidity }}</p> --}}
                    <div class="flex justify-center">
                        <button class="bg-green-500 text-white px-4 py-2 rounded btn-select" data-id="{{ $item->id }}">เลือก</button>
                    </div>
                </div>
            @endforeach

            {{-- <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> อุปกรณ์ (2)
                </div>
                <div class="text-1xl font-bold text-green-800">อัพเดทล่าสุด Datetime</div>
                <p class="text-green-700 mt-2">Nitrogen (N) : value</p>
                <p class="text-green-700 mt-2">Phosphorus (P) : value</p>
                <p class="text-green-700 mt-2">Potassium (K) : value</p>
                <p class="text-green-700 mt-2">pH : value</p>
                <p class="text-green-700 mt-2">ec : value</p>
                <p class="text-green-700 mt-2">Temperature : value</p>
                <p class="text-green-700 mt-2">Humidity : value</p>
                <div class="flex justify-center">
                    <button class="bg-green-500 text-white px-4 py-2 rounded btn-select" data-id="2">เลือก</button>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2"><i class="fa-solid fa-microchip"></i> อุปกรณ์ (3)
                </div>
                <div class="text-1xl font-bold text-green-800">อัพเดทล่าสุด Datetime</div>
                <p class="text-green-700 mt-2">Nitrogen (N) : value</p>
                <p class="text-green-700 mt-2">Phosphorus (P) : value</p>
                <p class="text-green-700 mt-2">Potassium (K) : value</p>
                <p class="text-green-700 mt-2">pH : value</p>
                <p class="text-green-700 mt-2">ec : value</p>
                <p class="text-green-700 mt-2">Temperature : value</p>
                <p class="text-green-700 mt-2">Humidity : value</p>
                <div class="flex justify-center">
                    <button class="bg-green-500 text-white px-4 py-2 rounded btn-select" data-id="3">เลือก</button>
                </div>
            </div> --}}
        </div>

        <div class="mt-6 p-6 rounded-xl shadow-sm border border-green-200 mb-6">
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

            <div class="tab-content hidden">
                ข้อมูลทั่วไปของการปลูก เช่น พื้นที่ปลูก, ประเภทพืช, วันที่เริ่มปลูก, วันที่คาดว่าจะเก็บเกี่ยว
            </div>
            <div class="tab-content hidden">
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[900px] bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                        <thead>
                            <tr class="bg-green-100 text-green-600 text-sm">
                                <th class="px-4 py-2 border-b whitespace-nowrap">#</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Nitrogen (N)</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Phosphorus (P)</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Potassium (K)</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">pH</th>
                                {{-- <th class="px-4 py-2 border-b whitespace-nowrap">EC</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Temperature</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Humidity</th> --}}
                                <th class="px-4 py-2 border-b whitespace-nowrap">Datetime</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- js -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-content hidden">
                อัพโหลดรูปได้, เขียนข้อความรายงานการปลูกได้
            </div>
        </div>




        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2">👥 ผู้ใช้งาน</div>
                <div class="text-3xl font-bold text-green-800">124 คน</div>
                <p class="text-green-700 mt-2">เพิ่มขึ้น 12% จากเมื่อวาน</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2">💰 รายได้</div>
                <div class="text-3xl font-bold text-green-800">฿5,420</div>
                <p class="text-green-700 mt-2">รายได้วันนี้</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-green-200 shadow hover:shadow-md transition">
                <div class="text-green-600 text-lg font-semibold mb-2">🔔 การแจ้งเตือน</div>
                <div class="text-3xl font-bold text-green-800">3 รายการ</div>
                <p class="text-green-700 mt-2">แจ้งเตือนที่ยังไม่อ่าน</p>
            </div>
        </div> --}}
    </div>
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
                    console.log('อุปกรณ์ที่เลือก:', deviceId);

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

        $('.btn-select').click(function() {
            var deviceId = $(this).data('id');
            console.log('Selected device ID:', deviceId);

            // ตรวจสอบว่า DataTable ถูกสร้างไว้แล้วหรือยัง
            if ($.fn.dataTable.isDataTable('#table')) {
                // ถ้ามีแล้ว ให้ทำการล้างและโหลดใหม่
                $('#table').DataTable().destroy();
            }

            var table = $('#table').DataTable({
                ajax: {
                    url: '/dashboard',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        //device_id: deviceId
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
                reponsive: true,
            });
        });
    </script>
@endsection
