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
                    <div class="text-green-600 text-lg font-semibold mb-2">
                        <i class="fa-solid fa-microchip"></i> {{ $item->name }}
                    </div>

                    <div class="text-1xl font-bold text-green-800">
                        อัพเดทล่าสุด
                        {{ optional($item->latestSensor)->created_at ? $item->latestSensor->created_at->format('d-m-Y H:i') : 'ไม่มีข้อมูล Sensor' }}
                    </div>

                    @if ($item->latestSensor)
                        <p class="text-green-700 mt-2">Nitrogen (N) : {{ $item->latestSensor->n }} mg/kg</p>
                        <p class="text-green-700 mt-2">Phosphorus (P) : {{ $item->latestSensor->p }} mg/kg</p>
                        <p class="text-green-700 mt-2">Potassium (K) : {{ $item->latestSensor->k }} mg/kg</p>
                        <p class="text-green-700 mt-2">pH : {{ $item->latestSensor->ph }}</p>
                    @else
                        <p class="text-gray-500 mt-2 italic">ยังไม่มีข้อมูล Sensor</p>
                    @endif

                    <div class="flex justify-center mt-4">
                        <button class="bg-green-500 text-white px-4 py-2 rounded btn-select" data-id="{{ $item->id }}">
                            เลือก
                        </button>
                    </div>
                </div>
            @endforeach
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

            <div class="tab-content hidden" id="general-info">
                ข้อมูลทั่วไปของการปลูก เช่น พื้นที่ปลูก, ประเภทพืช, วันที่เริ่มปลูก, วันที่คาดว่าจะเก็บเกี่ยว
            </div>
            <div class="tab-content hidden">
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[900px] bg-white border border-green-200 rounded-lg shadow-lg" id="table">
                        <thead>
                            <tr class="bg-green-100 text-green-600 text-sm">
                                <th class="px-4 py-2 border-b whitespace-nowrap">#</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Nitrogen (N) mg/kg</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Phosphorus (P) mg/kg</th>
                                <th class="px-4 py-2 border-b whitespace-nowrap">Potassium (K) mg/kg</th>
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
            <div class="tab-content hidden" id="report-planting">
                <button class="bg-green-500 text-white px-4 py-2 rounded" id="btn-create-report">
                    <i class="fa-solid fa-plus"></i> สร้างรายงาน
                </button>

                @include('modal.create-report')

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
            $('#device_id').val(deviceId);
            $('#general-info').html('<p>กำลังโหลดข้อมูล...</p>');

            $.ajax({
                url: '/dashboard/data/' + deviceId,
                method: 'GET',
                data: {
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
                    /* {
                        data: 'ec'
                    },
                    {
                        data: 'temperature'
                    },
                    {
                        data: 'humidity'
                    }, */
                    {
                        data: 'datetime'
                    }
                ],
                reponsive: true,
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
                reponsive: true,
                scrollX: true,
            });
        });

        const input = document.getElementById('image');
        const previewContainer = document.getElementById('image-preview');
        let files = [];

        input.addEventListener('change', (event) => {
            const selectedFiles = Array.from(event.target.files);

            if (files.length + selectedFiles.length > 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถอัปโหลดได้',
                    text: 'อัปโหลดได้สูงสุด 3 รูปเท่านั้น',
                });
                input.value = '';
                return;
            }

            selectedFiles.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'relative w-20 h-20 rounded overflow-hidden border';

                    preview.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" />
                        <button class="absolute top-0 right-0 bg-red-600 text-white text-xs p-1 rounded-bl hover:bg-red-700">x</button>
                    `;

                    preview.querySelector('button').addEventListener('click', () => {
                        preview.remove();
                        files = files.filter(f => f !== file);
                        updateInputFiles();
                    });

                    previewContainer.appendChild(preview);
                    files.push(file);
                    updateInputFiles();
                };
                reader.readAsDataURL(file);
            });

            input.value = '';
        });

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();
            files.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        $('#btn-report-submit').on('click', function() {
            const formData = new FormData();

            files.forEach(file => formData.append('images[]', file));

            formData.append('detail', $('#detail').val());
            formData.append('use_user_sensor_id', $('#device_id').val());

            $.ajax({
                url: '/dashboard/planting/report/create',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire('สำเร็จ!', 'สร้างรายงานเรียบร้อยแล้ว.', 'success');
                        $('#modal-report').addClass('hidden');
                        $('#table-planting').DataTable().ajax.reload();
                    } else {
                        Swal.fire('ผิดพลาด!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถสร้างรายงานได้.', 'error');
                }
            });
        });

        $('#btn-create-report').on('click', function() {
            $('#modal-report').removeClass('hidden');
            $('#image-preview').empty();
            $('#image').val('');
            $('#detail').val('');
            files = [];
        });

        $('.closeModal').on('click', function() {
            $('#modal-report').addClass('hidden');
        });

        $(document).on('click', '.preview-image', function () {
            const imgSrc = $(this).attr('src');
            Swal.fire({
                title: 'Preview รูปภาพ',
                imageUrl: imgSrc,
                imageAlt: 'Preview',
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'ปิด',
                background: '#fefefe',
                width: '150px',
                padding: '1em',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        });
    </script>
@endsection
