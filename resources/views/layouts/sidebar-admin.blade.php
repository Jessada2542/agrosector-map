<style>
    .side-active {
        --tw-bg-opacity: 1;
        background-color: rgb(220 252 231 / var(--tw-bg-opacity, 1)) !important;
        color: rgb(21 128 61); /* text-green-800 */
        font-weight: 600;
        border-left: 4px solid #22c55e; /* green-500 */
    }
</style>
<!-- ✅ MOBILE NAVBAR -->
<nav class="lg:hidden fixed top-0 left-0 w-full bg-gradient-to-r from-green-500 via-emerald-400 to-cyan-500 text-white flex items-center justify-between px-4 py-3 shadow z-30">
    <div class="text-xl font-bold">Agrosector Back</div>
    <button id="open-sidebar" class="text-3xl text-white">☰</button>
</nav>

<!-- ✅ OVERLAY SIDEBAR (mobile only) -->
<aside id="mobile-sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-green-500 via-emerald-400 to-cyan-400 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-40 lg:hidden shadow-lg">

    <button id="close-sidebar" class="absolute top-4 right-4 text-3xl text-white">×</button>

    <div class="text-center text-2xl font-bold mt-12 mb-6">Agrosector Back</div>
    <nav class="flex flex-col space-y-2 px-4">
        <a href="{{ route('admin.index') }}" class="{{ (isset($sideActive) && $sideActive == 'map') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-map"></i> แผนที่
        </a>
        <a href="{{ route('admin.dashboard') }}" class="{{ (isset($sideActive) && $sideActive == 'dashboard') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-chart-line"></i> รายงาน
        </a>
        <a href="{{ route('admin.users') }}" class="{{ (isset($sideActive) && $sideActive == 'users') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-users"></i> ผู้ใช้งานระบบ
        </a>
        <a href="{{ route('admin.planting') }}" class="{{ (isset($sideActive) && $sideActive == 'planting') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-seedling"></i> การปลูก
        </a>
        <a href="{{ route('setting.index') }}" class="{{ (isset($sideActive) && $sideActive == 'setting') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-gear"></i> จัดการเช็นเชอร์
        </a>
        <a href="{{ route('admin.users.profile') }}" class="{{ (isset($sideActive) && $sideActive == 'user') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-users"></i> บัญชีผู้ใช้
        </a>
        <a href="{{ route('logout') }}" class="px-4 py-2 bg-red-200 hover:bg-red-300 text-red-800 rounded mt-4">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> ออกจากระบบ
        </a>
    </nav>
</aside>

<!-- ✅ DESKTOP SIDEBAR -->
<aside class="hidden lg:flex flex-col w-64 h-full fixed top-0 left-0 bg-gradient-to-b from-green-500 via-emerald-400 to-cyan-400 text-white p-4 space-y-4 shadow-lg z-20">
    <div class="text-center text-2xl font-bold">Agrosector Back</div>
    <nav class="flex flex-col space-y-2">
        <a href="{{ route('admin.index') }}" class="hover:bg-white hover:text-green-800 px-4 py-2 rounded transition {{ (isset($sideActive) && $sideActive == 'map') ? 'side-active' : '' }}">
            <i class="fa-solid fa-map"></i> Map
        </a>
        <a href="{{ route('admin.dashboard') }}" class="hover:bg-white hover:text-green-800 px-4 py-2 rounded transition {{ (isset($sideActive) && $sideActive == 'dashboard') ? 'side-active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> รายงาน
        </a>
        <a href="{{ route('admin.users') }}" class="hover:bg-white hover:text-green-800 px-4 py-2 rounded transition {{ (isset($sideActive) && $sideActive == 'users') ? 'side-active' : '' }}">
            <i class="fa-solid fa-users"></i> ผู้ใช้งานระบบ
        </a>
        <a href="{{ route('admin.planting') }}" class="hover:bg-white hover:text-green-800 px-4 py-2 rounded transition {{ (isset($sideActive) && $sideActive == 'planting') ? 'side-active' : '' }}">
            <i class="fa-solid fa-seedling"></i> การปลูก
        </a>
        <a href="{{ route('setting.index') }}" class="hover:bg-white hover:text-green-800 px-4 py-2 rounded transition {{ (isset($sideActive) && $sideActive == 'setting') ? 'side-active' : '' }}">
            <i class="fa-solid fa-gear"></i> จัดการเช็นเชอร์
        </a>
        <a href="{{ route('admin.users.profile') }}" class="{{ (isset($sideActive) && $sideActive == 'user') ? 'side-active' : '' }} px-4 py-2 hover:bg-white hover:text-green-800 rounded transition">
            <i class="fa-solid fa-users"></i> บัญชีผู้ใช้
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
