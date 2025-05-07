<!-- Sidebar -->
<aside id="sidebar"
    class="sidebar w-64 bg-gradient-to-b from-green-200 to-green-400 text-green-900 flex flex-col p-4 space-y-6 shadow-lg fixed top-0 left-0 h-full transform sidebar-closed md:block hidden">
    <!-- Close Button (X) -->
    <button id="close-sidebar" class="text-3xl text-green-600 absolute top-4 right-4 md:hidden">
        ×
    </button>

    <div class="text-2xl font-bold">🌿 NPK SYSTEM</div>
    <nav class="flex flex-col space-y-2">
        <a href="#dashboard" class="hover:bg-green-100 px-4 py-2 rounded transition side-active">📊 Dashboard</a>
        <a href="#user" class="hover:bg-green-100 px-4 py-2 rounded transition">👤 ผู้ใช้</a>
        <a href="#settings" class="hover:bg-green-100 px-4 py-2 rounded transition">⚙️ การตั้งค่า</a>
        <a href="#logout" class="mt-auto hover:bg-red-100 bg-red-200 px-4 py-2 rounded transition text-red-800">🚪
            ออกจากระบบ</a>
    </nav>
</aside>

<!-- Hamburger menu for mobile -->
<div id="hamburger-container" class="md:hidden p-4 fixed top-4 left-4 z-10">
    <button id="hamburger" class="text-green-600 text-3xl">
        ☰
    </button>
</div>
<script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const closeSidebarButton = document.getElementById('close-sidebar');
    const hamburgerContainer = document.getElementById('hamburger-container');

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('ml-64');
        hamburgerContainer.classList.add('hidden'); // Hide hamburger when sidebar opens
    });

    closeSidebarButton.addEventListener('click', () => {
        sidebar.classList.add('hidden');
        mainContent.classList.remove('ml-64');
        hamburgerContainer.classList.remove('hidden'); // Show hamburger again when sidebar closes
    });
</script>
