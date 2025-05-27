@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://api.longdo.com/map3/?key=297ba9a121afbb2c7818b0a2c497b131"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/utc.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/timezone.js"></script>
<style>
    #map {
        height: 100vh;
        width: 100%;
    }

    canvas .lineChart {
        max-width: 600px;
        margin: 50px auto;
        display: block;
    }
</style>
@section('content')
    <div id="map"></div>

    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-center text-xl font-bold mb-4">ค้นหาพื้นที่ที่ต้องการ</h2>
            <div class="mb-4">
                <label for="province" class="block text-sm font-medium text-gray-700">เลือกจังหวัด</label>
                <select id="province"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>เลือกจังหวัด</option>
                    @foreach ($provinces as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="district" class="block text-sm font-medium text-gray-700">เลือกอำเภอ</label>
                <select id="district"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>เลือกอำเภอ</option>
                    @foreach ($districts as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="subdistrict" class="block text-sm font-medium text-gray-700">เลือกตำบล</label>
                <select id="subdistrict"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>เลือกตำบล</option>
                    @foreach ($subdistricts as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-center gap-2">
                <button id="btn-search" class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-400 text-white">ค้นหา</button>
                <button class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 closeModal">ปิด</button>
            </div>
        </div>
    </div>

    <!-- Modal แบบชิดขวา -->
    <div id="modal-sensor" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-end hidden">
        <div class="relative bg-white w-[400px] h-full shadow-lg flex flex-col">
            <div class="flex items-center justify-between p-6 border-b">
                <h2 class="text-xl font-semibold">สถิติ</h2>
                <button onclick="document.getElementById('modal-sensor').classList.add('hidden')"
                    class="text-gray-500 hover:text-red-600 text-2xl font-bold">
                    &times;
                </button>
            </div>
            <div class="p-6 overflow-y-auto" id="sensor-content">
                <strong id="sensor-name">ชื่อ sensor</strong>
                <p class="text-gray-700" id="sensor-n">Nitrogen (N)</p>
                <p class="text-gray-700" id="sensor-p">Phosphorus (P)</p>
                <p class="text-gray-700" id="sensor-k">Potassium (K)</p>
                <p class="text-gray-700" id="sensor-ph">pH</p>
                <canvas id="grap-sensor-n" class="lineChart"></canvas>
                <canvas id="grap-sensor-p" class="lineChart"></canvas>
                <canvas id="grap-sensor-k" class="lineChart"></canvas>
                <canvas id="grap-sensor-ph" class="lineChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            init();
        });

        var map;

        function init() {
            map = new longdo.Map({
                placeholder: document.getElementById('map'),
                language: 'th',
            });
            map.Event.bind('ready', function() {
                console.log('Map is ready!');
                map.Ui.Toolbar.visible(false);
                map.Ui.Terrain.visible(false);
                map.Ui.LayerSelector.visible(false);
                /* map.Ui.Crosshair.visible(false); */
                map.Ui.Scale.visible(false);

                map.bound({
                    minLon: 97.0,
                    minLat: 5.5,
                    maxLon: 105.0,
                    maxLat: 20.5
                });

                var menuBarControl = new longdo.MenuBar({
                    button: [{
                            label: '<i class="fa fa-search mr-1"></i> ค้นหาพื้นที่ได้ที่นี้',
                            type: 'search',
                        },
                        {
                            label: '<i class="fa-solid fa-location-dot"></i> แสดงพิกัดที่คลิก',
                            type: 'position',
                        }
                    ],
                    change: menuChange
                });

                map.Ui.add(menuBarControl);
            });
        }

        function markSensor(sensorData) {
            // เพิ่ม marker พร้อม metadata
            sensorData.forEach(sensor => {
                const marker = new longdo.Marker({
                    lat: sensor.lat,
                    lon: sensor.lon
                }, {
                    title: sensor.name,
                    metadata: {
                        id: sensor.id
                    }
                });

                map.Overlays.add(marker);
            });

            map.Event.bind('overlayClick', function(overlay) {
                const id = overlay._geojson?.properties?.metadata?.id;
                console.log('ID ที่คลิก:', id);
                openModal(id);
            });
        }

        function openModal(id) {
            $('#modal-sensor').removeClass('hidden');

            $.ajax({
                url: `/api/sensor/data/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Sensor Data:', response);

                    if (response.status) {
                        const sensor = response.data;
                        $('#sensor-name').text(sensor.name);
                        $('#sensor-n').text(`Nitrogen (N): ${sensor.latest_sensor.n} mg/kg`);
                        $('#sensor-p').text(`Phosphorus (P): ${sensor.latest_sensor.p} mg/kg`);
                        $('#sensor-k').text(`Potassium (K): ${sensor.latest_sensor.k} mg/kg`);
                        $('#sensor-ph').text(`pH: ${sensor.latest_sensor.ph}`);

                        // สร้างกราฟ
                        /* const ctx = document.getElementById('myLineChart').getContext('2d');
                        const myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.'], // แกน X
                                datasets: [{
                                    label: 'Nitrogen (N)',         // คำอธิบายเส้น
                                    data: [25, 27, 30, 32, 29],      // แกน Y
                                    borderColor: 'rgba(75, 192, 192, 1)', // สีเส้น
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // สีพื้นที่ใต้เส้น
                                    borderWidth: 2,
                                    tension: 0.4, // ความโค้งของเส้น
                                    fill: true,
                                    pointRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: false
                                    }
                                }
                            }
                        }); */

                        dayjs.extend(dayjs_plugin_utc);
                        dayjs.extend(dayjs_plugin_timezone);

                        const labels = sensor.sensors.map(d =>
                            dayjs.utc(d.created_at).tz('Asia/Bangkok').format('DD-MM-YYYY HH:mm')
                        );

                        const datasets = [
                            {
                                label: 'Nitrogen (N)',
                                data: sensor.sensors.map(d => d.n),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'Phosphorus (P)',
                                data: sensor.sensors.map(d => d.p),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'Potassium (K)',
                                data: sensor.sensors.map(d => d.k),
                                borderColor: 'rgba(255, 206, 86, 1)',
                                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'pH',
                                data: sensor.sensors.map(d => d.ph),
                                borderColor: 'rgba(153, 102, 255, 1)',
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            }
                        ];

                        // แมป label ของกราฟแต่ละตัว
                        const typeLabelMap = {
                            n: 'Nitrogen (N)',
                            p: 'Phosphorus (P)',
                            k: 'Potassium (K)',
                            ph: 'pH'
                        };

                        // สร้างกราฟแต่ละตัว
                        ['n', 'p', 'k', 'ph'].forEach((type) => {
                            const ctx = document.getElementById(`grap-sensor-${type}`).getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                labels: labels,
                                datasets: datasets.filter(ds => ds.label === typeLabelMap[type])
                                },
                                options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                    display: true,
                                    position: 'top'
                                    }
                                },
                                scales: {
                                    y: {
                                    beginAtZero: false
                                    }
                                }
                                }
                            });
                        });
                    } else {
                        $('#sensor-content').html('<p class="text-red-500">ไม่พบข้อมูลสำหรับ Sensor นี้</p>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#sensor-content').html('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    console.error('AJAX Error:', status, error);
                }
            });
        }

        function menuChange(item) {
            if (item.type === 'search') {
                $('#modal').removeClass('hidden').addClass('flex');
            } else if (item.type === 'position') {
                const location = map.location();
                const marker = new longdo.Marker(location);

                map.Overlays.add(marker);

                Swal.fire({
                    title: 'พิกัดที่เลือก',
                    text: `Lat: ${location.lat.toFixed(6)}, Lon: ${location.lon.toFixed(6)}`,
                    backdrop: false,
                });
            } else {
                Swal.fire({
                    text: 'ไม่พบข้อมูลที่เลือก',
                    icon: 'warning',
                    backdrop: false,
                });
            }
        }

        $('.closeModal').on('click', function() {
            $('#modal').removeClass('flex').addClass('hidden');
            $('#modal-sensor').addClass('hidden');
        });

        // ปิด modal เมื่อคลิกพื้นหลัง
        $('#modal').on('click', function(e) {
            if (e.target.id === 'modal') {
                $(this).removeClass('flex').addClass('hidden');
            }
        });

        $('#province').select2({
            placeholder: 'เลือกจังหวัด',
            allowClear: true,
            width: '100%',
        });

        $('#district').select2({
            placeholder: 'เลือกอำเภอ',
            allowClear: true,
            width: '100%',
        });

        $('#subdistrict').select2({
            placeholder: 'เลือกตำบล',
            allowClear: true,
            width: '100%',
        });

        $('#province').on('change', function() {
            var provinceId = $(this).val();
            $('#district').empty().append('<option value="" disabled selected>เลือกอำเภอ</option>');
            $('#subdistrict').empty().append('<option value="" disabled selected>เลือกตำบล</option>');
            if (provinceId) {
                $.ajax({
                    url: '/api/districts',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        province_code: provinceId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#district').append('<option value="' + item.id + '">' + item
                                .name + '</option>');
                        });
                    }
                });
            }
        });

        $('#district').on('change', function() {
            var districtId = $(this).val();
            $('#subdistrict').empty().append('<option value="" disabled selected>เลือกตำบล</option>');
            if (districtId) {
                $.ajax({
                    url: '/api/subdistricts',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        district_code: districtId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#subdistrict').append('<option value="' + item.id + '">' + item
                                .name + '</option>');
                        });
                    }
                });
            }
        });

        let object;

        $('#btn-search').on('click', function() {
            const provinceId = $('#province').val();
            const districtId = $('#district').val();
            const subdistrictId = $('#subdistrict').val();

            if (provinceId || districtId || subdistrictId) {
                $('#modal').removeClass('flex').addClass('hidden');

                if (object) {
                    map.Overlays.unload(object);
                }

                if (provinceId && !districtId && !subdistrictId) {
                    object = new longdo.Overlays.Object(provinceId, 'IG', {
                        lineColor: '#0054ff',
                    });
                } else if (districtId && !subdistrictId) {
                    object = new longdo.Overlays.Object(districtId, 'IG', {
                        lineColor: '#00ff00',
                    });
                } else if (subdistrictId && districtId && provinceId) {
                    object = new longdo.Overlays.Object(subdistrictId, 'IG', {
                        lineColor: '#ff0000',
                    });
                }

                map.Overlays.clear();
                map.Overlays.load(object);

                $.ajax({
                    url: '/api/sensor/marker',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        province_code: provinceId,
                        district_code: districtId,
                        subdistrict_code: subdistrictId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            markSensor(response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถค้นหาพื้นที่ได้ กรุณาลองใหม่อีกครั้ง',
                            icon: 'error',
                            backdrop: false,
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือก',
                    text: 'จังหวัด, อำเภอ หรือ ตำบล',
                    showConfirmButton: true,
                    backdrop: false,
                });
            }
        });
    </script>
@endsection
