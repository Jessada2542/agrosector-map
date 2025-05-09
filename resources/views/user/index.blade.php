@extends('layouts.app')
@section('content')
    <div class="m-5">
        <h1 class="text-2xl font-bold mb-4">ตั้งค่าผู้ใช้งาน</h1>
        <img src="{{ asset('images/avatars/'. $data->avatar .'') }}" alt="{{ $data->avatar }}" id="profile-image" class="w-32 h-32 rounded-full mb-4" />
        <button id="btn-upload" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">อัพโหลดรูปภาพ</button>
        <input type="file" id="profile-image-input" accept="image/*" class="hidden" />

        <div class="mb-4">
            <div class="md:columns-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ - นามสกุล</label>
                    <input type="text" id="name" placeholder="Name"
                        class="border border-gray-300 rounded-lg p-2 w-full mb-4" value="{{ $data->name }}" />
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">รหัสผู้ใช้งาน <span
                            class="text-red-700">*แก้ไขไม่ได้</span></label>
                    <input type="text" id="username" placeholder="Username"
                        class="border border-gray-300 rounded-lg p-2 w-full mb-4" value="{{ $data->username }}" readonly />
                </div>
            </div>

            <div class="md:columns-2">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                    <input type="text" id="email" placeholder="Email"
                        class="border border-gray-300 rounded-lg p-2 w-full mb-4" value="{{ $data->email }}" />
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">โทรศัพท์</label>
                    <input type="text" id="phone" placeholder="Phone"
                        class="border border-gray-300 rounded-lg p-2 w-full mb-4" value="{{ $data->phone }}" />
                </div>
            </div>

            <label for="address" class="block text-sm font-medium text-gray-700">ที่อยู่</label>
            <textarea id="address" class="border border-gray-300 rounded-lg p-2 w-full mb-4" rows="3" placeholder="Address">{{ $data->address }}</textarea>
        </div>

        <div class="flex justify-center">
            <button id="btn-save" class="bg-green-500 text-white px-4 py-2 rounded mb-4">บันทึก</button>
        </div>
    </div>

    <script>
        $('#btn-upload').click(function() {
            $('#profile-image-input').click();
        });

        $('#profile-image-input').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile-image').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        $('#btn-save').click(function() {
            const fileInput = $('#profile-image-input')[0];
            const file = fileInput.files[0];
            const name = $('#name').val();
            const email = $('#email').val();
            const phone = $('#phone').val();
            const address = $('#address').val();

            if (name === '' || email === '' || phone === '' || address === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }

            const formData = new FormData();
            if (file) {
                formData.append('avatar', file);
            }
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);

            $.ajax({
                url: "{{ route('user.update', $data->id) }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    </script>
@endsection
