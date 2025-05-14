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
          <a href="./employees.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
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
          <i class="ti-layout-grid2-alt mr-2"></i> <a href="./"> Dashboard</a>
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
          <i class="ti-user mr-2 text-2xl"></i> Employees
        </h1>
        <button onclick="toggleAddEmployeeModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm flex items-center">
          <i class="ti-plus mr-2"></i> Add Employee
        </button>

        <!-- Add Employee Modal -->
        <div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl max-h-[90vh] p-6 relative flex flex-col">

            <!-- Close Button -->
            <button onclick="toggleAddEmployeeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
              <i class="ti-close text-xl"></i>
            </button>

            <h2 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
              <i class="ti-user mr-2 text-blue-600"></i> Add New Employee
            </h2>

            <!-- Scrollable content -->
            <div class="overflow-y-auto pr-2" style="max-height: calc(80vh - 80px);">

              <form id="addEmployeeForm" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <!-- All your input fields here -->
                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Position</label>
                    <input type="text" name="position" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                      <label class="block text-sm text-gray-700 mb-1">Department</label>
                      <select name="department_id" id="departmentSelect2" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">-- Select Department --</option>
                        <!-- Departments will be populated dynamically -->
                      </select>
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Date of Joining</label>
                    <input type="date" name="date_of_joining" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Gender</label>
                    <select name="gender" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                      <option value="">-- Select Gender --</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Salary (MWK)</label>
                    <input type="number" step="0.01" name="salary" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Bank Name</label>
                    <input type="text" name="bank_name" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Bank Branch</label>
                    <input type="text" name="bank_branch" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Bank Account Name</label>
                    <input type="text" name="bank_account_name" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Bank Account Number</label>
                    <input type="text" name="branch_code" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Hours per Week</label>
                    <input type="number" name="hours_per_week" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Hours per day on Weekend</label>
                    <input type="number" name="hours_per_weekend" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Hours per day on Weekday</label>
                    <input type="number" name="hours_per_weekday" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div>
                    <label class="block text-sm text-gray-700 mb-1">Hourly Rate</label>
                    <input type="number" name="hourly_rate" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                  </div>

                  <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-700 mb-1">Employment Status</label>
                    <select name="status" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                      <option value="">-- Select Status --</option>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                      <option value="terminated">Terminated</option>
                    </select>
                  </div>

                  <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-700 mb-1">Remarks</label>
                    <textarea name="remarks" rows="3" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"></textarea>
                  </div>
                </div>

                <div class="flex justify-end pt-4">
                  <button type="button" onclick="toggleAddEmployeeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                    Cancel
                  </button>
                  <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Save Employee
                  </button>
                </div>
              </form>

            </div>

          </div>
        </div>

      </div>

      <script>
        function toggleAddEmployeeModal() {
          const modal = document.getElementById('addEmployeeModal');
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
    $('#addEmployeeForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: 'add_employee.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    notyf.success(response.message);
                    $('#addEmployeeForm')[0].reset();
                    toggleAddEmployeeModal();
                    setTimeout(() => {
                          location.reload();
                        }, 2000);
                } else {
                    notyf.error(response.message);
                }
            },
            error: function() {
                notyf.error('An unexpected error occurred. Try again.');
            }
        });
    });
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      fetch('get_departments.php')
        .then(response => response.json())
        .then(data => {
          const select = document.getElementById('departmentSelect2');
          data.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.id;
            option.textContent = dept.name;
            select.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Error fetching departments:', error);
          notyf.error('Failed to load departments');
        });
    });
</script>

<div id="loadingSpinner" class="hidden text-center my-4">
  <i class="ti-reload animate-spin text-xl text-gray-500"></i>
</div>

<!-- Filters -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" placeholder="Search by name" class="border p-2 rounded w-full" id="nameFilter"/>
        <select class="border p-2 rounded w-full" id="genderFilter">
            <option value="">All Genders</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <select class="border p-2 rounded w-full" id="branchFilter">
            <option value="">All Branches</option>
        </select>
        <select class="border p-2 rounded w-full" id="departmentFilter">
            <option value="">All Departments</option>
        </select>
    </div>
