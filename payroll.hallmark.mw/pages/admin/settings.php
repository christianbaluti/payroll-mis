<?php 
  include "backend.php"; // Ensure it pulls current user data
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Settings | HR Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="../../themify/themify-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Segoe UI', sans-serif; }
    .transition-transform { transition: transform 0.3s ease-in-out; }
  </style>
</head>
<body class="bg-gray-100 h-screen flex overflow-hidden">

  <!-- Sidebar -->

  <!-- Sidebar (Mobile + Desktop) -->
  <aside id="sidebar" class="w-64 bg-white shadow-lg h-full overflow-y-auto flex-shrink-0 transition-transform transform md:translate-x-0 -translate-x-full md:block fixed md:static z-30">
    <div class="p-6 text-xl font-bold text-blue-600 flex items-center">
      <i class="ti-menu-alt mr-2 text-2xl"></i> <a href="./">Admin Dashboard</a>
    </div>
    <nav class="mt-4">
      <ul class="space-y-2 text-gray-700 font-medium">
        <li><a href="./users.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-user mr-2"></i> Users</a></li>
        <li><a href="./employees.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-user mr-2"></i> Employees</a></li>
        <li><a href="./departments.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-id-badge mr-2"></i> Departments</a></li>
        <li><a href="./branches.php" class="block px-6 py-3 hover:bg-blue-300"><i class="ti-map-alt mr-2"></i> Branches</a></li>
        <li><a href="./settings.php" class="block px-6 py-3 hover:bg-blue-300 bg-blue-100"><i class="ti-settings mr-2"></i> Settings</a></li>
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

    <!-- Settings Content -->
    <main class="px-4 sm:px-8 lg:px-16 py-8 mx-auto max-w-7xl">
  <div class="bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-3xl font-bold text-blue-600 mb-6">User Settings</h2>

    <form id="settingsForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Email -->
      <div class="col-span-1">
        <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= $email ?>" 
               class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <!-- Phone -->
      <div class="col-span-1">
        <label class="block text-gray-700 font-semibold mb-1" for="phone">Phone</label>
        <input type="text" name="phone" id="phone" value="<?= $phone ?>" 
               class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
      </div>

      <!-- Role -->
      <div class="col-span-1 md:col-span-2">
        <label class="block text-gray-700 font-semibold mb-1">Role</label>
        <input type="text" readonly value="<?= $role ?>" 
               class="w-full bg-gray-100 text-gray-600 border border-gray-300 rounded-lg p-3 cursor-not-allowed">
      </div>

      <!-- New Password -->
      <div class="col-span-1 md:col-span-2">
        <label class="block text-gray-700 font-semibold mb-1">New Password</label>
        <input type="password" name="password" id="password" placeholder="Leave blank to keep current"
               class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <!-- Submit Button -->
      <div class="col-span-1 md:col-span-2 text-right">
        <button type="submit"
                class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
          Update Settings
        </button>
      </div>
    </form>
  </div>
</main>


  </div>

  <!-- Notyf + Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
  <script>
    const notyf = new Notyf({ duration: 3000, position: { x: 'right', y: 'top' } });

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch('update_settings.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          notyf.success(data.message || 'Settings updated successfully!');
        } else {
          notyf.error(data.message || 'Failed to update settings.');
        }
      })
      .catch(() => notyf.error('An error occurred while processing the request.'));
    });
  </script>
</body>
</html>
