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
  <title>HR Payroll - Branches</title>
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
      <a href="./departments.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
        <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
          <i class="ti-id-badge"></i>
        </div>
        <span>Departments</span>
      </a>
    </li>
    <li>
      <a href="./branches.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
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
          <i class="ti-user mr-2 text-2xl"></i> Branches
        </h1>
        <button onclick="toggleAddBranchModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm flex items-center">
          <i class="ti-plus mr-2"></i> Add Branch
        </button>


        <!-- Add Branch Modal --> 
        <div id="addBranchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">

            <!-- Close Button -->
            <button onclick="toggleAddBranchModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
              <i class="ti-close text-xl"></i>
            </button>

            <h2 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
              <i class="ti-map mr-2 text-green-600"></i> Add New Branch
            </h2>

            <form id="addBranchForm" class="space-y-4">

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm text-gray-700 mb-1">Branch Name</label>
                  <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-green-300">
                </div>

                <div>
                  <label class="block text-sm text-gray-700 mb-1">Branch Location</label>
                  <input type="text" name="location" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-green-300">
                </div>
              </div>

              <div class="flex justify-end pt-4">
                <button type="button" onclick="toggleAddBranchModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                  Cancel
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                  Save Branch
                </button>
              </div>
            </form>
          </div>
        </div>

      </div>

      <script>
        function toggleAddBranchModal() {
          const modal = document.getElementById('addBranchModal');
          modal.classList.toggle('hidden');
        }
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

      <script>
        $(document).ready(function () {
          $('#addBranchForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
              url: 'add_branch.php',
              method: 'POST',
              data: formData,
              dataType: 'json',
              beforeSend: function () {
                $.notify("Saving Branch data...", "info");
              },
              success: function (response) {
                if (response.status === 'success') {
                  $.notify(response.message, "success");
                  $('#addBranchForm')[0].reset();
                  toggleAddBranchModal(); // Optional modal close function
                  setTimeout(() => {
                          location.reload();
                        }, 3000);
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

      <div id="loadingSpinner" class="hidden text-center my-4">
        <i class="ti-reload animate-spin text-xl text-gray-500"></i>
      </div>

      <!-- Filters -->
      <div class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input type="text" id="branchNameFilter" class="border p-2 rounded w-full" placeholder="Branch name">
          <input type="text" id="branchLocationFilter" class="border p-2 rounded w-full" placeholder="Branch location">
          
        </div>
      </div>

      <!-- Branch Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <div class="mb-4 text-right">
          <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-green-50 text-left text-gray-600">
              <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Branch Name</th>
                <th class="px-4 py-3">Location</th>
                <th class="px-4 py-3">Departments</th>
                <th class="px-4 py-3">Employees</th>
                <th class="px-4 py-3 text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="branchTableBody" class="divide-y divide-gray-200">
              <!-- Rows will be inserted dynamically -->
            </tbody>
          </table>
        </div>
      </div>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const nameFilter = document.getElementById("branchNameFilter");
          const locationFilter = document.getElementById("branchLocationFilter");
          const tableBody = document.getElementById("branchTableBody");

          fetchBranches(); // Load all branches on page load

          function fetchBranches(filters = {}) {
            const method = Object.keys(filters).length ? 'POST' : 'GET';

            fetch('fetch_branches.php', {
              method: method,
              headers: { 'Content-Type': 'application/json' },
              body: method === 'POST' ? JSON.stringify(filters) : null
            })
            .then(res => res.json())
            .then(data => {
              populateBranchTable(data.branches || []);
            })
            .catch(err => {
              console.error('Error fetching branches:', err);
            });
          }

          function populateBranchTable(branches) {
            tableBody.innerHTML = '';

            if (branches.length === 0) {
              const tr = document.createElement('tr');
              const td = document.createElement('td');
              td.colSpan = 6;
              td.className = 'text-center py-4 text-gray-500';
              td.textContent = 'No branches found.';
              tr.appendChild(td);
              tableBody.appendChild(tr);
              return;
            }

            branches.forEach((branch, index) => {
              const tr = document.createElement('tr');

              tr.innerHTML = `
                <td class="px-4 py-2">${index + 1}</td>
                <td class="px-4 py-2 font-medium">${branch.branch_name}</td>
                <td class="px-4 py-2">${branch.location}</td>
                <td class="px-4 py-2 text-center">${branch.department_count}</td>
                <td class="px-4 py-2 text-center">${branch.employee_count}</td>
                <td class="px-4 py-2 text-center">
                  <button onclick="editBranch(${branch.id})" class="text-blue-600 hover:text-blue-800">
                    <i class="ti-pencil-alt"></i>
                  </button>
                  <button onclick="deleteBranch('${branch.id}')" class="text-red-600 hover:text-red-800">
                    <i class="ti-trash"></i>
                  </button>
                </td>
              `;

              tableBody.appendChild(tr);
            });
          }

          function applyBranchFilters() {
            const filters = {
              branch_name: nameFilter.value.trim(),
              location: locationFilter.value.trim(),
              // Add more filters here if needed
            };

            fetchBranches(filters);
          }

          // Add listeners to filters
          nameFilter.addEventListener('input', debounce(applyBranchFilters, 400));
          locationFilter.addEventListener('input', debounce(applyBranchFilters, 400));

          // Debounce helper
          function debounce(func, delay) {
            let timer;
            return function () {
              clearTimeout(timer);
              timer = setTimeout(func, delay);
            };
          }
        });
      </script>
      <!-- Edit Branch Modal -->
      <div id="editBranchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl p-6 relative">
              <button onclick="toggleEditBranchModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
                  <i class="ti-close text-xl"></i>
              </button>
              <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Branch</h2>
              <form id="editBranchForm" class="space-y-4">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div>
                          <label class="block text-sm text-gray-700 mb-1">Full Name</label>
                          <input type="text" name="name" id="editName" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                      </div>
                      <input type="hidden" name="id" id="editBranchId">
                      <div>
                          <label class="block text-sm text-gray-700 mb-1">Location</label>
                          <input type="text" name="location" id="editLocation" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                      </div>
                  </div>
                  <div class="flex justify-end pt-4">
                      <button type="button" onclick="toggleEditBranchModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                          Cancel
                      </button>
                      <button type="button" onclick="confirmEditBranch()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                          Update Branch
                      </button>
                  </div>
              </form>
          </div>
      </div>


      <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-xs p-6 relative">
              <button onclick="toggleDeleteModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
                  <i class="ti-close text-xl"></i>
              </button>
              <h2 class="text-xl font-semibold mb-4 text-gray-800">Are you sure you want to delete this Branch?</h2>
              <div class="flex justify-end pt-4">
                  <button type="button" onclick="toggleDeleteModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                      Cancel
                  </button>
                  <button type="button" id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                      Delete
                  </button>
              </div>
          </div>
      </div>


      <script>
          function toggleEditBranchModal() {
              const modal = document.getElementById('editBranchModal');
              modal.classList.toggle('hidden');
          }

          function editBranch(id) {
              toggleEditBranchModal();
              const fetch_id = id;
              
              $.ajax({
                  url: 'get_one_branch.php',
                  method: 'GET',
                  data: { id: id },
                  success: function(response) {
                      const one_branch = response.data;

                      $('#editBranchId').val(one_branch.id); // ✅ Set hidden ID
                      $('#editName').val(one_branch.name);
                      $('#editLocation').val(one_branch.location);
                  },
                  error: function() {
                      console.log(data);
                      $.notify('Error fetching Branch data', 'error');
                  }
              });
          }

          function confirmEditBranch() {
            const formData = $('#editBranchForm').serialize();

            if (confirm('Are you sure you want to update this Branch?')) {
                const btn = document.querySelector("#editBranchForm button[type='button']:last-child");
                btn.disabled = true;
                btn.innerText = 'Updating...';

                $.ajax({
                    url: 'update_branches.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        btn.disabled = false;
                        btn.innerText = 'Update Branch';

                        if (response.status === 'success') {
                            $.notify('Branch updated successfully, reloading...', 'success');
                            setTimeout(() => {
                              location.reload();
                            }, 2000);
                        } else {
                            $.notify('Error updating branch', 'error');
                        }
                    },
                    error: function() {
                        btn.disabled = false;
                        btn.innerText = 'Update Branch';
                        $.notify('An unexpected error occurred', 'error');
                    }
                });

                toggleEditBranchModal();
            }
          }

          function toggleDeleteModal() {
              const modal = document.getElementById('deleteModal');
              modal.classList.toggle('hidden');
          }

          function deleteBranch(id) {
              toggleDeleteModal();

              document.getElementById('confirmDeleteBtn').onclick = function() {
                  $.ajax({
                      url: 'delete_one_branch.php',
                      method: 'POST',
                      data: { id: id },
                      
                      dataType: 'json',
                      success: function(response) {
                          if (response.status === 'success') {
                              notyf.success(response.message);
                              setTimeout(() => {
                                    window.location.reload();
                                }, 2000);

                          } else {
                              notyf.error(response.message);
                          }
                      },
                      error: function() {
                          notyf.error('An unexpected error occurred. Try again.');
                      }
                  });
                  toggleDeleteModal();
              };
          }
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
