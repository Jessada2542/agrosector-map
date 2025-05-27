@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://api.longdo.com/map3/?key=297ba9a121afbb2c7818b0a2c497b131"></script>
<style>
    #map {
        height: 100vh;
        width: 100%;
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
            /* $.ajax({
                url: `/api/sensor/${id}`, // หรือ route ใน Laravel เช่น route('sensor.show', id)
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#sensor-content').html(`
                <p><strong>ชื่อ:</strong> ${data.name}</p>
                <p><strong>ค่า:</strong> ${data.value}</p>
            `);
                },
                error: function(xhr, status, error) {
                    $('#sensor-content').html('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    console.error('AJAX Error:', status, error);
                }
            }); */
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
                            console.log(response.data);

                            const sensorData = [{
                                    id: 1,
                                    name: 'Sensor A',
                                    lon: 101.129354,
                                    lat: 16.440727
                                },
                                {
                                    id: 2,
                                    name: 'Sensor B',
                                    lon: 101.129354,
                                    lat: 15.440727
                                },
                                {
                                    id: 3,
                                    name: 'Sensor C',
                                    lon: 101.129354,
                                    lat: 14.440727
                                }
                            ];
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
