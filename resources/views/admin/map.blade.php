@extends('layouts.app-admin')
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

    @include('modal.search')
    @include('modal.mark-sensor')

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

                $.ajax({
                    url: '/api/sensor/marker',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
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
                openModal(id);
            });
        }

        function openModal(id) {
            // เปิด modal
            $('#modal-sensor').removeClass('hidden');

            // เก็บ chart instances ไว้ที่ object
            window.sensorCharts = window.sensorCharts || {};

            $.ajax({
                url: `/api/sensor/data/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        dayjs.extend(dayjs_plugin_utc);
                        dayjs.extend(dayjs_plugin_timezone);

                        const sensor = response.data;
                        $('#user-name').text(sensor.user ? sensor.user.name : '');
                        $('#sensor-position').text(sensor.user_sensor ? sensor.user_sensor.lat + ', ' + sensor.user_sensor.lon : '');
                        const subdistrict = sensor.user_sensor?.subdistrict ? sensor.user_sensor.subdistrict.subdistrict_name_th : '';
                        const district = sensor.user_sensor?.district ? sensor.user_sensor.district.district_name_th : '';
                        const province = sensor.user_sensor?.province ? sensor.user_sensor.province.province_name_th : '';
                        const province_code = sensor.user_sensor?.province ? sensor.user_sensor.province.province_code : '';
                        $('#sensor-address').text('ตำบล' + subdistrict + ' อำเภอ' + district + ' จังหวัด' + province + ' ' + province_code + '000');
                        $('#sensor-name').text(sensor.name ? sensor.name : '');
                        $('#sensor-date').text(sensor.start_date ? dayjs.utc(sensor.start_date).tz('Asia/Bangkok').format('DD/MM/YYYY') : '');
                        $('#sensor-detail').text(sensor.detail ? sensor.detail : '');
                        $('#sensor-update').text(sensor.latest_sensor?.created_at ? dayjs.utc(sensor.latest_sensor.created_at).tz('Asia/Bangkok').format('DD/MM/YYYY HH:mm') : '');
                        if (sensor.latest_sensor && sensor.latest_sensor.n) {
                            $('#box-n').removeClass('hidden');
                            $('#sensor-n').text(sensor.latest_sensor.n);
                        } else {
                            $('#box-n').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.p) {
                            $('#box-p').removeClass('hidden');
                            $('#sensor-p').text(sensor.latest_sensor.p);
                        } else {
                            $('#box-p').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.k) {
                            $('#box-k').removeClass('hidden');
                            $('#sensor-k').text(sensor.latest_sensor.k);
                        } else {
                            $('#box-k').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.ph) {
                            $('#box-ph').removeClass('hidden');
                            $('#sensor-ph').text(sensor.latest_sensor.ph);
                        } else {
                            $('#box-ph').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.humidity) {
                            $('#box-s-h').removeClass('hidden');
                            $('#sensor-soil-humidity').text(sensor.latest_sensor.humidity);
                        } else {
                            $('#box-s-h').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.temperature) {
                            $('#box-s-t').removeClass('hidden');
                            $('#sensor-soil-temperature').text(sensor.latest_sensor.temperature);
                        } else {
                            $('#box-s-t').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.ec) {
                            $('#box-ec').removeClass('hidden');
                            $('#sensor-ec').text(sensor.latest_sensor.ec);
                        } else {
                            $('#box-ec').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.light) {
                            $('#box-light').removeClass('hidden');
                            $('#sensor-light').text(sensor.latest_sensor.light);
                        } else {
                            $('#box-light').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.air_humidity) {
                            $('#box-a-h').removeClass('hidden');
                            $('#sensor-air-humidity').text(sensor.latest_sensor.air_humidity);
                        } else {
                            $('#box-a-h').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.air_temperature) {
                            $('#box-a-t').removeClass('hidden');
                            $('#sensor-air-temperature').text(sensor.latest_sensor.air_temperature);
                        } else {
                            $('#box-a-t').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.co2) {
                            $('#box-co2').removeClass('hidden');
                            $('#sensor-co2').text(sensor.latest_sensor.co2);
                        } else {
                            $('#box-co2').addClass('hidden');
                        }

                        if (sensor.latest_sensor && sensor.latest_sensor.nh3) {
                            $('#box-nh3').removeClass('hidden');
                            $('#sensor-nh3').text(sensor.latest_sensor.nh3);
                        } else {
                            $('#box-nh3').addClass('hidden');
                        }

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
                            },
                            {
                                label: 'Soil Humidity',
                                data: sensor.sensors.map(d => d.humidity),
                                borderColor: 'rgba(0, 191, 255, 1)',
                                backgroundColor: 'rgba(0, 191, 255, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'Soil Temperature',
                                data: sensor.sensors.map(d => d.temperature),
                                borderColor: 'rgba(255, 140, 0, 1)',
                                backgroundColor: 'rgba(255, 140, 0, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'EC',
                                data: sensor.sensors.map(d => d.ec),
                                borderColor: 'rgba(255, 128, 0, 1)',
                                backgroundColor: 'rgba(255, 128, 0, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'Light',
                                data: sensor.sensors.map(d => d.light),
                                borderColor: 'rgba(255, 215, 0, 1)',
                                backgroundColor: 'rgba(255, 215, 0, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'Air Humidity',
                                data: sensor.sensors.map(d => d.air_humidity),
                                borderColor: 'rgba(30, 144, 255, 1)',
                                backgroundColor: 'rgba(30, 144, 255, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'Air Temperature',
                                data: sensor.sensors.map(d => d.air_temperature),
                                borderColor: 'rgba(255, 99, 71, 1)',
                                backgroundColor: 'rgba(255, 99, 71, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'CO2',
                                data: sensor.sensors.map(d => d.co2),
                                borderColor: 'rgba(0, 128, 0, 1)',
                                backgroundColor: 'rgba(0, 128, 0, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            },
                            {
                                label: 'NH3',
                                data: sensor.sensors.map(d => d.nh3),
                                borderColor: 'rgba(148, 0, 211, 1)',
                                backgroundColor: 'rgba(148, 0, 211, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 4
                            }
                        ];

                        const typeLabelMap = {
                            n: 'Nitrogen (N)',
                            p: 'Phosphorus (P)',
                            k: 'Potassium (K)',
                            ph: 'pH',
                            's-h': 'Soil Humidity',
                            's-t': 'Soil Temperature',
                            ec: 'EC',
                            light: 'Light',
                            'a-h': 'Air Humidity',
                            'a-t': 'Air Temperature',
                            co2: 'CO2',
                            nh3: 'NH3'
                        };

                        // ทำลูปสร้างกราฟครบทุกตัว
                        Object.keys(typeLabelMap).forEach((type) => {
                            if (window.sensorCharts[type]) {
                                window.sensorCharts[type].destroy();
                            }
                            const ctx = document.getElementById(`grap-sensor-${type}`).getContext('2d');
                            window.sensorCharts[type] = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: datasets.filter(ds => ds.label === typeLabelMap[type]),
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { display: true, position: 'top' }
                                    },
                                    scales: {
                                        y: { beginAtZero: false }
                                    }
                                }
                            });
                        });
                    } else {
                        $('#sensor-content').html('<p class="text-red-500">ไม่พบข้อมูลสำหรับ Sensor นี้</p>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#sensor-content').html('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    console.error('AJAX Error!', status, error);
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
