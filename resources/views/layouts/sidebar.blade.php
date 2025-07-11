<!-- ✅ MOBILE NAVBAR -->
<nav class="lg:hidden fixed top-0 left-0 w-full bg-gradient-to-r from-green-200 to-green-400 text-green-900 flex items-center justify-between px-4 py-3 shadow z-30">
    <div>
        <img src="{{ asset('/images/logo_sidebar.jpg') }}" alt="logo" width="150">
    </div>
    <button id="open-sidebar" class="text-3xl text-green-800">☰</button>
</nav>

<!-- ✅ OVERLAY SIDEBAR (mobile only) -->
<aside id="mobile-sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-green-200 to-green-400 text-green-900 transform -translate-x-full transition-transform duration-300 ease-in-out z-40 lg:hidden shadow-lg">

    <!-- Close button -->
    <button id="close-sidebar" class="absolute top-4 right-4 text-3xl text-green-800">×</button>

    <!-- Sidebar content -->
    <div class="flex justify-center my-5">
        <img src="{{ asset('/images/logo_sidebar.jpg') }}" alt="logo" width="180">
    </div>
    <nav class="flex flex-col space-y-2 px-4">
        <a href="{{ route('map.index') }}" class="{{ (isset($sideAtive) && $sideAtive == 'map') ? 'side-active' : '' }} px-4 py-2 hover:bg-green-100 rounded">
            <i class="fa-solid fa-map"></i> แผนที่
        </a>
        <a href="{{ route('dashboard.index') }}" class="{{ (isset($sideAtive) && $sideAtive == 'dashboard') ? 'side-active' : '' }} px-4 py-2 hover:bg-green-100 rounded">
            <i class="fa-solid fa-chart-line"></i> รายงาน
        </a>
        <a href="{{ route('user.planting') }}" class="{{ (isset($sideAtive) && $sideAtive == 'planting') ? 'side-active' : '' }} px-4 py-2 hover:bg-green-100 rounded">
            <i class="fa-solid fa-seedling"></i> การปลูก
        </a>
        <a href="{{ route('user.index') }}" class="{{ (isset($sideAtive) && $sideAtive == 'user') ? 'side-active' : '' }} px-4 py-2 hover:bg-green-100 rounded">
            <i class="fa-solid fa-user"></i> บัญชีผู้ใช้
        </a>
        <a href="{{ route('setting.index') }}" class="{{ (isset($sideAtive) && $sideAtive == 'setting') ? 'side-active' : '' }} px-4 py-2 hover:bg-green-100 rounded">
            <i class="fa-solid fa-gear"></i> การตั้งค่า
        </a>
        <a href="{{ route('logout') }}" class="px-4 py-2 bg-red-200 hover:bg-red-300 text-red-800 rounded mt-4">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ
        </a>
    </nav>
</aside>

<!-- ✅ DESKTOP SIDEBAR -->
<aside class="hidden lg:flex flex-col w-64 h-full fixed top-0 left-0 bg-gradient-to-b from-green-200 to-green-400 text-green-900 p-4 space-y-4 shadow-lg z-20">
    <div class="flex justify-center my-3">
        <img src="{{ asset('/images/logo_sidebar.jpg') }}" alt="logo" width="250">
    </div>
    <nav class="flex flex-col space-y-2">
        <a href="{{ route('map.index') }}" class="hover:bg-green-100 px-4 py-2 rounded {{ (isset($sideAtive) && $sideAtive == 'map') ? 'side-active' : '' }}">
            <i class="fa-solid fa-map"></i> Map
        </a>
        <a href="{{ route('dashboard.index') }}" class="hover:bg-green-100 px-4 py-2 rounded {{ (isset($sideAtive) && $sideAtive == 'dashboard') ? 'side-active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> รายงาน
        </a>
        <a href="{{ route('user.planting') }}" class="hover:bg-green-100 px-4 py-2 rounded {{ (isset($sideAtive) && $sideAtive == 'planting') ? 'side-active' : '' }}">
            <i class="fa-solid fa-seedling"></i> การปลูก
        </a>
        <a href="{{ route('user.index') }}" class="hover:bg-green-100 px-4 py-2 rounded {{ (isset($sideAtive) && $sideAtive == 'user') ? 'side-active' : '' }}">
            <i class="fa-solid fa-user"></i> บัญชีผู้ใช้
        </a>
        <a href="{{ route('setting.index') }}" class="hover:bg-green-100 px-4 py-2 rounded {{ (isset($sideAtive) && $sideAtive == 'setting') ? 'side-active' : '' }}">
            <i class="fa-solid fa-gear"></i> การตั้งค่า
        </a>
        <a href="{{ route('logout') }}" class="mt-auto hover:bg-red-100 bg-red-200 px-4 py-2 rounded transition text-red-800">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ
        </a>
    </nav>
</aside>

<script>
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('mobile-sidebar');

    openBtn.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
    });

    closeBtn.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
    });
</script>
