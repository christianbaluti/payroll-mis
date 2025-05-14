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
  <link rel="icon" type="image/x-icon" href="./../../includes/logo.png">
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
      <i class="ti-menu-alt mr-2 text-2xl"></i> <a href="./">HR Dashboard</a>
    </div>
    <nav class="mt-6">
  <ul class="space-y-2 font-medium text-gray-700 text-sm">
    <li>
      <a href="./paye.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-notepad"></i>
        </div>
        <span>PAYE Calculator</span>
      </a>
    </li>
    <li>
      <a href="./employees.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-user"></i>
        </div>
        <span>Employees</span>
      </a>
    </li>
    <li>
      <a href="./departments.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-id-badge"></i>
        </div>
        <span>Departments</span>
      </a>
    </li>
    <li>
      <a href="./branches.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-map-alt"></i>
        </div>
        <span>Branches</span>
      </a>
    </li>
    <li class="relative">
      <button id="payrollToggle" class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="flex items-center space-x-3">
          <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
            <i class="ti-money"></i>
          </div>
          <span style="font-weight: 600;">Generate Payroll</span>
        </div>
        <i class="ti-angle-down transition-transform duration-300" id="dropdownIcon"></i>
      </button>
      <ul id="payrollSubmenu" class="ml-10 mt-2 space-y-1 overflow-hidden transition-all duration-300 ease-in-out hidden">
        <li>
          <a href="./payrun.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-100">
            <div class="p-1.5 rounded-md bg-gradient-to-tr from-gray-500 to-blue-300 text-white text-xs">
              <i class="ti-control-play text-xs"></i>
            </div>
            <span>Payrun</span>
          </a>
        </li>
        <li>
          <a href="./drafts.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-100">
            <div class="p-1.5 rounded-md bg-gradient-to-tr from-gray-500 to-blue-300 text-white text-xs">
              <i class="ti-pencil-alt text-xs"></i>
            </div>
            <span>Drafts</span>
          </a>
        </li>
        <li>
          <a href="./history.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-100">
            <div class="p-1.5 rounded-md bg-gradient-to-tr from-gray-500 to-blue-300 text-white text-xs">
              <i class="ti-time text-xs"></i>
            </div>
            <span>History</span>
          </a>
        </li>
        <li>
          <a href="./reports.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-100">
            <div class="p-1.5 rounded-md bg-gradient-to-tr from-gray-500 to-blue-300 text-white text-xs">
              <i class="ti-bar-chart text-xs"></i>
            </div>
            <span>Reports</span>
          </a>
        </li>
      </ul>
    </li>
    <script>
      const toggleBtn = document.getElementById('payrollToggle');
      const submenu = document.getElementById('payrollSubmenu');
      const icon = document.getElementById('dropdownIcon');
      toggleBtn.addEventListener('click', () => {
        submenu.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
      });
    </script>
    <li>
      <a href="./attendance.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-check"></i>
        </div>
        <span>Attendance</span>
      </a>
    </li>
    <li>
      <a href="./bulky.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-email"></i>
        </div>
        <span>Bulky Emailing</span>
      </a>
    </li>
    <li>
      <a href="./settings.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-settings"></i>
        </div>
        <span>Settings</span>
      </a>
    </li>
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
        <span class="whitespace-nowrap"><i class="ti-user text-purple-500 mr-1"></i> HR</span>
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
        <button onclick="toggleAddDepartmentModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm flex items-center">
          <i class="ti-plus mr-2"></i> Add Department
        </button>


        <!-- Add Department Modal -->
        <div id="addDepartmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">
            
            <!-- Close Button -->
            <button onclick="toggleAddDepartmentModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
              <i class="ti-close text-xl"></i>
            </button>

            <h2 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
              <i class="ti-user mr-2 text-blue-600"></i> Add New Department
            </h2>

            <form id="addDepartmentForm" class="space-y-4">

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm text-gray-700 mb-1">Department Name</label>
                  <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div>
                  <label class="block text-sm text-gray-700 mb-1">Head of Department</label>
                  <input type="text" name="head_of_department" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="sm:col-span-2">
                  <label class="block text-sm text-gray-700 mb-1">Department Branch</label>
                  <select name="branch" id="branchSelect" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">-- Select Branch --</option>
                    <!-- Branches will be populated dynamically -->
                  </select>
                </div>
              </div>

              <div class="flex justify-end pt-4">
                <button type="button" onclick="toggleAddDepartmentModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                  Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                  Save Department
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <script>
        function toggleAddDepartmentModal() {
          const modal = document.getElementById('addDepartmentModal');
          modal.classList.toggle('hidden');
        }
      </script>
            <!-- Notyf CDN -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
      <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
      <script>
        const notyf = new Notyf();
      </script>

  <!-- jQuery and Notify.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/notifyjs-browser/dist/notify.js"></script>

      <script>
        $(document).ready(function () {
          $('#addDepartmentForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
              url: 'add_department.php',
              method: 'POST',
              data: formData,
              dataType: 'json',
              beforeSend: function () {
                $.notify("Saving department data...", "info");
              },
              success: function (response) {
                if (response.status === 'success') {
                  $.notify(response.message, "success");
                  $('#addDepartmentForm')[0].reset();
                  setTimeout(() => {
                          location.reload();
                        }, 3000);
                  toggleAddDepartmentModal(); // Optional modal close function
                  setTimeout(() => {
                    location.reload();
                  }, 2000);
                } else {
                  $.notify(response.message, "error");
                }
              },
              error: function () {
                $.notify("An unexpected error occurred. Try again.", "error");
              }
            });
          });
        });
      </script>


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
                notyf.error('Failed to load branches');
              });
          });
      </script>
      <script>
          document.addEventListener('DOMContentLoaded', () => {
            fetch('get_branches.php')
              .then(response => response.json())
              .then(data => {
                const select = document.getElementById('branchSelect2');
                data.forEach(myBranch => {
                  const option = document.createElement('option');
                  option.value = myBranch.id;
                  option.textContent = myBranch.name + " from " + myBranch.location;
                  select.appendChild(option);
                });
              })
              .catch(error => {
                console.error('Error fetching branches:', error);
                notyf.error('Failed to load branches');
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
                <th class="px-4 py-3 text-center">Actions</th>
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
                <td class="px-4 py-2 text-center">
                  <button class="text-blue-600 hover:text-blue-800" onclick="editDepartment(${dept.id})"><i class="ti-pencil-alt"></i></button>
                  <button class="text-red-600 hover:text-red-800" onclick="deleteDepartment(${dept.id})"><i class="ti-trash"></i></button>
                </td>
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

        <div id="editDepartmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">
              <button onclick="toggleEditDepartmentModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
                  <i class="ti-close text-xl"></i>
              </button>
              <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Department</h2>
              <form id="editDepartmentForm" class="space-y-4">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div>
                          <label class="block text-sm text-gray-700 mb-1">Full Name</label>
                          <input type="text" name="name" id="editName" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                      </div>
                      <input type="hidden" name="id" id="editDepartmentId">
                      <div>
                          <label class="block text-sm text-gray-700 mb-1">Head of Department  </label>
                          <input type="text" name="head_of_department" id="editHead_of_department" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                      </div>
                      <div>
                          <label class="block text-sm text-gray-700 mb-1">Department Branch</label>
                          <select name="branch_id" id="branchSelect2" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                            <option value="">-- Select Branch --</option>
                            <!-- Branches will be populated dynamically -->
                          </select>
                      </div>
                  <div class="flex justify-end pt-4">
                      <button type="button" onclick="toggleEditDepartmentModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                          Cancel
                      </button>
                      <button type="button" onclick="confirmEditDepartment()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                          Update Department
                      </button>
                  </div>
              </form>
          </div>
      </div>
   
      <script type="text/javascript">
        
        function toggleEditDepartmentModal() {
            const modal = document.getElementById('editDepartmentModal');
            if (!modal) return console.error('Modal not found!');
            
            // Use classList for better state management
            modal.classList.toggle('hidden');
        }
          
          function editDepartment(id) {
              toggleEditDepartmentModal();
              
              $.ajax({
                  url: 'get_one_department.php',
                  method: 'GET',
                  data: { id: id },
                  success: function(response) {
                      if (response.status === 'success') {
                          const department = response.data;
                          
                          // Fix variable naming issue
                          $('#editDepartmentId').val(department.id);
                          $('#editName').val(department.name);
                          $('#editHead_of_department').val(department.head_of_department);
                          $('#branchSelect2').val(department.branch_id);
                      } else {
                          $.notify('Error fetching department data', 'error');
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('AJAX Error:', error);
                      $.notify('Error fetching department data', 'error');
                  }
              });
          }

          function confirmEditDepartment() {
            if (!confirm('Are you sure you want to update this department?')) {
                return;
            }

            const formData = $('#editDepartmentForm').serialize();
            const btn = document.querySelector("#editDepartmentForm button[type='button']:last-child");
            
            btn.disabled = true;
            btn.innerText = 'Updating...';

            $.ajax({
                url: 'update_department.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    btn.disabled = false;
                    btn.innerText = 'Update Department';

                    if (response.status === 'success') {
                        $.notify('Department updated successfully', 'success');
                        setTimeout(() => {
                          location.reload();
                        }, 1000);
                    } else {
                        $.notify('Error updating department', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    btn.disabled = false;
                    btn.innerText = 'Update Department';
                    console.error('Update Error:', error);
                    $.notify('An unexpected error occurred', 'error');
                }
            });
        }

      </script>
      <script>
          function deleteDepartment(id) {
            if (!confirm("Are you sure you want to delete this department?")) return;

            $.ajax({
              url: 'delete_department.php',
              method: 'POST',
              data: { id },
              dataType: 'json',
              beforeSend: function () {
                $.notify("Deleting department...", "warn");
              },
              success: function (response) {
                if (response.status === 'success') {
                  $.notify(response.message, "success");
                  // Optionally, remove the department row from DOM or reload the page
                  setTimeout(() => {
                    location.reload();
                  }, 1000);
                } else {
                  $.notify(response.message, "error");
                }
              },
              error: function () {
                $.notify("An error occurred while deleting the department.", "error");
              }
            });
          }

        </script>

      <!--End editng here-->
              <!-- Delete Confirmation Modal -->

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
