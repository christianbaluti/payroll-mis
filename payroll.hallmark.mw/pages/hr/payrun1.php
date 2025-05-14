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
    <title>HR Payroll - Payrolls</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet" />
    <link href="../../themify/themify-icons.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="./../../includes/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notifyjs-browser/dist/notify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
      const notyf = new Notyf({
          duration: 3000,
          position: {
              x: 'right',
              y: 'top',
          },
          types: [
              {
                  type: 'success',
                  background: '#4CAF50',
                  icon: {
                      className: 'ti-check',
                      tagName: 'i',
                      text: 'Success'
                  }
              },
              {
                  type: 'error',
                  background: '#f44336',
                  icon: {
                      className: 'ti-close',
                      tagName: 'i',
                      text: 'Error'
                  }
              }
          ]
      });
    </script>
    <style>
      body {
        font-family: 'Segoe UI', sans-serif;
      }
      .transition-transform {
        transition: transform 0.3s ease-in-out;
      }
      .loading-overlay {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(0, 0, 0, 0.5);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 1000;
      }
      .loading-spinner {
          border: 4px solid #f3f3f3;
          border-top: 4px solid #3498db;
          border-radius: 50%;
          width: 40px;
          height: 40px;
          animation: spin 1s linear infinite;
      }
      @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
      }
      .scroll-wrapper {
        width: 100%;
        overflow-x: auto;
        height: 20px;
      }
      .scroll-shadow {
        height: 1px;
        pointer-events: none;
      }
      .table-container {
        width: 100%;
        overflow-x: auto;
      }
      .scroll-wrapper,
      .table-container {
        scrollbar-width: thin;
      }

      /* Modal styles */
      .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
      }

      .modal-content {
        background: white;
        width: 90%;
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        border-radius: 8px;
      }

      .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
      }

      .modal-close {
        cursor: pointer;
        font-size: 24px;
      }

      .modal-body {
        margin-bottom: 20px;
      }

      .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
      }
    </style>
  </head>

  <body class="bg-gray-100 h-screen overflow-hidden flex">
    <!-- Sidebar -->
    <aside id="sidebar"
      class="fixed md:static z-30 bg-white shadow-lg w-64 h-full overflow-y-auto transform md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out">
      <div
        class="p-6 flex items-center text-xl font-bold text-blue-600 border-b">
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
            <a href="./branches.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-blue-100 transition-all duration-300">
              <div class="p-1.5 rounded-md bg-gradient-to-tr from-blue-600 to-blue-400 text-white text-xs">
                <i class="ti-map-alt"></i>
              </div>
              <span>Branches</span>
            </a>
          </li>
          <li class="relative">
            <button id="payrollToggle" class="flex items-center justify-between w-full px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
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
                <a href="./payrun.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-200 bg-blue-100">
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
    <header
      class="bg-white sticky top-0 z-10 shadow flex justify-between items-center px-4 py-3 border-b">
      <div class="flex items-center gap-3">
        <!-- Toggle Button for Mobile -->
        <button onclick="toggleSidebar()"
          class="md:hidden text-blue-600 text-2xl focus:outline-none">
          <i class="ti-layout-grid2-alt"></i>
        </button>
        <!-- Title -->
        <span
          class="hidden md:inline-flex text-xl font-semibold text-blue-600 items-center">
          <i class="ti-layout-grid2-alt mr-2"></i> <a href="./">Dashboard</a>
        </span>
      </div>
      <div class="flex items-center gap-4 text-sm text-gray-700">
        <span class="whitespace-nowrap"><i
            class="ti-email text-blue-500 mr-1"></i> <?= $email ?></span>
        <span class="whitespace-nowrap"><i
            class="ti-user text-purple-500 mr-1"></i> HR</span>
        <a href="../../includes/logout.php"
          class="text-red-500 hover:text-red-700 font-medium flex items-center">
          <i class="ti-power-off mr-1"></i> Logout
        </a>
      </div>
    </header>
      
      <main class="p-6">
        
        <!-- Page Title -->
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-blue-600 flex items-center">
            Payroll Drafting
          </h1>
        </div>
        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select id="branchFilter" class="border p-2 rounded w-full">
              <option value="">Select Branch</option>
            </select>
            <select id="departmentFilter" class="border p-2 rounded w-full">
              <option value="">Select Department</option>
            </select>
            <select id="genderFilter" class="border p-2 rounded w-full">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <input type="number" id="minSalary"
              class="border p-2 rounded w-full" placeholder="Min Salary">
            <input type="number" id="maxSalary"
              class="border p-2 rounded w-full" placeholder="Max Salary">
          </div>
        </div>

        <div id="loaderModal" class="modal" style="display: none; justify-content: center; align-items: center; background: rgba(0,0,0,0.7);">
            <div class="flex flex-col items-center bg-white p-6 rounded-lg shadow-xl">
                <div class="loading-spinner"></div>
                <p class="text-gray-700 mt-3">Processing Payslips & Report...</p>
            </div>
        </div>

        <!-- Bulk Edit Hours Modal -->
        <div id="bulkHoursModal" class="modal">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="text-xl font-bold">Bulk Edit Overtime Hours</h3>
              <span class="modal-close" onclick="closeModal('bulkHoursModal')">&times;</span>
            </div>
            <div class="modal-body">
              <div class="mb-4">
                <label class="block mb-2">Weekend Hours:</label>
                <input type="number" id="bulkWeekendHours" class="w-full border p-2 rounded">
              </div>
              <div class="mb-4">
                <label class="block mb-2">Weekday Hours:</label>
                <input type="number" id="bulkWeekdayHours" class="w-full border p-2 rounded">
              </div>
            </div>
            <div class="modal-footer">
              <button onclick="closeModal('bulkHoursModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
              <button onclick="applyBulkHours()" class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
            </div>
          </div>
        </div>

        <!-- Confirm Generate Payroll Modal -->
        <div id="confirmPayrollModal" class="modal">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="text-xl font-bold">Confirm Payroll Generation</h3>
              <span class="modal-close" onclick="closeModal('confirmPayrollModal')">&times;</span>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to generate the payroll? This will create draft payslips for all selected employees.</p>
            </div>
            <div class="modal-footer">
              <button onclick="closeModal('confirmPayrollModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
              <button onclick="generatePayroll()" class="bg-blue-600 text-white px-4 py-2 rounded">Generate</button>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div id="totalsSection" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-6 text-xs text-white font-semibold">
          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-wallet text-2xl text-blue-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Gross Pay</h3>
              <p id="totalGrossDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-money text-2xl text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Net Pay</h3>
              <p id="totalNetDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-bar-chart-alt text-2xl text-blue-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">PAYE</h3>
              <p id="totalPayeDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-briefcase text-2xl text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Pension (5%)</h3>
              <p id="totalPensionDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-plus text-2xl text-green-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Additions</h3>
              <p id="totalAdditionsDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-green-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-minus text-2xl text-green-700"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Deductions</h3>
              <p id="totalDeductionsDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-red-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-heart-broken text-2xl text-red-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Welfare Fund</h3>
              <p id="totalWelfareFund" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-pink-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-calendar text-2xl text-pink-600"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Weekend OT</h3>
              <p id="totalWeekendOvertime" class="text-xs text-gray-500">0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-yellow-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-calendar text-2xl text-yellow-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Weekday OT</h3>
              <p id="totalWeekdayOvertime" class="text-xs text-gray-500">0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-gray-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-time text-2xl text-gray-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total OT</h3>
              <p id="totalOvertime" class="text-xs text-gray-500">0.00</p>
            </div>
          </div>
        </div> <br>

        <!-- Action Buttons -->
        <div class="mb-4 text-right">
          <button onclick="openModal('bulkHoursModal')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Bulk Edit Hours
          </button>
          <button id="bulkEditBtn" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            Bulk Edit Additions/Deductions
          </button>
          <button onclick="openModal('confirmPayrollModal')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Generate Payroll
          </button>
        </div>

        <!-- Employee Table -->
        <div class="table-container bg-white rounded-lg shadow overflow-x-auto">
          <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-green-50 text-left text-gray-600">
              <tr>
                <th class="px-4 py-3"><input type="checkbox" id="selectAllEmployees"></th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Gender</th>
                <th class="px-4 py-3">Salary</th>
                <th class="px-4 py-3">PAYE</th>
                <th class="px-4 py-3">Net Salary</th>
                <th class="px-4 py-3">Pension</th>
                <th class="px-4 py-3">Additions</th>
                <th class="px-4 py-3">Deductions</th>
                <th class="px-4 py-3">Welfare Fund</th>
                <th class="px-4 py-3">Hourly Rate</th>
                <th class="px-4 py-3">Weekend Hours</th>
                <th class="px-4 py-3">Weekday Hours</th>
                <th class="px-4 py-3">Weekend OT Pay</th>
                <th class="px-4 py-3">Weekday OT Pay</th>
                <th class="px-4 py-3">Total Overtime</th>
                <th class="px-4 py-3">Hours/Month</th>
                <th class="px-4 py-3">Bank Name</th>
                <th class="px-4 py-3">Bank Account Name</th>
                <th class="px-4 py-3">Bank Account Number</th>
                <th class="px-4 py-3">Bank Branch</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3 text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="employeeTableBody" class="divide-y divide-gray-200">
              <!-- Dynamically filled -->
            </tbody>
          </table>
        </div>
        <!--end of employee table -->

        <!-- Modal for bulky editing salary of additons and deuductions -->
        <div id="bulkEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
          <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Bulk Edit Additions & Deductions</h2>
            
            <label class="block mb-2">Additions (MWK):</label>
            <input type="number" id="bulkAdditions" class="w-full border p-2 mb-4 rounded" placeholder="0.00">

            <label class="block mb-2">Deductions (MWK):</label>
            <input type="number" id="bulkDeductions" class="w-full border p-2 mb-4 rounded" placeholder="0.00">

            <div class="flex justify-end space-x-2">
              <button onclick="closeBulkModal()" class="bg-gray-400 text-white px-3 py-1 rounded">Cancel</button>
              <button onclick="applyBulkChanges()" class="bg-blue-600 text-white px-3 py-1 rounded">Apply</button>
            </div>
          </div>
        </div>

        <!-- Modal for Editing Net Salary -->
        <div id="salaryEditModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
          <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Edit Net Salary</h2>
            <input type="hidden" id="editEmployeeId">
            
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Current Net Salary</label>
              <input type="text" id="currentNetSalary" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>
            
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Additions (e.g. bonus)</label>
              <input type="number" id="additionsInput" class="border p-2 rounded w-full" value="0">
            </div>
            
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Deductions (e.g. advance)</label>
              <input type="number" id="deductionsInput" class="border p-2 rounded w-full" value="0">
            </div>

            <div class="flex justify-end space-x-2">
              <button onclick="closeSalaryModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
              <button onclick="applySalaryChanges()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
          </div>
        </div>
        <!-- End of Modal for Editing Net Salary -->

        <!-- Add to your existing HTML -->
        <div id="overtimeModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-xl font-bold">Edit Overtime Hours</h3>
                    <span class="modal-close" onclick="closeModal('overtimeModal')">&times;</span>
                </div>
                <div class="modal-body" data-employee-id="">
                    <div class="mb-4">
                        <label class="block mb-2">Weekend Hours:</label>
                        <input type="number" id="weekendHours" class="w-full border p-2 rounded" min="0" step="0.5">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2">Weekday Hours:</label>
                        <input type="number" id="weekdayHours" class="w-full border p-2 rounded" min="0" step="0.5">
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="closeModal('overtimeModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button onclick="saveOvertimeHours()" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                </div>
            </div>
        </div>
      </main>
    </div>

    <script type="text/javascript">
      // Sidebar toggle function
      function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
          sidebar.classList.toggle('-translate-x-full');
          console.log('Toggled sidebar:', sidebar.classList.contains('-translate-x-full') ? 'closed' : 'open');
        } else {
          console.error('Sidebar element not found!');
        }
      }

      // Wait for DOM to be fully loaded
      document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        
        if (!sidebar) {
          console.error('Sidebar element not found on DOMContentLoaded');
          return;
        }
        
        console.log('Sidebar initialized:');
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (event) => {
          // Only run this logic on mobile screens
          if (window.innerWidth <= 768) {
            const toggleButton = document.querySelector('.md\\:hidden');
            
            // If sidebar is open and click is outside sidebar and not on toggle button
            if (!sidebar.classList.contains('-translate-x-full') && 
                !sidebar.contains(event.target) && 
                (!toggleButton || !toggleButton.contains(event.target))) {
              
              console.log('Closing sidebar from outside click');
              sidebar.classList.add('-translate-x-full');
            }
          }
        });
      });
      
      // Modal functions
      function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
      }

      function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
      }

      // Open bulk modal
      document.getElementById("bulkEditBtn").addEventListener("click", () => {
        const selected = [...document.querySelectorAll('.employeeCheckbox:checked')];
        if (selected.length === 0) {
          alert("Please select at least one employee.");
          return;
        }
        document.getElementById("bulkEditModal").classList.remove("hidden");
      });

      // Apply bulk hours
      function applyBulkHours() {
          const selectedEmployees = document.querySelectorAll('.employeeCheckbox:checked');
          if (selectedEmployees.length === 0) {
              console.log('Please select at least one employee to apply bulk hours.');
              return;
          }

          const weekendHours = parseFloat(document.getElementById('bulkWeekendHours').value) || 0;
          const weekdayHours = parseFloat(document.getElementById('bulkWeekdayHours').value) || 0;

          selectedEmployees.forEach(checkbox => {
              const row = checkbox.closest('tr');
              const hourlyRate = parseFloat(row.dataset.hourlyRate);

              // Update hours
              row.querySelector('.weekend-hours').value = weekendHours;
              row.querySelector('.weekday-hours').value = weekdayHours;

              // Calculate overtime pay
              const weekendOTPay = weekendHours * hourlyRate * 2;
              const weekdayOTPay = weekdayHours * hourlyRate * 1.5;

              // Update pay cells
              row.querySelector('.weekend-ot-pay').textContent = weekendOTPay.toFixed(2);
              row.querySelector('.weekday-ot-pay').textContent = weekdayOTPay.toFixed(2);
              row.querySelector('.total-ot-pay').textContent = (weekendOTPay + weekdayOTPay).toFixed(2);
          });

          closeModal('bulkHoursModal');
          updateTotals();
      }

      // Generate payroll
      async function generatePayroll() {
          const selectedEmployees = document.querySelectorAll('.employeeCheckbox:checked');
          if (selectedEmployees.length === 0) {
              notyf.error('Please select at least one employee to generate payroll.');
              return;
          }

          const payrollData = {
              month: document.getElementById('payrollMonth').value.split('-')[1],
              year: document.getElementById('payrollMonth').value.split('-')[0],
              unique_id: Date.now().toString(),
              status: 'drafted',
              employees: []
          };

          selectedEmployees.forEach(checkbox => {
              const row = checkbox.closest('tr');
              payrollData.employees.push({
                  name: row.querySelector('td:nth-child(2)').textContent,
                  department: row.querySelector('td:nth-child(3)').textContent,
                  gender: row.querySelector('td:nth-child(4)').textContent,
                  gross_salary: parseFloat(row.dataset.salary),
                  paye: parseFloat(row.dataset.paye),
                  pension: parseFloat(row.dataset.pension),
                  additions: parseFloat(row.dataset.additions),
                  deductions: parseFloat(row.dataset.deductions),
                  welfare_fund: parseFloat(row.dataset.welfareFund),
                  hourly_rate: parseFloat(row.dataset.hourlyRate),
                  weekend_overtime_hours: parseFloat(row.querySelector('.weekend-hours').value),
                  weekday_overtime_hours: parseFloat(row.querySelector('.weekday-hours').value),
                  weekend_overtime_pay: parseFloat(row.querySelector('.weekend-ot-pay').textContent),
                  weekday_overtime_pay: parseFloat(row.querySelector('.weekday-ot-pay').textContent),
                  bank_name: row.dataset.bankName,
                  bank_account_name: row.dataset.bankAccountName,
                  bank_account_number: row.dataset.bankAccountNumber,
                  bank_branch: row.dataset.bankBranch,
                  email: row.dataset.email
              });
          });

          try {
              const response = await fetch('generate_payroll.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(payrollData)
              });

              const result = await response.json();
              if (result.success) {
                  notyf.success('Payroll generated successfully');
                  closeModal('confirmPayrollModal');
              } else {
                  notyf.error(result.message || 'Failed to generate payroll');
              }
          } catch (error) {
              console.error('Error generating payroll:', error);
              notyf.error('Failed to generate payroll');
          }
      }

      // Rest of your existing JavaScript code...

      //filtering employees
      document.addEventListener("DOMContentLoaded", () => {
        const branchSelect = document.getElementById("branchFilter");
        const deptSelect = document.getElementById("departmentFilter");
        const genderSelect = document.getElementById("genderFilter");
        const minSalary = document.getElementById("minSalary");
        const maxSalary = document.getElementById("maxSalary");
        const tableBody = document.getElementById("employeeTableBody");
        const selectAll = document.getElementById("selectAllEmployees");
        const employeeDataBuffer = {}; // Buffer to store employee data

      //Updating totals
      function updateTotals() {
        let totalGross = 0;
        let totalNet = 0;
        let totalPaye = 0;
        let totalAdditions = 0;
        let totalDeductions = 0;
        let totalPension = 0;
        let totalWelfareFund = 0;
        let totalWeekendOvertime = 0;
        let totalWeekdayOvertime = 0;
        let totalOvertime = 0;

        const rows = document.querySelectorAll("#employeeTableBody tr");
        rows.forEach(row => {
          const checkbox = row.querySelector("input[type='checkbox']");
          if (checkbox.checked) {
            const id = row.dataset.id;
            const salary = parseFloat(row.dataset.salary || 0);
            const paye = parseFloat(row.dataset.paye || 0);
            const net = parseFloat(row.dataset.net || 0);
            const additions = parseFloat(row.dataset.additions || 0);
            const deductions = parseFloat(row.dataset.deductions || 0);
            const baseNet = parseFloat(row.dataset.baseNet || 0);
            const pension = parseFloat(row.dataset.pension || 0);
            const welfareFund = parseFloat(row.dataset.welfareFund || 0);
            const weekendOvertime = parseFloat(row.dataset.weekendOvertime || 0);
            const weekdayOvertime = parseFloat(row.dataset.weekdayOvertime || 0);
            const totalOT = parseFloat(row.dataset.totalOvertime || 0);

            totalGross += salary;
            totalNet += net;
            totalPaye += paye;
            totalAdditions += additions;
            totalDeductions += deductions;
            totalPension += pension;
            totalWelfareFund += welfareFund;
            totalWeekendOvertime += weekendOvertime;
            totalWeekdayOvertime += weekdayOvertime;
            totalOvertime += totalOT;
          }
        });

        document.getElementById("totalGrossDisplay").textContent = `MWK ${totalGross.toFixed(2)}`;
        document.getElementById("totalNetDisplay").textContent = `MWK ${totalNet.toFixed(2)}`;
        document.getElementById("totalPayeDisplay").textContent = `MWK ${totalPaye.toFixed(2)}`;
        document.getElementById("totalAdditionsDisplay").textContent = `MWK ${totalAdditions.toFixed(2)}`;
        document.getElementById("totalDeductionsDisplay").textContent = `MWK ${totalDeductions.toFixed(2)}`;
        document.getElementById("totalPensionDisplay").textContent = `MWK ${totalPension.toFixed(2)}`;
        document.getElementById("totalWelfareFund").textContent = `MWK ${totalWelfareFund.toFixed(2)}`;
        document.getElementById("totalWeekendOvertime").textContent = `MWK ${totalWeekendOvertime.toFixed(2)}`;
        document.getElementById("totalWeekdayOvertime").textContent = `MWK ${totalWeekdayOvertime.toFixed(2)}`;
        document.getElementById("totalOvertime").textContent = `MWK ${totalOvertime.toFixed(2)}`;
      }

      // Select all checkbox
      selectAll.addEventListener("change", () => {
        const isChecked = selectAll.checked;
        const checkboxes = document.querySelectorAll("#employeeTableBody input[type='checkbox']");
        checkboxes.forEach(checkbox => {
          checkbox.checked = isChecked;
        });
        updateTotals();
      });

      // Monitor individual selections
      tableBody.addEventListener("change", (e) => {
        if (e.target.type === "checkbox") updateTotals();
      });

      // Load branch options
      loadBranches();

      //initial load
      fetchEmployees();

      // Event Listeners
      branchSelect.addEventListener("change", () => {
        const branchId = branchSelect.value;
        if (branchId) {
          loadDepartments(branchId);
        } else {
          deptSelect.innerHTML = `<option value="">Select Department</option>`;
        }
        fetchEmployees();
      });

      [deptSelect, genderSelect, minSalary, maxSalary].forEach(el => {
        el.addEventListener('change', fetchEmployees);
      });

      selectAll.addEventListener('change', () => {
        document.querySelectorAll('.employeeCheckbox').forEach(cb => cb.checked = selectAll.checked);
      });

      document.getElementById("payrollModal").addEventListener("click", (e) => {
        if (e.target.id === "payrollModal") closePayrollModal();
      });

      // Initial fetch
      fetchEmployees();

      // ========== Helper Functions ==========
      function loadBranches() {
        fetch('api/get_branches.php')
          .then(res => res.json())
          .then(data => {
            if (!data.success) {
              console.error("Error loading branches:", data.message);
              return;
            }

            branchSelect.innerHTML = `<option value="">Select Branch</option>`;
            data.branches.forEach(b => {
              const opt = document.createElement("option");
              opt.value = b.id;
              opt.text = b.name;
              branchSelect.appendChild(opt);
            });
          })
          .catch(err => console.error('Error loading branches:', err));
      }

      function loadDepartments(branchId) {
        fetch('api/get_departments.php?branch_id=' + branchId)
          .then(res => res.json())
          .then(data => {
            if (!data.success) {
              console.error("Error loading departments:", data.message);
              return;
            }

            deptSelect.innerHTML = `<option value="">Select Department</option>`;
            data.departments.forEach(d => {
              const opt = document.createElement("option");
              opt.value = d.id;
              opt.text = d.name;
              deptSelect.appendChild(opt);
            });
          })
          .catch(err => console.error('Error loading departments:', err));
      }

      function fetchEmployees() {
        const filters = {};

        if (branchSelect.value) filters.branch_id = branchSelect.value;
        if (deptSelect.value) filters.department_id = deptSelect.value;
        if (genderSelect.value) filters.gender = genderSelect.value;
        if (minSalary.value) filters.min_salary = parseFloat(minSalary.value);
        if (maxSalary.value) filters.max_salary = parseFloat(maxSalary.value);

        fetch('api/filter_employees2.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(filters)
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              populateEmployees(data.employees);
              calculateTotalPayroll(data.employees);
            } else {
              console.error('Error fetching employees:', data.message);
            }
          })
          .catch(err => {
            console.error('Fetch failed:', err);
          });
      }

      function calculatePAYE(salary) {
        let remaining = salary;
        let totalTax = 0;

        if (remaining <= 150000) {
          return 0;
        }
        remaining -= 150000;

        if (remaining >= 350000) {
          totalTax += 350000 * 0.25;
          remaining -= 350000;
        } else {
          totalTax += remaining * 0.25;
          return totalTax;
        }

        if (remaining >= 2050000) {
          totalTax += 2050000 * 0.30;
          remaining -= 2050000;
        } else {
          totalTax += remaining * 0.30;
          return totalTax;
        }

        totalTax += remaining * 0.35;
        return totalTax;
      }

      async function fetchEmployeeAttendance(employeeId) {
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1;

        try {
          const response = await fetch(`fetch_attendance.php?employee_id=${employeeId}&year=${year}&month=${month}`);
          const attendances = await response.json();

          let totalWeekdayHours = 0;
          let totalWeekendHours = 0;

          attendances.forEach(att => {
            if (!att.time_in || !att.time_out) return;

            const date = new Date(att.date_made);
            const dayOfWeek = date.getDay(); // 0 = Sunday, 6 = Saturday

            const timeIn = new Date(`1970-01-01T${att.time_in}Z`);
            const timeOut = new Date(`1970-01-01T${att.time_out}Z`);
            let diffInHours = (timeOut - timeIn) / (1000 * 60 * 60); // milliseconds to hours

            if (diffInHours < 0) {
              diffInHours += 24; // For overnight shifts
            }

            if (dayOfWeek === 0 || dayOfWeek === 6) {
              totalWeekendHours += diffInHours;
            } else {
              totalWeekdayHours += diffInHours;
            }
          });

          // Return values for you to assign easily
          return {
            weekdayHours: parseFloat(totalWeekdayHours.toFixed(2)),
            weekendHours: parseFloat(totalWeekendHours.toFixed(2))
          };
        } catch (error) {
          console.error("Error fetching attendances:", error);
          return {
            weekdayHours: 0,
            weekendHours: 0
          };
        }
      }

      function populateEmployees(employees) {
        tableBody.innerHTML = "";

        if (employees.length === 0) {
          tableBody.innerHTML = `
          <tr>
            <td colspan="21" class="px-4 py-3 text-center text-gray-500">No employees found.</td>
          </tr>`;
          return;
        }

        employees.forEach(emp => {
          const salary = parseFloat(emp.salary || 0);
          let additions = 0;
          let deductions = 0;
          let paye = calculatePAYE(salary - additions - deductions);
          let netSalary = salary - paye;
          let pension = netSalary * 0.05;
          let welfareFund = 2000;
          let baseNet = netSalary - pension - welfareFund;
          const weekdayOT = parseFloat(emp.weekday_overtime || 0);
          const weekendOT = parseFloat(emp.weekend_overtime || 0);
          const totalOT = weekdayOT + weekendOT;
          const hoursPerWeek = parseFloat(emp.hours_per_week || 0);

          let weekdayHours = 0;
          let weekendHours = 0;
          let theemployeeId = emp.id;

          fetchEmployeeAttendance(theemployeeId).then(data => {
            weekdayHours = data.weekdayHours;
            weekendHours = data.weekendHours;

            // Now you can use these variables anywhere
          });

          // Initialize buffer if not present
          if (!employeeDataBuffer[emp.id]) {
            employeeDataBuffer[emp.id] = {
              baseSalary: salary,
              paye,
              baseNet,
              additions,
              deductions,
              pension,
              welfareFund,
              weekdayOT,
              weekendOT
            };
          } else {
            additions = employeeDataBuffer[emp.id].additions;
            deductions = employeeDataBuffer[emp.id].deductions;
            paye = calculatePAYE(salary - additions - deductions);
            netSalary = salary - paye;
            pension = netSalary * 0.05;
            welfareFund = 2000;
            baseNet = netSalary - pension - welfareFund;
          }

          // Calculate hourly rate and overtime pay
          const hourlyRate = emp.hourly_rate;
          let weekendOTPay = weekendHours * hourlyRate * 2;
          let weekdayOTPay = weekdayHours * hourlyRate * 1.5;

          if (weekdayHours > emp.hours_per_weekday * 5) {
            let weekdayOTPay = (weekdayHours - emp.hours_per_weekday) * hourlyRate * 1.5;
          } else weekdayOTPay = 0;

          const overtimePay = weekdayOTPay + weekendOTPay;

          const currentNet = baseNet + overtimePay + additions - deductions;

          const row = document.createElement("tr");
          row.id = `employeeRow_${emp.id}`;
          row.setAttribute("data-id", emp.id);
          row.setAttribute("data-salary", salary);
          row.setAttribute("data-paye", paye);
          row.setAttribute("data-base-net", baseNet);
          row.setAttribute("data-net", currentNet);
          row.setAttribute("data-pension", pension);
          row.setAttribute("data-additions", additions);
          row.setAttribute("data-deductions", deductions);
          row.setAttribute("data-welfare-fund", welfareFund);
          row.setAttribute("data-hourly-rate", emp.hourly_rate);
          row.setAttribute("data-hours-per-weeknd", emp.hours_per_weekend);
          row.setAttribute("data-hours-per-weekday", emp.hours_per_weekday);
          row.setAttribute("data-hours-per-week", hoursPerWeek);
          row.setAttribute("data-bank-name", emp.bank_name || '');
          row.setAttribute("data-bank-branch", emp.bank_branch || '');
          row.setAttribute("data-bank-account-name", emp.bank_account_name || '');
          row.setAttribute("data-bank-account-number", emp.bank_account_number || '');
          row.setAttribute("data-branch-code", emp.branch_code || '');
          row.setAttribute("data-weekday-ot", weekdayOTPay);
          row.setAttribute("data-weekend-ot", weekendOTPay);
          row.setAttribute("data-total-ot", overtimePay);

          row.innerHTML = `
          <td class="px-4 py-3"><input type="checkbox" class="employeeCheckbox" value="${emp.id}"></td>
          <td class="px-4 py-3">${emp.name}</td>
          <td class="px-4 py-3">${emp.department}</td>
          <td class="px-4 py-3">${emp.gender}</td>
          <td class="px-4 py-3">MWK ${salary.toFixed(2)}</td>
          <td class="px-4 py-3">MWK ${paye.toFixed(2)}</td>
          <td class="px-4 py-3 net-cell" data-id="${emp.id}" data-salary="${currentNet}">MWK ${currentNet.toFixed(2)}</td>
          <td class="px-4 py-3 pension-cell">MWK ${pension.toFixed(2)}</td>
          <td class="px-4 py-3 additions-cell">MWK ${additions.toFixed(2)}</td>
          <td class="px-4 py-3 deductions-cell">MWK ${deductions.toFixed(2)}</td>
          <td class="px-4 py-3 welfare-cell">MWK ${welfareFund.toFixed(2)}</td>
          <td class="px-4 py-3">${emp.hourly_rate}</td>
          <!-- Add to your table rows -->
          <td class="px-4 py-3">
              <input type="number" class="weekend-hours w-full border p-2 rounded" 
                     value="${emp.weekend_overtime_hours}" min="0" step="0.5">
          </td>
          <td class="px-4 py-3">
              <input type="number" class="weekday-hours w-full border p-2 rounded" 
                     value="${emp.weekday_overtime_hours}" min="0" step="0.5">
          </td>
          <td class="px-4 py-3 weekend-ot-pay">${emp.weekend_overtime_pay}</td>
          <td class="px-4 py-3 weekday-ot-pay">${emp.weekday_overtime_pay}</td>
          <td class="px-4 py-3 total-ot-pay">${emp.total_overtime_pay}</td>

          <td class="px-4 py-3 hours-cell">${hoursPerWeek}</td>
          <td class="px-4 py-3 bank-name-cell">${emp.bank_name || ''}</td>
          <td class="px-4 py-3 bank-account-cell">${emp.bank_account_name || ''}</td>
          <td class="px-4 py-3 bank-account-number-cell">${emp.branch_code || ''}</td>
          <td class="px-4 py-3 bank-branch-cell">${emp.bank_branch || ''}</td>
          <td class="px-4 py-3 email-cell">${emp.email || ''}</td>
          <td class="px-4 py-3 text-center">
            <button onclick="openSalaryModal(${emp.id})"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
              Edit
            </button>
          </td>
        `;

          tableBody.appendChild(row);
        });

        updateTotals();
      }

      function calculateTotalPayroll(employees) {
        let total = 0;
        employees.forEach(emp => {
          total += parseFloat(emp.salary || 0);
        });

        const displayEl = document.getElementById("totalPayrollDisplay");
        if (displayEl) {
          displayEl.textContent = `MWK ${total.toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
        }
      }

      // Close modal
      window.closeBulkModal = function () {
        document.getElementById("bulkEditModal").classList.add("hidden");
      }

      // Apply bulk changes
      window.applyBulkChanges = function () {
        const additions = parseFloat(document.getElementById("bulkAdditions").value) || 0;
        const deductions = parseFloat(document.getElementById("bulkDeductions").value) || 0;
        const selectedCheckboxes = document.querySelectorAll('.employeeCheckbox:checked');

        if (selectedCheckboxes.length === 0) {
          notify.error("No employees selected.");
          return;
        }

        selectedCheckboxes.forEach(cb => {
          const id = cb.value;
          const row = document.querySelector(`#employeeTableBody tr[data-id="${id}"]`);

          const baseNet = parseFloat(row.dataset.baseNet) || 0;
          const net = baseNet + additions - deductions;
          const pension = baseNet * 0.05;

          row.dataset.additions = additions;
          row.dataset.deductions = deductions;
          row.dataset.net = net.toFixed(2);

          row.querySelector('.additions-cell').textContent = `MWK ${additions.toFixed(2)}`;
          row.querySelector('.deductions-cell').textContent = `MWK ${deductions.toFixed(2)}`;
          row.querySelector('.net-cell').textContent = `MWK ${net.toFixed(2)}`;
          row.querySelector('.pension-cell').textContent = `MWK ${pension}`;

          employeeDataBuffer[id].additions = additions;
          employeeDataBuffer[id].deductions = deductions;
        });

        updateTotals();
        closeBulkModal();
        notify.success("Bulk updates applied successfully!");
      }
      });

      // Salary Edit Modal logic
      window.openSalaryModal = function (employeeId) {
        const row = document.querySelector(`#employeeRow_${employeeId}`);
        document.getElementById("editEmployeeId").value = employeeId;

        const currentAdditions = parseFloat(row.dataset.additions || 0);
        const currentDeductions = parseFloat(row.dataset.deductions || 0);
        const baseNetSalary = parseFloat(row.dataset.baseNet || 0);
        document.getElementById("currentNetSalary").value = (baseNetSalary + currentAdditions - currentDeductions).toFixed(2);
        document.getElementById("additionsInput").value = currentAdditions.toFixed(2);
        document.getElementById("deductionsInput").value = currentDeductions.toFixed(2);

        document.getElementById("salaryEditModal").classList.remove("hidden");
      }

      window.closeSalaryModal = function () {
        document.getElementById("salaryEditModal").classList.add("hidden");
      }

      window.applySalaryChanges = function applySalaryChanges() {
        const id = document.getElementById('editEmployeeId').value;
        const additions = parseFloat(document.getElementById('additionsInput').value) || 0;
        const deductions = parseFloat(document.getElementById('deductionsInput').value) || 0;

        // Locate the row using data-id
        const row = document.querySelector(`#employeeTableBody tr[data-id="${id}"]`);
        if (!row) return;

        // Extract base salary and paye
        const baseSalary = parseFloat(row.dataset.salary) || 0;
        const paye = parseFloat(row.dataset.paye) || 0;
        const baseNet = parseFloat(row.dataset.baseNet) || 0;

        // Calculate new net salary
        const newNetSalary = baseNet + additions - deductions;
        const pension = baseNet * 0.05; // Pension is based on the original net salary

        // Update data attributes
        row.dataset.additions = additions;
        row.dataset.deductions = deductions;
        row.dataset.net = newNetSalary.toFixed(2);

        // Update visible text in the table
        row.querySelector('.additions-cell').textContent = `MWK ${additions.toFixed(2)}`;
        row.querySelector('.deductions-cell').textContent = `MWK ${deductions.toFixed(2)}`;
        row.querySelector('.net-cell').textContent = `MWK ${newNetSalary.toFixed(2)}`;
        row.querySelector('.pension-cell').textContent = `MWK ${pension}`;

        // Update the buffer
        employeeDataBuffer[id].additions = additions;
        employeeDataBuffer[id].deductions = deductions;

        // Close modal and recalculate totals
        closeSalaryModal();
        updateTotals();
      }

      function closePayrollModal() {
        document.getElementById("payrollModal").classList.add("hidden");
      }
      function showLoading() {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(overlay);
      }

      function hideLoading() {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
          overlay.remove();
        }
      }

      document.getElementById("generatePayrollBtn").addEventListener("click", function () {
        const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));
        if (selectedEmployees.length === 0) {
          alert("Please select at least one employee to generate payroll.");
          return;
        }

        // Generate PDF
        //generatePayrollPDF();

        // Generate Excel
        exportToExcel();
      });

      function NotUsed_exportToExcel() {
        const tableData = [];
        const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));

        // Add header row
        tableData.push([
          'Name', 'Department', 'Gender', 'Salary', 'PAYE', 'Net Salary',
          'Pension', 'Additions', 'Deductions', 'Welfare Fund', 'Hourly Rate',
          'Weekend Hrs', 'Weekday Hrs', 'Weekend OT', 'Weekday OT',
          'Total Overtime', 'Hours Per Week', 'Bank Name', 'Bank Account Name',
          'Bank Account Number', 'Bank Branch', 'Email'
        ]);

        // Add employee data
        selectedEmployees.forEach(checkbox => {
          const row = checkbox.closest('tr');
          tableData.push([
            row.querySelector('td:nth-child(2)').textContent.trim(),
            row.querySelector('td:nth-child(3)').textContent.trim(),
            row.querySelector('td:nth-child(4)').textContent.trim(),
            row.querySelector('td:nth-child(5)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(6)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(7)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(8)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(9)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(10)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(11)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(12)').textContent.trim(),
            row.querySelector('td:nth-child(13)').textContent.trim(),
            row.querySelector('td:nth-child(14)').textContent.trim(),
            row.querySelector('td:nth-child(15)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(16)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(17)').textContent.replace('MWK ', '').trim(),
            row.querySelector('td:nth-child(18)').textContent.trim(),
            row.querySelector('td:nth-child(19)').textContent.trim(),
            row.querySelector('td:nth-child(20)').textContent.trim(),
            row.querySelector('td:nth-child(21)').textContent.trim(),
            row.querySelector('td:nth-child(22)').textContent.trim(),
            row.querySelector('td:nth-child(23)').textContent.trim()
          ]);
        });

        // Add totals row
        tableData.push([
          'Totals',
          '',
          '',
          document.getElementById("totalGrossDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalPayeDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalNetDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalPensionDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalAdditionsDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalDeductionsDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalWelfareFund").textContent.replace('MWK ', '').trim(),
          '',
          '',
          '',
          '',
          //document.getElementById("totalOvertime").textContent.replace('MWK ', '').trim(),
          //document.getElementById("totalWeekendOvertime").textContent.replace('MWK ', '').trim(),
          //document.getElementById("totalWeekdayOvertime").textContent.replace('MWK ', '').trim(),
          //document.getElementById("totalWelfareFund").textContent.replace('MWK ', '').trim()
        ]);

        // Create CSV content with proper escaping
        const escapeCsvCell = (cell) => {
          if (cell === null || cell === undefined) return '';
          const str = String(cell);
          if (str.includes(',') || str.includes('"') || str.includes('\n')) {
            return `"${str.replace(/"/g, '""')}"`;
          }
          return str;
        };

        const csvContent = tableData.map(row =>
          row.map(cell => escapeCsvCell(cell)).join(',')
        ).join('\n');

        // Create and download the file
        const blob = new Blob([csvContent], {
          type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `payroll_report_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }

      function exportToExcel() {
        document.getElementById('loaderModal').style.display = 'flex';

        const tableData = [];
        const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));

        tableData.push([
          'Name', 'Department', 'Gender', 'Salary', 'PAYE', 'Net Salary',
          'Pension', 'Additions', 'Deductions', 'Welfare Fund', 'Hourly Rate',
          'Weekend Overtime Hours', 'Weekday Overtime Hours', 'Weekend Overtime Pay', 'Weekday Overtime Pay',
          'Total Overtime', 'Hours Per Week', 'Bank Name', 'Bank Account Name',
          'Bank Account Number', 'Bank Branch', 'Email'
        ]);

        let processed = 0;

        selectedEmployees.forEach((checkbox, index) => {
          const row = checkbox.closest('tr');
          const employeeData = {
            name: row.querySelector('td:nth-child(2)').textContent.trim(),
            department: row.querySelector('td:nth-child(3)').textContent.trim(),
            gender: row.querySelector('td:nth-child(4)').textContent.trim(),
            salary: row.querySelector('td:nth-child(5)').textContent.replace('MWK ', '').trim(),
            paye: row.querySelector('td:nth-child(6)').textContent.replace('MWK ', '').trim(),
            netSalary: row.querySelector('td:nth-child(7)').textContent.replace('MWK ', '').trim(),
            pension: row.querySelector('td:nth-child(8)').textContent.replace('MWK ', '').trim(),
            additions: row.querySelector('td:nth-child(9)').textContent.replace('MWK ', '').trim(),
            deductions: row.querySelector('td:nth-child(10)').textContent.replace('MWK ', '').trim(),
            welfareFund: row.querySelector('td:nth-child(11)').textContent.replace('MWK ', '').trim(),
            bankName: row.querySelector('td:nth-child(19)').textContent.trim(),
            bankAccountName: row.querySelector('td:nth-child(20)').textContent.trim(),
            bankAccountNumber: row.querySelector('td:nth-child(21)').textContent.trim(),
            bankBranch: row.querySelector('td:nth-child(22)').textContent.trim(),
            email: row.querySelector('td:nth-child(23)').textContent.trim()
          };

          fetch('send_payslip.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(employeeData)
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                console.log(`✅ Email sent successfully to ${employeeData.email}`);
              } else {
                console.error(`❌ Failed to send email to ${employeeData.email}:`, data.error);
              }
            })
            .catch(error => {
              console.error(`❌ Error sending email to ${employeeData.email}:`, error);
            })
            .finally(() => {
              processed++;
              if (processed === selectedEmployees.length) {
                document.getElementById('loaderModal').style.display = 'none';
                const notyf = new Notyf();
                notyf.success('Payslips processing complete!');
              }
            });


          tableData.push([
            employeeData.name, employeeData.department, employeeData.gender,
            employeeData.salary, employeeData.paye, employeeData.netSalary,
            employeeData.pension, employeeData.additions, employeeData.deductions,
            employeeData.welfareFund, '', '', '', '', '', '', '',
            employeeData.bankName, employeeData.bankAccountName,
            employeeData.bankAccountNumber, employeeData.bankBranch,
            employeeData.email
          ]);
        });

        tableData.push([
          'Totals',
          '',
          '',
          document.getElementById("totalGrossDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalPayeDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalNetDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalPensionDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalAdditionsDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalDeductionsDisplay").textContent.replace('MWK ', '').trim(),
          document.getElementById("totalWelfareFund").textContent.replace('MWK ', '').trim(),
          '',
          '',
          '',
          '',
        ]);

        const escapeCsvCell = (cell) => {
          if (cell === null || cell === undefined) return '';
          const str = String(cell);
          if (str.includes(',') || str.includes('"') || str.includes('\n')) {
            return `"${str.replace(/"/g, '""')}"`;
          }
          return str;
        };

        const csvContent = tableData.map(row =>
          row.map(cell => escapeCsvCell(cell)).join(',')
        ).join('\n');

        const blob = new Blob([csvContent], {
          type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `payroll_report_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }

      document.addEventListener('DOMContentLoaded', function () {
        // Initialize Notyf
        const notyf = new Notyf({
          duration: 3000,
          position: {
            x: 'right',
            y: 'top',
          },
          types: [
            {
              type: 'success',
              background: '#4CAF50',
              icon: {
                className: 'ti-check',
                tagName: 'i',
                text: 'Success'
              }
            },
            {
              type: 'error',
              background: '#f44336',
              icon: {
                className: 'ti-close',
                tagName: 'i',
                text: 'Error'
              }
            }
          ]
        });

        // Initialize Notify.js
        $.notify.defaults({
          className: "success",
          position: "top right",
          autoHide: true,
          clickToHide: true
        });
      });
      function showNotification(type, message) {
        try {
          if (type === 'success' || type === 'error') {
            notyf.open({
              type: type,
              message: message,
              duration: 3000
            });
          } else {
            $.notify(message, {
              className: type,
              position: "top right"
            });
          }
        } catch (error) {
          console.error('Notification error:', error);
        }
      }

      function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
      }

      // Add to your existing JavaScript code
      function updateOvertimePay(row, weekendHours, weekdayHours) {
          const hourlyRate = parseFloat(row.dataset.hourlyRate);
          const weekendOTPay = weekendHours * hourlyRate * 2;
          const weekdayOTPay = weekdayHours * hourlyRate * 1.5;
          const totalOTPay = weekendOTPay + weekdayOTPay;

          // Update cells
          row.querySelector('.weekend-hours').value = weekendHours;
          row.querySelector('.weekday-hours').value = weekdayHours;
          row.querySelector('.weekend-ot-pay').textContent = weekendOTPay.toFixed(2);
          row.querySelector('.weekday-ot-pay').textContent = weekdayOTPay.toFixed(2);
          row.querySelector('.total-ot-pay').textContent = totalOTPay.toFixed(2);

          // Update data attributes
          row.dataset.weekendOvertime = weekendOTPay;
          row.dataset.weekdayOvertime = weekdayOTPay;
          row.dataset.totalOvertime = totalOTPay;

          updateTotals();
      }

      function editOvertimeHours(employeeId) {
          const row = document.querySelector(`#employeeRow_${employeeId}`);
          const modal = document.getElementById('overtimeModal');
          const weekendInput = modal.querySelector('#weekendHours');
          const weekdayInput = modal.querySelector('#weekdayHours');

          weekendInput.value = row.querySelector('.weekend-hours').value;
          weekdayInput.value = row.querySelector('.weekday-hours').value;

          modal.querySelector('.modal-body').dataset.employeeId = employeeId;
          modal.style.display = 'block';
      }

      function saveOvertimeHours() {
          const modal = document.getElementById('overtimeModal');
          const employeeId = modal.querySelector('.modal-body').dataset.employeeId;
          const weekendHours = parseFloat(modal.querySelector('#weekendHours').value) || 0;
          const weekdayHours = parseFloat(modal.querySelector('#weekdayHours').value) || 0;
          const row = document.querySelector(`#employeeRow_${employeeId}`);

          updateOvertimePay(row, weekendHours, weekdayHours);
          modal.style.display = 'none';
      }
    </script>
  </body>
</html>