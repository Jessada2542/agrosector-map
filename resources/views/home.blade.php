<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agrosector Map</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://api.longdo.com/map3/?key=297ba9a121afbb2c7818b0a2c497b131"></script>
    <style>
        html,
        body {
            margin: 0;
            height: 100%;
        }

        #map {
            height: 100%;
        }
    </style>
</head>

<body onload="init();">
    <div id="map"></div>

    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-center text-xl font-bold mb-4">ค้นหาพื้นที่ที่ต้องการ</h2>
            <div class="mb-4">
                <label for="province" class="block text-sm font-medium text-gray-700">เลือกจังหวัด</label>
                <select id="province" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>เลือกจังหวัด</option>
                    @foreach ($provinces as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="district" class="block text-sm font-medium text-gray-700">เลือกอำเภอ</label>
                <select id="district" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>เลือกอำเภอ</option>
                    @foreach ($districts as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="subdistrict" class="block text-sm font-medium text-gray-700">เลือกตำบล</label>
                <select id="subdistrict" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>เลือกตำบล</option>
                    @foreach ($subdistricts as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-center gap-2">
                <button id="btn-search" class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-400 text-white">ค้นหา</button>
                <button id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">ปิด</button>
            </div>
        </div>
    </div>

    <script>
        const checkLogin = '{{ Auth::check() }}';
        var map;

        function init() {
            map = new longdo.Map({
                placeholder: document.getElementById('map'),
                language: 'th',
            });

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
                button: [
                    {
                        label: '<i class="fa fa-search mr-1"></i> ค้นหาพื้นที่ได้ที่นี้',
                        type: 'search',
                    },
                    {
                        label: '<i class="fa-solid fa-location-dot"></i> แสดงพิกัดที่คลิก',
                        type: 'position',
                    },
                    {
                        label: '<i class="fa-solid fa-arrow-right-to-bracket"></i> เข้าสู่ระบบ',
                        type: 'login',
                    }
                ],
                change: menuChange
            });

            map.Ui.add(menuBarControl);
        }

        function menuChange(item) {
            if (!checkLogin && item.type !== 'login') {
                Swal.fire({
                    text: 'กรุณาเข้าสู่ระบบ',
                    icon: 'warning',
                    backdrop: false,
                });

                return;
            }

            if (item.type === 'search') {
                $('#modal').removeClass('hidden').addClass('flex');
            } else if (item.type === 'position') {
                const location = map.location();
                const marker = new longdo.Marker(location);

                map.Overlays.clear();
                map.Overlays.add(marker);

                Swal.fire({
                    title: 'พิกัดที่เลือก',
                    text: `Lat: ${location.lat.toFixed(6)}, Lon: ${location.lon.toFixed(6)}`,
                    backdrop: false,
                });
            } else if (item.type === 'login') {
                location.href = '/login';
            } else {
                Swal.fire({
                    text: 'ไม่พบข้อมูลที่เลือก',
                    icon: 'warning',
                    backdrop: false,
                });
            }
        }

        $('#closeModal').on('click', function() {
            $('#modal').removeClass('flex').addClass('hidden');
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
                            $('#district').append('<option value="' + item.id + '">' + item.name + '</option>');
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
                            $('#subdistrict').append('<option value="' + item.id + '">' + item.name + '</option>');
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
                    map.Overlays.load(object);
                } else if (districtId && !subdistrictId) {
                    object = new longdo.Overlays.Object(districtId, 'IG', {
                        lineColor: '#00ff00',
                    });
                    map.Overlays.load(object);
                } else if (subdistrictId && districtId && provinceId) {
                    object = new longdo.Overlays.Object(subdistrictId, 'IG', {
                        lineColor: '#ff0000',
                    });
                    map.Overlays.load(object);
                }
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

</body>

</html>
