<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>เข้าสู่ระบบ</title>
    <link href="{{ asset('/images/logo.png') }}" rel="shortcut icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-green-100 to-green-300 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">เข้าสู่ระบบ Agrosector Map</h2>

        <form class="space-y-4">
            <div>
                <label class="block text-green-800 mb-1" for="username">ชื่อผู้ใช้</label>
                <input type="text" id="username"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                    placeholder="username" required />
            </div>

            <div>
                <label class="block text-green-800 mb-1" for="password">รหัสผ่าน</label>
                <input type="password" id="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                    placeholder="••••••••" required />
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox text-green-600" />
                    <span class="ml-2 text-sm text-green-800">จดจำฉัน</span>
                </label>
                <a href="#" class="text-sm text-green-600 hover:underline">ลืมรหัสผ่าน?</a>
            </div>

            <button type="button" id="btn-login"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition duration-300">
                เข้าสู่ระบบ
            </button>
        </form>

        <p class="mt-6 text-sm text-center text-green-800">
            ยังไม่มีบัญชี? <a href="#" class="text-green-600 hover:underline">สมัครสมาชิก</a>
        </p>
    </div>

    <script>
        $(document).ready(function () {
            // Set X-CSRF-TOKEN header for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Ensure jQuery marks requests as AJAX for server-side detection
            $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
                jqXHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            });
            $('#btn-login').click(function () {
                const username = $('#username').val();
                const password = $('#password').val();

                if (username === '' || password === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }

                $.ajax({
                    url: '/login',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        username: username,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'เข้าสู่ระบบสำเร็จ',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                if (response.role > 1) {
                                    window.location.href = '/admin/map';
                                } else {
                                    window.location.href = '/map';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function (jqXHR) {
                        let title = 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ';
                        if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            title = jqXHR.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: title,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
