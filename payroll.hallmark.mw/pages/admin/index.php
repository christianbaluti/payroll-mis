<?php 

  include "backend.php";

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HR Payroll Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="../../themify/themify-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
    .transition-transform {
      transition: transform 0.3s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-100 h-screen flex overflow-hidden">

  <!-- Sidebar (Mobile + Desktop) -->
  <aside id="sidebar" class="w-64 bg-white shadow-lg h-full overflow-y-auto flex-shrink-0 transition-transform transform md:translate-x-0 -translate-x-full md:block fixed md:static z-30">
    <div class="p-6 text-xl font-bold text-blue-600 flex items-center">
      <i class="ti-menu-alt mr-2 text-2xl"></i> <a href="./">Admin Dashboard</a>
    </div>
    <nav class="mt-4">
      <ul class="space-y-2 text-gray-700 font-medium">
        <li><a href="./users.php" class="block px-6 py-3 hover:bg-blue-100"><i class="ti-user mr-2"></i> Users</a></li>
        <li><a href="./employees.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-user mr-2"></i> Employees</a></li>
        <li><a href="./departments.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-id-badge mr-2"></i> Departments</a></li>
        <li><a href="./branches.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-map-alt mr-2"></i> Branches</a></li>
        <li><a href="./settings.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-settings mr-2"></i> Settings</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col min-h-screen">

    <!-- Topbar -->
    <header class="bg-white shadow-md sticky top-0 z-10 flex justify-between items-center px-4 py-3 border-b">
      <div class="flex items-center space-x-3">
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleSidebar()" class="md:hidden text-blue-600 text-2xl focus:outline-none">
          <i class="ti-layout-grid2-alt"></i>
        </button>
        <!-- Title (Hidden on mobile) -->
        <span class="text-xl font-semibold text-blue-600 hidden md:flex items-center">
          <i class="ti-layout-grid2-alt mr-2"></i> <a href="./">Dashboard</a>
        </span>
      </div>
      <div class="text-sm text-gray-700 space-x-4 flex items-center">
        <span class="whitespace-nowrap"><i class="ti-email text-blue-500 mr-1"></i> <?= $email ?></span>
        <span class="whitespace-nowrap"><i class="ti-user text-purple-500 mr-1"></i> Admin</span>
        <a href="../../includes/logout.php" class="text-red-500 hover:text-red-700 font-medium flex items-center">
          <i class="ti-power-off mr-1"></i> Logout
        </a>
      </div>
    </header>

    <!-- Dashboard Content -->
    <main class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
  <div class="bg-white p-5 rounded-xl shadow-md flex items-center">
    <i class="ti-user text-3xl text-blue-500 mr-4"></i>
    <div>
      <h3 class="text-xl font-bold text-gray-700">Employees</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalEmployees ?></p>
    </div>
  </div>

  <div class="bg-white p-5 rounded-xl shadow-md flex items-center">
    <i class="ti-briefcase text-3xl text-green-500 mr-4"></i>
    <div>
      <h3 class="text-xl font-bold text-gray-700">Departments</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalDepartments ?></p>
    </div>
  </div>

  <div class="bg-white p-5 rounded-xl shadow-md flex items-center">
    <i class="ti-location-pin text-3xl text-yellow-500 mr-4"></i>
    <div>
      <h3 class="text-xl font-bold text-gray-700">Branches</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalBranches ?></p>
    </div>
  </div>

  <div class="bg-white p-5 rounded-xl shadow-md flex items-center">
    <i class="ti-crown text-3xl text-red-500 mr-4"></i>
    <div>
      <h3 class="text-xl font-bold text-gray-700">Admin Users</h3>
      <p class="text-sm text-gray-500">Total: <?= $adminCount?></p>
    </div>
  </div>

  <div class="bg-white p-5 rounded-xl shadow-md flex items-center">
    <i class="ti-clipboard text-3xl text-indigo-500 mr-4"></i>
    <div>
      <h3 class="text-xl font-bold text-gray-700">HR Users</h3>
      <p class="text-sm text-gray-500">Total: <?= $hrCount?></p>
    </div>
  </div>

  <div class="bg-white p-5 rounded-xl shadow-md flex items-center">
    <i class="ti-bar-chart text-3xl text-pink-500 mr-4"></i>
    <div>
      <h3 class="text-xl font-bold text-gray-700">Total Users</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalUsers ?></p>
    </div>
  </div>
</main>


  </div>

  <!-- JS to toggle sidebar -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    }

    // Optional: Close sidebar when clicking outside on mobile
    window.addEventListener('click', function (e) {
      const sidebar = document.getElementById('sidebar');
      if (!sidebar.contains(e.target) && !e.target.closest('button')) {
        if (window.innerWidth < 768) {
          sidebar.classList.add('-translate-x-full');
        }
      }
    });
  </script>
</body>
</html>
