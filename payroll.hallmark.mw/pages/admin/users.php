<?php include "backend.php";
if (!isset($_SESSION['email'])) {
  header("Location: ../../index.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>HR Payroll - Employees</title>
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
<body class="bg-gray-100 h-screen overflow-hidden flex">

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed md:static z-30 bg-white shadow-lg w-64 h-full overflow-y-auto transform md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 flex items-center text-xl font-bold text-blue-600 border-b">
      <i class="ti-menu-alt mr-2 text-2xl"></i> <a href="./">Admin Dashboard</a>
    </div>
    <nav class="mt-4">
      <ul class="space-y-2 font-medium text-gray-700">
        <li><a href="./users.php" class="block px-6 py-3 bg-blue-100 hover:bg-blue-300"><i class="ti-user mr-2"></i> Users</a></li>
        <li><a href="./employees.php" class="block px-6 py-3  hover:bg-blue-100"><i class="ti-user mr-2"></i> Employees</a></li>
        <li><a href="./departments.php" class="block px-6 py-3 hover:bg-blue-100"><i class="ti-id-badge mr-2"></i> Departments</a></li>
        <li><a href="./branches.php" class="block px-6 py-3 hover:bg-blue-100"><i class="ti-map-alt mr-2"></i> Branches</a></li>
        <li><a href="./settings.php" class="block px-6 py-3 hover:bg-blue-100"><i class="ti-settings mr-2"></i> Settings</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">

    <!-- Topbar -->
    <header class="bg-white sticky top-0 z-10 shadow flex justify-between items-center px-4 py-3 border-b">
      <div class="flex items-center gap-3">
        <!-- Toggle Button for Mobile -->
        <button onclick="toggleSidebar()" class="md:hidden text-blue-600 text-2xl focus:outline-none">
          <i class="ti-layout-grid2-alt"></i>
        </button>
        <!-- Title -->
        <span class="hidden md:inline-flex text-xl font-semibold text-blue-600 items-center">
          <i class="ti-layout-grid2-alt mr-2"></i> <a href="./"> Dashboard</a>
        </span>
      </div>
      <div class="flex items-center gap-4 text-sm text-gray-700">
        <span class="whitespace-nowrap"><i class="ti-email text-blue-500 mr-1"></i> <?= $email ?></span>
        <span class="whitespace-nowrap"><i class="ti-user text-purple-500 mr-1"></i> Admin</span>
        <a href="../../includes/logout.php" class="text-red-500 hover:text-red-700 font-medium flex items-center">
          <i class="ti-power-off mr-1"></i> Logout
        </a>

      </div>
    </header>

    <!-- Main Dashboard Area -->
    <main class="p-6">
      <!-- Page Title & Button -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-600 flex items-center">
          <i class="ti-user mr-2 text-2xl"></i> Users
        </h1>
        <button onclick="toggleAddUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm flex items-center">
          <i class="ti-plus mr-2"></i> Add User
        </button>
      </div>

      <!-- User Creation Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
  <div class="max-w-3xl w-full bg-white shadow-xl rounded-2xl p-8 relative">
    <!-- Close Button -->
    <button onclick="toggleAddUserModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">
      &times;
    </button>

    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
      <i class="ti-user mr-2 text-blue-600 text-3xl"></i> Create New User
    </h2>

    <form id="createUserForm" class="space-y-5">
      <!-- Email -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
          <i class="ti-email text-gray-400 mr-2"></i>
          <input type="email" name="email" required class="w-full outline-none" placeholder="Enter user email" />
        </div>
      </div>

      <!-- Role -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Role</label>
        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
          <i class="ti-id-badge text-gray-400 mr-2"></i>
          <select name="role" required class="w-full outline-none bg-transparent">
            <option value="" disabled selected>Select Role</option>
            <option value="admin">Admin</option>
            <option value="hr">HR</option>
          </select>
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md">
          <i class="ti-email mr-2"></i> Send Account Invitation
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-60 flex items-center justify-center z-50 hidden">
  <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-75"></div>
</div>

<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- Notyf (for Toasts) -->
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" />

<script>
  function toggleAddUserModal() {
    const modal = document.getElementById("addUserModal");
    modal.classList.toggle("hidden");
  }

  const notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' }
  });

  document.getElementById("createUserForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const spinner = document.getElementById("loadingSpinner");

    spinner.classList.remove("hidden");

    fetch("create_user_invite.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          notyf.success(data.message);
          e.target.reset();
          toggleAddUserModal(); // Close modal
          setTimeout(() => {
            spinner.classList.add("hidden");
            location.reload(); // Reload after 2 seconds
          }, 2000);
        } else {
          spinner.classList.add("hidden");
          notyf.error(data.message);
        }
      })
      .catch(error => {
        spinner.classList.add("hidden");
        notyf.error("Something went wrong!");
        console.error("Error:", error);
      });
  });
</script>



      <!-- Notyf CDN -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
      <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
      <script>
        const notyf = new Notyf();
      </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notifyjs-browser/dist/notify.js"></script>

  <!-- jQuery and Notify.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notifyjs-browser/dist/notify.js"></script>

      <div id="loadingSpinner" class="hidden text-center my-4">
        <i class="ti-reload animate-spin text-xl text-gray-500"></i>
      </div>

      <!-- Employees Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <div class="mb-4">
          <table class="min-w-full text-sm text-gray-700">
              <thead class="bg-blue-50 text-left text-gray-600">
                  <tr>
                      <th class="px-4 py-3">#</th>
                      <th class="px-4 py-3">Email</th>
                      <th class="px-4 py-3">Phone</th>
                      <th class="px-4 py-3">Role</th>
                  </tr>
              </thead>
              <tbody id="employeeTableBody" class="divide-y divide-gray-200">
              </tbody>
          </table>
      </div>

      <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
          // Initialize Notify.js
            $.notify.defaults({
                className: "success",
                position: "top center",
                autoHideDelay: 3000
            });
            fetch("fetch_users.php")
                .then(response => response.json())
                .then(data => {
                  if (data.employees && Array.isArray(data.employees)) {
                    populateEmployees(data.employees);
                  } else {
                    console.error("No employee data found in response:", data);
                    $.notify("No users found!", "error");
                  }
                })
                .catch(error => {
                  console.error("Fetch error:", error);
                  $.notify("Failed to fetch users.", "error");
                });

            function populateEmployees(userList) {
              const tbody = document.getElementById('employeeTableBody');
              if (!tbody) return;

              tbody.innerHTML = '';
              userList.forEach((user, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                  <td class="px-4 py-3">${index + 1}</td>
                  <td class="px-4 py-3">${user.email}</td>
                  <td class="px-4 py-3">${user.phone || '-'}</td>
                  <td class="px-4 py-3 capitalize">${user.role}</td>
                `;
                tbody.appendChild(row);
              });
            }



            
        });
      </script>
    </main>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("-translate-x-full");
    }
  </script>
</body>
</html>