</div>

      <!-- Employees Table -->
      <div class="mb-4 text-right sticky">
        <button id="exportBtn" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow">
          <i class="ti-download mr-2"></i> Export
        </button>
      </div>
      <div class="bg-white rounded-lg shadow overflow-x-auto max-h-[70vh] overflow-y-auto">
        
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-blue-50 text-left text-gray-600 sticky top-0 z-10">
            <tr>
              <th class="px-4 py-3">#</th>
              <th class="px-4 py-3">Name</th>
              <th class="px-4 py-3">Gender</th>
              <th class="px-4 py-3">Date of Birth</th>
              <th class="px-4 py-3">Email</th>
              <th class="px-4 py-3">Position</th>
              <th class="px-4 py-3">Department</th>
              <th class="px-4 py-3">Branch</th>
              <th class="px-4 py-3">Date of Joining</th>
              <th class="px-4 py-3">Salary</th>
              <th class="px-4 py-3">Bank Name</th>
              <th class="px-4 py-3">Bank Branch</th>
              <th class="px-4 py-3">Bank Account Name</th>
              <th class="px-4 py-3">Bank Account Number</th>
              <th class="px-4 py-3">Hourly Rate</th>
              <th class="px-4 py-3">Hours Per Weekday</th>
              <th class="px-4 py-3">Hours Per Weekend</th>
              <th class="px-4 py-3">Hours/Week</th>
              <th class="px-4 py-3">Employment Period</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Remarks</th>
              <th class="px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody id="employeeTableBody" class="divide-y divide-gray-200">
            <!-- Populated by JS -->
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
            fetch("fetch_employees.php")
                .then(response => response.json())
                .then(data => {
                    populateDropdown("departmentFilter", data.departments);
                    populateDropdown("branchFilter", data.branches);

                    // Show all employees initially
                    if (data.employees) {
                        populateEmployees(data.employees);
                    }
                });

            let nameInput = document.getElementById("nameFilter");
            let genderSelect = document.getElementById("genderFilter");
            let departmentSelect = document.getElementById("departmentFilter");
            let branchSelect = document.getElementById("branchFilter");

            let debounceTimer;
            nameInput.addEventListener("input", function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(updateEmployees, 300);
            });

            [genderSelect, departmentSelect, branchSelect].forEach(select => {
                select.addEventListener("change", updateEmployees);
            });

            function populateDropdown(id, values) {
                const dropdown = document.getElementById(id);
                values.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.id;           // <- use ID for backend filter
                    option.textContent = item.name;   // <- show readable name
                    dropdown.appendChild(option);
                });
            }

            function formatDate(dateString) {
                if (!dateString) return '';
                const options = { year: 'numeric', month: 'short', day: 'numeric' }; // e.g., 27 Apr 2025
                return new Date(dateString).toLocaleDateString('en-US', options);
            }

           function populateEmployees(employeeList) {
            const tbody = document.getElementById('employeeTableBody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (employeeList.length === 0) {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td colspan="18" class="px-4 py-6 text-center text-gray-500">
                  <i class="ti-info-alt mr-2"></i> No employees found.
                </td>
              `;
              tbody.appendChild(row);
              return;
            }

            employeeList.forEach((employee, index) => {
              const row = document.createElement('tr');
              row.className = 'hover:bg-gray-50';
              row.dataset.employeeId = employee.id; // Store employee ID

              row.innerHTML = `
                <td class="px-4 py-3">${index + 1}</td>
                <td class="px-4 py-3 font-medium">${employee.name}</td>
                <td class="px-4 py-3">${employee.gender}</td>
                <td class="px-4 py-3">${formatDate(employee.date_of_birth)}</td>
                <td class="px-4 py-3">${employee.email}</td>
                <td class="px-4 py-3">${employee.position}</td>
                <td class="px-4 py-3">${employee.department}</td>
                <td class="px-4 py-3">${employee.branch}</td>
                <td class="px-4 py-3">${formatDate(employee.date_of_joining)}</td>
                <td class="px-4 py-3">${employee.salary}</td>
                <td class="px-4 py-3">${employee.bank_name}</td>
                <td class="px-4 py-3">${employee.bank_branch}</td>
                <td class="px-4 py-3">${employee.bank_account_name}</td>
                <td class="px-4 py-3">${employee.branch_code}</td>
                <td class="px-4 py-3">${employee.hourly_rate}</td>
                <td class="px-4 py-3">${employee.hours_per_weekday}</td>
                <td class="px-4 py-3">${employee.hours_per_weekend}</td>
                <td class="px-4 py-3">${employee.hours_per_week}</td>
                <td class="px-4 py-3">${employee.employment_period}</td>
                <td class="px-4 py-3">${employee.status}</td>
                <td class="px-4 py-3">${employee.remarks}</td>
                <td class="px-4 py-3 text-center space-x-2">
                  <button class="text-blue-600 hover:text-blue-800" onclick="editEmployee(${employee.id})">
                    <i class="ti-pencil-alt"></i>
                  </button>
                  <button class="text-red-600 hover:text-red-800" onclick="deleteEmployee(${employee.id})">
                    <i class="ti-trash"></i>
                  </button>
                </td>
              `;
              tbody.appendChild(row);
            });
          }




            function updateEmployees() {
                $.ajax({
                    url: "fetch_employees.php",
                    type: "POST",
                    data: {
                        name: nameInput.value,
                        gender: genderSelect.value,
                        department: departmentSelect.value,
                        branch: branchSelect.value
                    },
                    dataType: "json",
                    success: function(response) {
                        if (!response.success) {
                            $.notify("Failed to filter employees", { className: "error" });
                            return;
                        }
                        populateEmployees(response.data);
                        $.notify("Employees filtered successfully!");
                    },

                    error: function () {
                        $.notify("Error occurred while filtering employees", { className: "error" });
                    }
                });
            }
        });
        document.getElementById("exportBtn").addEventListener("click", function () {
            const name = document.getElementById("nameFilter").value;
            const gender = document.getElementById("genderFilter").value;
            const department = document.getElementById("departmentFilter").value;
            const branch = document.getElementById("branchFilter").value;

            const queryParams = new URLSearchParams({
                name: name,
                gender: gender,
                department: department,
                branch: branch
            });

            window.open(`export_employees.php?${queryParams.toString()}`, "_blank");
        });

        const btn = document.getElementById("exportBtn");
        btn.disabled = true;
        btn.innerText = "...";

        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = "<i class='ti-download mr-2'></i>Export";
        }, 500);
      </script>

      <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
          <div class="bg-white rounded-2xl shadow-lg w-full max-w-xs p-6 relative">
              <button onclick="toggleDeleteModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
                  <i class="ti-close text-xl"></i>
              </button>
              <h2 class="text-xl font-semibold mb-4 text-gray-800">Are you sure you want to delete this employee?</h2>
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

    <div id="editEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto hidden">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl h-full max-h-[90vh] overflow-y-auto p-6 relative flex flex-col">
          

            <button onclick="toggleEditEmployeeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
                <i class="ti-close text-xl"></i>
            </button>
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Employee</h2>
            <form id="editEmployeeForm" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" id="editName" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <input type="hidden" name="id" id="editEmployeeId">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="editEmail" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Position</label>
                        <input type="text" name="position" id="editPosition" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Department</label>
                        <select name="department_id" id="editDepartmentSelect" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                            <option value="">-- Select Department --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Gender</label>
                        <select name="gender" id="editGender" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                            <option value="">-- Select Gender --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Salary (MWK)</label>
                        <input type="number" step="0.01" name="salary" id="editSalary" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Bank Name</label>
                        <input type="text" name="bank_name" id="editBankName" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Bank Branch</label>
                        <input type="text" name="bank_branch" id="editBankBranch" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Bank Account Name</label>
                        <input type="text" name="bank_account_name" id="editBankAccountName" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Bank Account Number</label>
                        <input type="text" name="branch_code" id="editBranchCode" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Hours per Week</label>
                        <input type="number" name="hours_per_week" id="editHoursPerWeek" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <div>
                      <label class="block text-sm text-gray-700 mb-1">Hours per day on Weekend</label>
                      <input type="number" name="hours_per_weekend" id="edit_hours_per_weekend" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <div>
                      <label class="block text-sm text-gray-700 mb-1">Hours per day on Weekday</label>
                      <input type="number" name="hours_per_weekday" id="edit_hours_per_weekday" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <div>
                      <label class="block text-sm text-gray-700 mb-1">Hourly Rate</label>
                      <input type="number" name="hourly_rate" id="edit_hourly_rate" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Status</label>
                        <select name="status" id="editStatus" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" id="editRemarks" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"></textarea>
                    </div>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="button" onclick="toggleEditEmployeeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg mr-2">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmEditEmployee()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>


      
      <script type="text/javascript">
        function toggleDeleteModal() {
              const modal = document.getElementById('deleteModal');
              modal.classList.toggle('hidden');
          }
        function toggleEditEmployeeModal() {
              const modal = document.getElementById('editEmployeeModal');
              modal.classList.toggle('hidden');
          }
        function deleteEmployee(id) {
              toggleDeleteModal();

              document.getElementById('confirmDeleteBtn').onclick = function() {
                  $.ajax({
                      url: 'delete_employee.php',
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
        function editEmployee(id) {
          toggleEditEmployeeModal();

          $.ajax({
              url: 'get_employee.php',
              method: 'GET', // Or POST, depending on your backend
              data: { id: id },
              dataType: 'json',
              success: function(response) {
                  if (response.status === 'success') {
                      const employee = response.data;
                      $('#editEmployeeId').val(employee.id);
                      $('#editName').val(employee.name);
                      $('#editEmail').val(employee.email);
                      $('#editPosition').val(employee.position);
                      $('#editGender').val(employee.gender);
                      $('#editSalary').val(employee.salary);
                      $('#editBankName').val(employee.bank_name);
                      $('#editBankBranch').val(employee.bank_branch);
                      $('#editBankAccountName').val(employee.bank_account_name);
                      $('#editBranchCode').val(employee.branch_code);
                      $('#editHoursPerWeek').val(employee.hours_per_week);
                      $('#editStatus').val(employee.status);
                      $('#editRemarks').val(employee.remarks);
                      $('#edit_hours_per_weekend').val(employee.hours_per_weekend);
                      $('#edit_hours_per_weekday').val(employee.hours_per_weekday);
                      $('#edit_hourly_rate').val(employee.hourly_rate);

                      // Populate departments in the edit form
                      fetch('get_departments.php')
                          .then(response => response.json())
                          .then(data => {
                              const select = document.getElementById('editDepartmentSelect'); // Assuming this is the ID in your edit form
                              select.innerHTML = '<option value="">-- Select Department --</option>'; // Clear previous options
                              data.forEach(dept => {
                                  const option = document.createElement('option');
                                  option.value = dept.id;
                                  option.textContent = dept.name;
                                  option.selected = (dept.id == employee.department_id); // Select current department
                                  select.appendChild(option);
                              });
                          })
                          .catch(error => {
                              console.error('Error fetching departments:', error);
                              notyf.error('Failed to load departments for edit form');
                          });

                  } else {
                      notyf.error(response.message);
                      toggleEditEmployeeModal(); // Hide the modal if fetching fails
                  }
              },
              error: function() {
                  notyf.error('An unexpected error occurred while fetching employee data.');
                  toggleEditEmployeeModal(); // Hide the modal on error
              }
          });
          }
        function confirmEditEmployee() {
            const formData = $('#editEmployeeForm').serialize();

            if (confirm('Are you sure you want to update this employee?')) {
                const btn = document.querySelector("#editEmployeeForm button[type='button']:last-child");
                btn.disabled = true;
                btn.innerText = 'Updating...';

                $.ajax({
                    url: 'update_employee.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        btn.disabled = false;
                        btn.innerText = 'Update Employee';

                        if (response.status === 'success') {
                            $.notify('Employee updated successfully', 'success');
                            setTimeout(() => {
                              location.reload();
                            }, 2000);
                        } else {
                            $.notify('Error updating employee', 'error');
                        }
                    },
                    error: function() {
                        btn.disabled = false;
                        btn.innerText = 'Update Employee';
                        $.notify('An unexpected error occurred', 'error');
                    }
                });

                toggleEditEmployeeModal();
            }
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
