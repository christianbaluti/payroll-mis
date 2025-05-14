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
  <title>HR Payroll - Department</title>
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
        <li><a href="./users.php" class="block px-6 py-3 hover:bg-blue-100"><i class="ti-user mr-2"></i> Users</a></li>
        <li><a href="./employees.php" class="block px-6 py-3 hover:bg-blue-100"><i class="ti-user mr-2"></i> Employees</a></li>
        <li><a href="./departments.php" class="block px-6 py-3 bg-blue-100 hover:bg-blue-300"><i class="ti-id-badge mr-2"></i> Departments</a></li>
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
          <i class="ti-layout-grid2-alt mr-2"></i> <a href="./">Dashboard</a>
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
          <i class="ti-user mr-2 text-2xl"></i> Departments
        </h1>
      </div>
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

      <script>
          document.addEventListener('DOMContentLoaded', () => {
            fetch('get_branches.php')
              .then(response => response.json())
              .then(data => {
                const select = document.getElementById('branchSelect');
                data.forEach(myBranch => {
                  const option = document.createElement('option');
                  option.value = myBranch.id;
                  option.textContent = myBranch.name + " from " + myBranch.location;
                  select.appendChild(option);
                });
              })
              .catch(error => {
                console.error('Error fetching branches:', error);
              });
          });
      </script>

      <div id="loadingSpinner" class="hidden text-center my-4">
        <i class="ti-reload animate-spin text-xl text-gray-500"></i>
      </div>

      <!-- Filters -->
      <div class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input type="text" id="nameFilter" class="border p-2 rounded w-full" placeholder="Department name">
          <input type="text" id="headFilter" class="border p-2 rounded w-full" placeholder="Head of Department">
          <select class="border p-2 rounded w-full" id="branchFilter">
            <option value="">All Branches</option>
          </select>
        </div>
      </div>

      <!-- Departments Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <div class="mb-4 text-right">
          <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-blue-50 text-left text-gray-600">
              <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Department name</th>
                <th class="px-4 py-3">Branch</th>
                <th class="px-4 py-3">Head of Department</th>
                <th class="px-4 py-3">Number of Employees</th>
              </tr>
            </thead>
            <tbody id="departmentTableBody" class="divide-y divide-gray-200">
              <!-- Rows will be inserted here -->
            </tbody>
          </table>
        </div>
      </div>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const nameInput = document.getElementById("nameFilter");
          const headInput = document.getElementById("headFilter");
          const branchSelect = document.getElementById("branchFilter");
          const tableBody = document.getElementById("departmentTableBody");

          // Load all branches on page load
          fetchDepartments(); // Also loads branches and default departments

          function fetchDepartments(filters = {}) {
            const method = Object.keys(filters).length ? 'POST' : 'GET';

            fetch('fetch_departments.php', {
              method: method,
              headers: { 'Content-Type': 'application/json' },
              body: method === 'POST' ? JSON.stringify(filters) : null
            })
            .then(res => res.json())
            .then(data => {
              if (data.branches) populateBranches(data.branches);
              if (data.departments) populateTable(data.departments);
            })
            .catch(err => {
              console.error('Error fetching data:', err);
            });
          }

          function populateBranches(branches) {
            // Clear all except the default option
            branchSelect.innerHTML = '<option value="">All Branches</option>';
            branches.forEach(branch => {
              const option = document.createElement('option');
              option.value = branch.id;
              option.textContent = `${branch.name} (${branch.location})`;
              branchSelect.appendChild(option);
            });
          }

          function populateTable(departments) {
            tableBody.innerHTML = '';
            if (departments.length === 0) {
              const tr = document.createElement('tr');
              const td = document.createElement('td');
              td.colSpan = 6;
              td.className = 'text-center py-4 text-gray-500';
              td.textContent = 'No departments found.';
              tr.appendChild(td);
              tableBody.appendChild(tr);
              return;
            }

            departments.forEach((dept, index) => {
              const tr = document.createElement('tr');

              tr.innerHTML = `
                <td class="px-4 py-2">${index + 1}</td>
                <td class="px-4 py-2">${dept.department_name}</td>
                <td class="px-4 py-2">${dept.branch_name || '—'}</td>
                <td class="px-4 py-2">${dept.head_of_department}</td>
                <td class="px-4 py-2 text-center">${dept.employee_count}</td>
              `;

              tableBody.appendChild(tr);
            });
          }

          function applyFilters() {
            const filters = {
              department_name: nameInput.value.trim(),
              head_of_department: headInput.value.trim(),
              branch_id: branchSelect.value
            };

            fetchDepartments(filters);
          }

          // Add listeners to filters
          nameInput.addEventListener('input', debounce(applyFilters, 400));
          headInput.addEventListener('input', debounce(applyFilters, 400));
          branchSelect.addEventListener('change', applyFilters);

          // Simple debounce to prevent over-fetching
          function debounce(func, wait) {
            let timeout;
            return function () {
              clearTimeout(timeout);
              timeout = setTimeout(func, wait);
            };
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
