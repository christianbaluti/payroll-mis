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
    <title>HR Payroll - Payroll Drafts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet" />
    <link href="../../themify/themify-icons.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="./../../includes/logo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

      /* Style for editable input fields in the table */
      .editable-input {
          border: 1px solid #ccc;
          padding: 4px;
          border-radius: 4px;
          width: 80px; /* Adjust width as needed */
          text-align: right;
      }
    </style>
  </head>

  <body class="bg-gray-100 h-screen overflow-hidden flex">
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
                <a href="./payrun.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-100">
                  <div class="p-1.5 rounded-md bg-gradient-to-tr from-gray-500 to-blue-300 text-white text-xs">
                    <i class="ti-pencil-alt text-xs"></i>
                  </div>
                  <span>Payrun</span>
                </a>
              </li>
              <li>
                <a href="./drafts.php" class="flex items-center space-x-2 px-3 py-1 text-gray-700 rounded-lg hover:bg-blue-200 bg-blue-100">
                  <div class="p-1.5 rounded-md bg-gradient-to-tr from-gray-500 to-blue-300 text-white text-xs">
                    <i class="ti-control-play text-xs"></i>
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

    <div class="flex-1 flex flex-col min-h-screen overflow-y-auto">

      <header
      class="bg-white sticky top-0 z-10 shadow flex justify-between items-center px-4 py-3 border-b">
        <div class="flex items-center gap-3">
          <button onclick="toggleSidebar()"
            class="md:hidden text-blue-600 text-2xl focus:outline-none">
            <i class="ti-layout-grid2-alt"></i>
          </button>
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

        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-blue-600 flex items-center">
            Payroll Drafts
          </h1>
        </div>
        <div class="bg-white p-4 rounded-lg shadow mb-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select id="monthYearFilter" class="border p-2 rounded w-full">
              <option value="">Select Month & Year</option>
            </select>
            <select id="uniqueIdFilter" class="border p-2 rounded w-full">
              <option value="">Select Unique ID</option>
            </select>
          </div>
        </div>

        <div id="loaderModal" class="modal" style="display: none; justify-content: center; align-items: center; background: rgba(0,0,0,0.7);">
            <div class="flex flex-col items-center bg-white p-6 rounded-lg shadow-xl">
                <div class="loading-spinner"></div>
                <p class="text-gray-700 mt-3">Loading Payroll Data...</p>
            </div>
        </div>

        <div id="bulkHoursModal" class="modal">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="text-xl font-bold">Bulk Edit Overtime Hours</h3>
              <span class="modal-close" onclick="closeModal('bulkHoursModal')">&times;</span>
            </div>
            <div class="modal-body">
              <div class="mb-4">
                <label class="block mb-2">Weekend Hours:</label>
                <input type="number" id="bulkWeekendHours" class="w-full border p-2 rounded" min="0" step="0.5">
              </div>
              <div class="mb-4">
                <label class="block mb-2">Weekday Hours:</label>
                <input type="number" id="bulkWeekdayHours" class="w-full border p-2 rounded" min="0" step="0.5">
              </div>
            </div>
            <div class="modal-footer">
              <button onclick="closeModal('bulkHoursModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
              <button onclick="applyBulkHours()" class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
            </div>
          </div>
        </div>

        <div id="confirmAuthorizeModal" class="modal">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="text-xl font-bold">Confirm Payroll Authorization</h3>
              <span class="modal-close" onclick="closeModal('confirmAuthorizeModal')">&times;</span>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to authorize this payroll? This will finalize the payroll and send payslips to employees.</p>
            </div>
            <div class="modal-footer">
              <button onclick="closeModal('confirmAuthorizeModal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
              <button onclick="authorizePayroll()" class="bg-blue-600 text-white px-4 py-2 rounded">Authorize</button>
            </div>
          </div>
        </div>

        <div id="totalsSection" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-6 text-xs text-white font-semibold">
          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-wallet text-2xl text-blue-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Gross Pay</h3>
              <p id="totalGrossDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-money text-2xl text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Net Pay</h3>
              <p id="totalNetDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-bar-chart-alt text-2xl text-blue-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total PAYE</h3>
              <p id="totalPayeDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-briefcase text-2xl text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Pension (5%)</h3>
              <p id="totalPensionDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-green-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-plus text-2xl text-green-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Additions</h3>
              <p id="totalAdditionsDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-green-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-minus text-2xl text-green-700"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Deductions</h3>
              <p id="totalDeductionsDisplay" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-red-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-heart-broken text-2xl text-red-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Welfare Fund</h3>
              <p id="totalWelfareFund" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-pink-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-calendar text-2xl text-pink-600"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Weekend OT Pay</h3>
              <p id="totalWeekendOvertime" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-yellow-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-calendar text-2xl text-yellow-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Weekday OT Pay</h3>
              <p id="totalWeekdayOvertime" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-gray-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-time text-2xl text-gray-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total Overtime Pay</h3>
              <p id="totalOvertime" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>
        </div> <br>

        <div class="mb-4 text-right">
          <button onclick="openModal('bulkHoursModal')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Bulk Edit Hours
          </button>
          <button id="bulkEditBtn" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            Bulk Edit Additions/Deductions
          </button>
           <button onclick="exportToExcel()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Export to Excel
          </button>
          <button onclick="openModal('confirmAuthorizeModal')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Authorize Payroll
          </button>
        </div>

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
              </tbody>
          </table>
        </div>
        <div id="bulkEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
          <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Bulk Edit Additions & Deductions</h2>

            <label class="block mb-2">Additions (MWK):</label>
            <input type="number" id="bulkAdditions" class="w-full border p-2 mb-4 rounded" placeholder="0.00" value="0" min="0">

            <label class="block mb-2">Deductions (MWK):</label>
            <input type="number" id="bulkDeductions" class="w-full border p-2 mb-4 rounded" placeholder="0.00" value="0" min="0">

            <div class="flex justify-end space-x-2">
              <button onclick="closeBulkModal()" class="bg-gray-400 text-white px-3 py-1 rounded">Cancel</button>
              <button onclick="applyBulkChanges()" class="bg-blue-600 text-white px-3 py-1 rounded">Apply</button>
            </div>
          </div>
        </div>

        <div id="salaryEditModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
          <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-4 text-blue-600">Edit Net Salary Components</h2>
            <input type="hidden" id="editPayslipId"> <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Base Net Salary</label>
               <input type="text" id="baseNetSalaryDisplay" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Additions (e.g. bonus)</label>
              <input type="number" id="additionsInput" class="border p-2 rounded w-full" value="0" min="0" step="0.01">
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Deductions (e.g. advance)</label>
              <input type="number" id="deductionsInput" class="border p-2 rounded w-full" value="0" min="0" step="0.01">
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Current Net Salary (Calculated)</label>
              <input type="text" id="currentNetSalary" class="border p-2 rounded w-full bg-gray-100" readonly>
            </div>

            <div class="flex justify-end space-x-2">
              <button onclick="closeSalaryModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
              <button onclick="applySalaryChanges()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
          </div>
        </div>
        </main>
    </div>

  <script type="text/javascript">
      let payrollData = {}; // Store the currently loaded payroll data
      let payslipDataBuffer = {}; // Buffer for inline edits

      // Sidebar toggle function
      function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
          sidebar.classList.toggle('-translate-x-full');
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

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (event) => {
          // Only run this logic on mobile screens
          if (window.innerWidth <= 768) {
            const toggleButton = document.querySelector('.md\\:hidden');

            // If sidebar is open and click is outside sidebar and not on toggle button
            if (!sidebar.classList.contains('-translate-x-full') &&
                !sidebar.contains(event.target) &&
                (!toggleButton || !toggleButton.contains(event.target))) {

              sidebar.classList.add('-translate-x-full');
            }
          }
        });

        // Load months and years on page load
        loadPayrollMonths();

        // Add event listeners to filter elements
        const monthYearSelect = document.getElementById("monthYearFilter");
        const uniqueIdSelect = document.getElementById("uniqueIdFilter");
        const selectAll = document.getElementById("selectAllEmployees");
        const tableBody = document.getElementById("employeeTableBody");

        monthYearSelect.addEventListener("change", () => {
            const selectedMonthYear = monthYearSelect.value;
            if (selectedMonthYear) {
                const [month, year] = selectedMonthYear.split('-');
                loadUniqueIds(month, year);
            } else {
                uniqueIdSelect.innerHTML = `<option value="">Select Unique ID</option>`;
                clearPayrollData(); // Clear table and totals if no month/year selected
            }
        });

        uniqueIdSelect.addEventListener("change", () => {
            const uniqueId = uniqueIdSelect.value;
            if (uniqueId) {
                fetchPayrollData(uniqueId);
            } else {
                clearPayrollData(); // Clear table and totals if no unique ID selected
            }
        });

        selectAll.addEventListener('change', () => {
            document.querySelectorAll('.employeeCheckbox').forEach(cb => cb.checked = selectAll.checked);
             updateTotalsDisplay(); // Update totals after selecting all
        });

        // Add event delegation for editable input fields in the table
        tableBody.addEventListener('input', (event) => {
            const target = event.target;
            if (target.classList.contains('editable-input')) {
                const row = target.closest('tr');
                const payslipId = row.dataset.payslipId;
                const fieldName = target.dataset.field;
                const value = parseFloat(target.value) || 0;

                // Update the buffer
                if (!payslipDataBuffer[payslipId]) {
                    // Initialize buffer entry if it doesn't exist, copying current data
                    const currentPayslip = payrollData.payslips.find(p => p.id == payslipId);
                     if (currentPayslip) {
                        payslipDataBuffer[payslipId] = { ...currentPayslip };
                     } else {
                         console.error("Payslip not found in loaded data:", payslipId);
                         return;
                     }
                }
                payslipDataBuffer[payslipId][fieldName] = value;

                // Recalculate and update the row and totals
                updatePayslipRow(payslipId);
                updateTotalsDisplay();

                // Optionally, send update to backend immediately or in batches
                // For this example, we'll update on blur or button click
            }
        });

         // Add event listener for blur on editable inputs to trigger backend update
         tableBody.addEventListener('blur', (event) => {
             const target = event.target;
             if (target.classList.contains('editable-input')) {
                 const row = target.closest('tr');
                 const payslipId = row.dataset.payslipId;
                 // Send update to backend for this specific payslip
                 sendPayslipUpdate(payslipId);
             }
         }, true); // Use capture phase to ensure blur is caught

        // Open bulk modal for additions/deductions
        document.getElementById("bulkEditBtn").addEventListener("click", () => {
            const selected = [...document.querySelectorAll('.employeeCheckbox:checked')];
            if (selected.length === 0) {
                Swal.fire('Error!', 'Please select at least one employee.', 'error');
                return;
            }
            document.getElementById("bulkEditModal").classList.remove("hidden");
        });

         // Monitor individual selections for total updates
        tableBody.addEventListener("change", (e) => {
            if (e.target.type === "checkbox") {
                updateTotalsDisplay();
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

      // Apply bulk hours
      function applyBulkHours() {
          const selectedEmployees = document.querySelectorAll('.employeeCheckbox:checked');
          if (selectedEmployees.length === 0) {
              Swal.fire('Error!', 'Please select at least one employee to apply bulk hours.', 'error');
              return;
          }

          const weekendHours = parseFloat(document.getElementById('bulkWeekendHours').value) || 0;
          const weekdayHours = parseFloat(document.getElementById('bulkWeekdayHours').value) || 0;

          selectedEmployees.forEach(checkbox => {
              const row = checkbox.closest('tr');
              const payslipId = row.dataset.payslipId;

              // Update the buffer
              if (!payslipDataBuffer[payslipId]) {
                 const currentPayslip = payrollData.payslips.find(p => p.id == payslipId);
                 if (currentPayslip) {
                    payslipDataBuffer[payslipId] = { ...currentPayslip };
                 } else {
                     console.error("Payslip not found in loaded data:", payslipId);
                     return;
                 }
              }
              payslipDataBuffer[payslipId].weekend_overtime_hours = weekendHours;
              payslipDataBuffer[payslipId].weekday_overtime_hours = weekdayHours;

              // Update the input fields in the table
              row.querySelector('.weekend-hours-input').value = weekendHours.toFixed(2);
              row.querySelector('.weekday-hours-input').value = weekdayHours.toFixed(2);

              // Recalculate and update the row display
              updatePayslipRow(payslipId);
          });

          // Send bulk update to backend (optional, could do one request per payslip or a single bulk request)
          // For simplicity, we'll just update the UI and send individual updates on blur or authorize
          // A proper bulk update backend endpoint would be more efficient.

          updateTotalsDisplay(); // Update overall totals
          closeModal('bulkHoursModal');
          Swal.fire('Success!', 'Bulk overtime hours applied. Remember to authorize payroll to save changes.', 'success');
      }

      // Authorize payroll
      async function authorizePayroll() {
          const uniqueId = document.getElementById('uniqueIdFilter').value;
          if (!uniqueId) {
              Swal.fire('Error!', 'Please select a payroll draft first.', 'error');
              closeModal('confirmAuthorizeModal');
              return;
          }

          showLoading(); // Show loading overlay

          try {
              const response = await fetch('api/authorize_payroll.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({ unique_id: uniqueId })
              });

              const result = await response.json();
              hideLoading(); // Hide loading overlay
              closeModal('confirmAuthorizeModal');

              if (result.success) {
                  Swal.fire('Success!', 'Payroll authorized and payslips sent.', 'success');
                  // Optionally, reload the drafts list or mark this one as authorized
                  loadPayrollMonths(); // Reload months to reflect status if needed
                  clearPayrollData(); // Clear the current view
              } else {
                  Swal.fire('Error!', result.message || 'Failed to authorize payroll', 'error');
              }
          } catch (error) {
              console.error('Error authorizing payroll:', error);
              hideLoading(); // Hide loading overlay
              closeModal('confirmAuthorizeModal');
              Swal.fire('Error!', 'Failed to authorize payroll', 'error');
          }
      }

      // Export to Excel
      function exportToExcel() {
          const uniqueId = document.getElementById('uniqueIdFilter').value;
          if (!uniqueId) {
              Swal.fire('Error!', 'Please select a payroll draft first.', 'error');
              return;
          }

          // Redirect to the backend script to generate and download the Excel file
          window.location.href = `api/export_payroll_excel.php?unique_id=${uniqueId}`;
      }


      // Update totals display from the loaded payrollData or buffer
      function updateTotalsDisplay() {
          // Use data from the main payrollData object, as individual payslip updates
          // should eventually update the backend which updates the payroll totals.
          // If inline edits don't immediately update backend totals, you'd need to sum from the buffer.
          // For this implementation, we assume backend updates totals on payslip update.
          const totals = payrollData.totals || {};

          document.getElementById("totalGrossDisplay").textContent = `MWK ${(totals.total_gross_pay || 0).toFixed(2)}`;
          document.getElementById("totalNetDisplay").textContent = `MWK ${(totals.total_net_pay || 0).toFixed(2)}`;
          document.getElementById("totalPayeDisplay").textContent = `MWK ${(totals.total_paye || 0).toFixed(2)}`;
          document.getElementById("totalAdditionsDisplay").textContent = `MWK ${(totals.total_addition || 0).toFixed(2)}`;
          document.getElementById("totalDeductionsDisplay").textContent = `MWK ${(totals.total_deduction || 0).toFixed(2)}`;
          document.getElementById("totalPensionDisplay").textContent = `MWK ${(totals.total_pension || 0).toFixed(2)}`;
          document.getElementById("totalWelfareFund").textContent = `MWK ${(totals.total_welfare_fund || 0).toFixed(2)}`;
          document.getElementById("totalWeekendOvertime").textContent = `MWK ${(totals.total_weekend_overtime_pay || 0).toFixed(2)}`;
          document.getElementById("totalWeekdayOvertime").textContent = `MWK ${(totals.total_weekday_overtime_pay || 0).toFixed(2)}`;
          document.getElementById("totalOvertime").textContent = `MWK ${(totals.total_overtime_pay || 0).toFixed(2)}`;
      }


      // ========== Helper Functions ==========

      // Function to load available payroll months and years
      function loadPayrollMonths() {
        fetch('api/get_payroll_months.php')
          .then(res => res.json())
          .then(data => {
            const monthYearSelect = document.getElementById("monthYearFilter");
            monthYearSelect.innerHTML = `<option value="">Select Month & Year</option>`;
            if (data.success && data.months) {
              data.months.forEach(item => {
                const option = document.createElement("option");
                // Format: MonthName-Year (e.g., January-2023)
                const monthNames = ["January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"];
                const monthName = monthNames[item.month - 1]; // month is 1-indexed
                option.value = `${item.month}-${item.year}`;
                option.text = `${monthName} ${item.year}`;
                monthYearSelect.appendChild(option);
              });
            } else {
              console.error("Error loading payroll months:", data.message || "Unknown error");
            }
          })
          .catch(err => console.error('Error loading payroll months:', err));
      }

      // Function to load unique IDs for a selected month and year
      function loadUniqueIds(month, year) {
        fetch(`api/get_unique_ids_by_month.php?month=${month}&year=${year}`)
          .then(res => res.json())
          .then(data => {
            const uniqueIdSelect = document.getElementById("uniqueIdFilter");
            uniqueIdSelect.innerHTML = `<option value="">Select Unique ID</option>`;
            if (data.success && data.unique_ids) {
              data.unique_ids.forEach(item => {
                const option = document.createElement("option");
                option.value = item.unique_id;
                option.text = item.unique_id;
                uniqueIdSelect.appendChild(option);
              });
            } else {
               console.error("Error loading unique IDs:", data.message || "Unknown error");
            }
          })
          .catch(err => console.error('Error loading unique IDs:', err));
      }

      // Function to fetch and display payroll data for a selected unique ID
      function fetchPayrollData(uniqueId) {
          showLoading();
          fetch(`api/get_payroll_data.php?unique_id=${uniqueId}`)
              .then(res => res.json())
              .then(data => {
                  hideLoading();
                  if (data.success) {
                      payrollData = data.payroll;
                      payslipData = data.payslips;
                      payslipDataBuffer = {};
                      populateEmployeesTable(payslipData);
                      console.log(payslipData);
                      updateTotalsDisplay();
                  } else {
                      console.error("Error fetching payroll data:", data.message || "Unknown error");
                      Swal.fire('Error!', data.message || 'Failed to load payroll data.', 'error');
                      clearPayrollData();
                  }
              })
              .catch(err => {
                  console.error('Fetch payroll data failed:', err);
                  hideLoading();
                  Swal.fire('Error!', 'Failed to fetch payroll data.', 'error');
                  clearPayrollData();
              });
      }

      // Function to populate the employee table with payslip data
      function populateEmployeesTable(payslips) {
        const tableBody = document.getElementById("employeeTableBody");
        tableBody.innerHTML = "";

        if (!payslips || payslips.length === 0) {
          tableBody.innerHTML = `
          <tr>
            <td colspan="23" class="px-4 py-3 text-center text-gray-500">No employee data found for this payroll.</td>
          </tr>`;
          return;
        }

        payslips.forEach(payslip => {
          const row = document.createElement("tr");
          row.id = `payslipRow_${payslip.id}`;
          row.setAttribute("data-payslip-id", payslip.id);
          // Store original data attributes for calculations
          row.setAttribute("data-gross-salary", payslip.gross_salary);
          row.setAttribute("data-welfare-fund", payslip.welfare_fund);
          row.setAttribute("data-hourly-rate", payslip.hourly_rate);


          row.innerHTML = `
          <td class="px-4 py-3"><input type="checkbox" class="employeeCheckbox" value="${payslip.id}"></td>
          <td class="px-4 py-3">${payslip.name}</td>
          <td class="px-4 py-3">${payslip.department}</td>
          <td class="px-4 py-3">${payslip.Gender}</td>
          <td class="px-4 py-3">MWK ${parseFloat(payslip.gross_salary).toFixed(2)}</td>
          <td class="px-4 py-3 paye-cell">MWK ${parseFloat(payslip.paye).toFixed(2)}</td>
          <td class="px-4 py-3 net-cell">MWK ${calculateNetSalary(payslip).toFixed(2)}</td>
          <td class="px-4 py-3 pension-cell">MWK ${parseFloat(payslip.pension).toFixed(2)}</td>
          <td class="px-4 py-3 additions-cell">
              <input type="number" class="editable-input additions-input" data-field="additions"
                     value="${parseFloat(payslip.additions).toFixed(2)}" min="0" step="0.01">
          </td>
          <td class="px-4 py-3 deductions-cell">
              <input type="number" class="editable-input deductions-input" data-field="deductions"
                     value="${parseFloat(payslip.deductions).toFixed(2)}" min="0" step="0.01">
          </td>
          <td class="px-4 py-3 welfare-cell">MWK ${parseFloat(payslip.welfare_fund).toFixed(2)}</td>
          <td class="px-4 py-3">${parseFloat(payslip.hourly_rate).toFixed(2)}</td>
          <td class="px-4 py-3">
              <input type="number" class="editable-input weekend-hours-input" data-field="weekend_overtime_hours"
                     value="${parseFloat(payslip.weekend_overtime_hours).toFixed(2)}" min="0" step="0.5">
          </td>
          <td class="px-4 py-3">
              <input type="number" class="editable-input weekday-hours-input" data-field="weekday_overtime_hours"
                     value="${parseFloat(payslip.weekday_overtime_hours).toFixed(2)}" min="0" step="0.5">
          </td>
          <td class="px-4 py-3 weekend-ot-pay-cell">MWK ${parseFloat(payslip.weekend_overtime_pay).toFixed(2)}</td>
          <td class="px-4 py-3 weekday-ot-pay-cell">MWK ${parseFloat(payslip.weekday_overtime_pay).toFixed(2)}</td>
           <td class="px-4 py-3 total-ot-pay-cell">MWK ${parseFloat(payslip.total_overtime_pay).toFixed(2)}</td>
          <td class="px-4 py-3">${payslip.hours_per_month || ''}</td> <td class="px-4 py-3">${payslip.bank_name || ''}</td>
          <td class="px-4 py-3">${payslip.bank_account_name || ''}</td>
          <td class="px-4 py-3">${payslip.bank_account_number || ''}</td>
          <td class="px-4 py-3">${payslip.bank_branch || ''}</td>
          <td class="px-4 py-3">${payslip.email || ''}</td>
          <td class="px-4 py-3 text-center">
            <button onclick="openSalaryModal(${payslip.id})"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
              Edit
            </button>
          </td>
        `;

          tableBody.appendChild(row);
        });
         // Initial update of totals based on loaded data
         updateTotalsDisplay();
      }

      // Function to update a single payslip row display after inline edit or modal edit
      function updatePayslipRow(payslipId) {
          const row = document.querySelector(`#payslipRow_${payslipId}`);
          if (!row) return;

          // Get the latest data from the buffer or original data if not in buffer
          const payslip = payslipDataBuffer[payslipId] || payrollData.payslips.find(p => p.id == payslipId);
          if (!payslip) return;

          // Recalculate dependent fields
          const grossSalary = parseFloat(row.dataset.grossSalary || 0);
          const hourlyRate = parseFloat(row.dataset.hourlyRate || 0);
          const welfareFund = parseFloat(row.dataset.welfareFund || 0); // Assuming fixed welfare fund

          const weekendHours = parseFloat(payslip.weekend_overtime_hours || 0);
          const weekdayHours = parseFloat(payslip.weekday_overtime_hours || 0);
          const weekendOTPay = weekendHours * hourlyRate * 2;
          const weekdayOTPay = weekdayHours * hourlyRate * 1.5;
          const totalOTPay = weekendOTPay + weekdayOTPay;

          const taxableIncome = grossSalary + parseFloat(payslip.additions || 0) - parseFloat(payslip.deductions || 0);
          const paye = calculatePAYE(taxableIncome);
          const netSalaryBeforePension = grossSalary - paye;
          const pension = netSalaryBeforePension * 0.05;
          const baseNet = netSalaryBeforePension - pension - welfareFund;
          const currentNet = baseNet + totalOTPay + parseFloat(payslip.additions || 0) - parseFloat(payslip.deductions || 0);


          // Update the displayed values in the row
          row.querySelector('.paye-cell').textContent = `MWK ${paye.toFixed(2)}`;
          row.querySelector('.net-cell').textContent = `MWK ${currentNet.toFixed(2)}`;
          row.querySelector('.pension-cell').textContent = `MWK ${pension.toFixed(2)}`;
          row.querySelector('.additions-input').value = parseFloat(payslip.additions).toFixed(2);
          row.querySelector('.deductions-input').value = parseFloat(payslip.deductions).toFixed(2);
          row.querySelector('.weekend-hours-input').value = weekendHours.toFixed(2);
          row.querySelector('.weekday-hours-input').value = weekdayHours.toFixed(2);
          row.querySelector('.weekend-ot-pay-cell').textContent = `MWK ${weekendOTPay.toFixed(2)}`;
          row.querySelector('.weekday-ot-pay-cell').textContent = `MWK ${weekdayOTPay.toFixed(2)}`;
          row.querySelector('.total-ot-pay-cell').textContent = `MWK ${totalOTPay.toFixed(2)}`;

          // Update the payslip object in the buffer with recalculated values
           if (payslipDataBuffer[payslipId]) {
               payslipDataBuffer[payslipId].paye = paye;
               payslipDataBuffer[payslipId].pension = pension;
               payslipDataBuffer[payslipId].weekend_overtime_pay = weekendOTPay;
               payslipDataBuffer[payslipId].weekday_overtime_pay = weekdayOTPay;
               payslipDataBuffer[payslipId].total_overtime_pay = totalOTPay;
               // Assuming total_overtime_hours is sum of weekend and weekday hours
               payslipDataBuffer[payslipId].total_overtime_hours = weekendHours + weekdayHours;
               // Net salary calculation needs to be consistent with backend
               // For now, update the buffer's net salary based on the frontend calculation
               // A better approach is to get the updated net salary from the backend after saving
               payslipDataBuffer[payslipId].net_salary = currentNet;
           }

      }

      // Function to send updated payslip data to the backend
      async function sendPayslipUpdate(payslipId) {
          const updatedData = payslipDataBuffer[payslipId];

          if (!updatedData) return; // No changes for this payslip

          showLoading();
          try {
              const response = await fetch('api/update_payslip_data.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(updatedData)
              });

              const result = await response.json();
              hideLoading();

              if (result.success) {
                  // Update the main payrollData with the saved changes and recalculated values from backend
                  const index = payrollData.payslips.findIndex(p => p.id == payslipId);
                  if (index !== -1) {
                      payrollData.payslips[index] = result.updated_payslip;
                  }
                   // Update payroll totals from the backend response
                  payrollData.totals = result.updated_totals;

                  // Clear the buffer entry after successful save
                  delete payslipDataBuffer[payslipId];

                  Swal.fire('Success!', 'Payslip updated successfully.', 'success');
                  updateTotalsDisplay(); // Update totals display with new totals
              } else {
                  console.error("Error updating payslip:", result.message || "Unknown error");
                  Swal.fire('Error!', result.message || 'Failed to update payslip.', 'error');
                  // Optionally, revert the UI changes for this row
              }
          } catch (error) {
              console.error('Send payslip update failed:', error);
              hideLoading();
              Swal.fire('Error!', 'Failed to update payslip.', 'error');
               // Optionally, revert the UI changes for this row
          }
      }


      // Function to calculate PAYE (should match backend logic)
      function calculatePAYE(taxableIncome) {
          let totalTax = 0;
          let remaining = taxableIncome;

          if (remaining <= 100000) { // Increased tax free bracket
              return 0;
          }
          remaining -= 100000;

          if (remaining >= 400000) { // Increased threshold for 25%
              totalTax += 400000 * 0.25;
              remaining -= 400000;
          } else {
              totalTax += remaining * 0.25;
              return totalTax;
          }

          if (remaining >= 2500000) { // Increased threshold for 30%
              totalTax += 2500000 * 0.30;
              remaining -= 2500000;
          } else {
              totalTax += remaining * 0.30;
              return totalTax;
          }

          totalTax += remaining * 0.35; // Top bracket at 35%
          return totalTax;
      }

      // Function to calculate Net Salary (should match backend logic)
      function calculateNetSalary(payslip) {
          const grossSalary = parseFloat(payslip.gross_salary || 0);
          const additions = parseFloat(payslip.additions || 0);
          const deductions = parseFloat(payslip.deductions || 0);
          const pension = parseFloat(payslip.pension || 0);
          const welfareFund = parseFloat(payslip.welfare_fund || 0);
          const weekendOTPay = parseFloat(payslip.weekend_overtime_pay || 0);
          const weekdayOTPay = parseFloat(payslip.weekday_overtime_pay || 0);
          const paye = parseFloat(payslip.paye || 0); // Use the calculated PAYE

          // Net Salary = Gross Salary - PAYE - Pension - Welfare Fund + Additions - Deductions + Total Overtime Pay
          const netSalary = grossSalary - paye - pension - welfareFund + additions - deductions + weekendOTPay + weekdayOTPay;
          return netSalary;
      }


      // Clear table and totals
      function clearPayrollData() {
          document.getElementById("employeeTableBody").innerHTML = `
           <tr>
             <td colspan="23" class="px-4 py-3 text-center text-gray-500">Select a month & year and unique ID to view payroll data.</td>
           </tr>`;
          payrollData = {}; // Clear stored data
          payslipDataBuffer = {}; // Clear buffer
          updateTotalsDisplay(); // Reset totals to 0
      }


      // Close bulk modal
      window.closeBulkModal = function () {
        document.getElementById("bulkEditModal").classList.add("hidden");
      }

      // Apply bulk changes (Additions/Deductions)
      window.applyBulkChanges = function () {
        const additions = parseFloat(document.getElementById("bulkAdditions").value) || 0;
        const deductions = parseFloat(document.getElementById("bulkDeductions").value) || 0;
        const selectedCheckboxes = document.querySelectorAll('.employeeCheckbox:checked');

        if (selectedCheckboxes.length === 0) {
          Swal.fire('Error!', "No employees selected.", 'error');
          return;
        }

        selectedCheckboxes.forEach(cb => {
          const payslipId = cb.value;
          const row = document.querySelector(`#payslipRow_${payslipId}`);
          if (!row) return;

          // Update the buffer
          if (!payslipDataBuffer[payslipId]) {
             const currentPayslip = payrollData.payslips.find(p => p.id == payslipId);
             if (currentPayslip) {
                payslipDataBuffer[payslipId] = { ...currentPayslip };
             } else {
                 console.error("Payslip not found in loaded data:", payslipId);
                 return;
             }
          }
          payslipDataBuffer[payslipId].additions = additions;
          payslipDataBuffer[payslipId].deductions = deductions;

          // Update the input fields in the table
          row.querySelector('.additions-input').value = additions.toFixed(2);
          row.querySelector('.deductions-input').value = deductions.toFixed(2);

          // Recalculate and update the row display
          updatePayslipRow(payslipId);
        });

        // Send bulk update to backend (optional, could do one request per payslip or a single bulk request)
        // For simplicity, we'll just update the UI and send individual updates on blur or authorize

        updateTotalsDisplay(); // Update overall totals
        closeBulkModal();
        Swal.fire('Success!', "Bulk updates applied successfully! Remember to authorize payroll to save changes.", 'success');
      }

      // Salary Edit Modal logic (for single employee additions/deductions)
      window.openSalaryModal = function (payslipId) {
        const row = document.querySelector(`#payslipRow_${payslipId}`);
        if (!row) return;

        document.getElementById("editPayslipId").value = payslipId;

        // Get current data from buffer or original data
        const payslip = payslipDataBuffer[payslipId] || payrollData.payslips.find(p => p.id == payslipId);
         if (!payslip) {
             console.error("Payslip data not found for modal:", payslipId);
             return;
         }

        const grossSalary = parseFloat(payslip.gross_salary || 0);
        const additions = parseFloat(payslip.additions || 0);
        const deductions = parseFloat(payslip.deductions || 0);
        const paye = calculatePAYE(grossSalary + additions - deductions);
        const netSalaryBeforePension = grossSalary - paye;
        const pension = netSalaryBeforePension * 0.05;
        const welfareFund = parseFloat(payslip.welfare_fund || 0);
        const baseNet = netSalaryBeforePension - pension - welfareFund;
        const totalOTPay = parseFloat(payslip.total_overtime_pay || 0);
        const currentNet = baseNet + totalOTPay + additions - deductions;


        document.getElementById("baseNetSalaryDisplay").value = baseNet.toFixed(2); // Display calculated base net
        document.getElementById("additionsInput").value = additions.toFixed(2);
        document.getElementById("deductionsInput").value = deductions.toFixed(2);
        document.getElementById("currentNetSalary").value = currentNet.toFixed(2); // Display calculated current net


        document.getElementById("salaryEditModal").classList.remove("hidden");
      }

      window.closeSalaryModal = function () {
        document.getElementById("salaryEditModal").classList.add("hidden");
      }

      window.applySalaryChanges = function applySalaryChanges() {
        const payslipId = document.getElementById('editPayslipId').value;
        const additions = parseFloat(document.getElementById('additionsInput').value) || 0;
        const deductions = parseFloat(document.getElementById('deductionsInput').value) || 0;

        // Locate the row using data-payslip-id
        const row = document.querySelector(`#employeeTableBody tr[data-payslip-id="${payslipId}"]`);
        if (!row) return;

        // Update the buffer
         if (!payslipDataBuffer[payslipId]) {
             const currentPayslip = payrollData.payslips.find(p => p.id == payslipId);
             if (currentPayslip) {
                payslipDataBuffer[payslipId] = { ...currentPayslip };
             } else {
                 console.error("Payslip not found in loaded data:", payslipId);
                 return;
             }
          }
        payslipDataBuffer[payslipId].additions = additions;
        payslipDataBuffer[payslipId].deductions = deductions;

        // Update the input fields in the table
        row.querySelector('.additions-input').value = additions.toFixed(2);
        row.querySelector('.deductions-input').value = deductions.toFixed(2);

        // Recalculate and update the row display
        updatePayslipRow(payslipId);

        // Send update to backend immediately
        sendPayslipUpdate(payslipId);

        // Close modal and recalculate totals (totals update happens after backend save)
        closeSalaryModal();
        Swal.fire('Success!', "Employee data updated successfully!", 'success'); // This might fire before backend saves
      }

      function showLoading() {
        const overlay = document.getElementById('loaderModal');
        if(overlay) overlay.style.display = 'flex';
      }

      function hideLoading() {
        const overlay = document.getElementById('loaderModal');
         if(overlay) overlay.style.display = 'none';
      }

    </script>
  </body>
</html>
