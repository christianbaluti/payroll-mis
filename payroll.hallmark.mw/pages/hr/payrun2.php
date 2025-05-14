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
            Payroll Drafting
          </h1>
        </div>
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
            <div class="bg-green-100 rounded-lg p-2 mr-3 flex items-center justify-center">
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
              <p id="totalWeekendOvertime" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-yellow-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-calendar text-2xl text-yellow-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Weekday OT</h3>
              <p id="totalWeekdayOvertime" class="text-xs text-gray-500">MWK 0.00</p>
            </div>
          </div>

          <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-gray-100 rounded-lg p-2 mr-3 flex items-center justify-center">
              <i class="ti-time text-2xl text-gray-500"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-900 mb-1">Total OT</h3>
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
          <button onclick="openModal('confirmPayrollModal')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Generate Payroll
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
            <input type="hidden" id="editEmployeeId">

            <div class="mb-4">
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
      let employeeDataBuffer = {}; // Buffer to store employee data including editable hours

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

        // Initial load of branches and employees
        loadBranches();
        fetchEmployees();

        // Add event listeners to filter elements
        const branchSelect = document.getElementById("branchFilter");
        const deptSelect = document.getElementById("departmentFilter");
        const genderSelect = document.getElementById("genderFilter");
        const minSalary = document.getElementById("minSalary");
        const maxSalary = document.getElementById("maxSalary");
        const selectAll = document.getElementById("selectAllEmployees");
        const tableBody = document.getElementById("employeeTableBody");


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
             updateTotals(); // Update totals after selecting all
        });

        // Add event delegation for overtime hour inputs
        tableBody.addEventListener('input', (event) => {
            if (event.target.classList.contains('weekend-hours') || event.target.classList.contains('weekday-hours')) {
                const inputElement = event.target;
                const row = inputElement.closest('tr');
                const employeeId = row.dataset.id;
                const weekendHours = parseFloat(row.querySelector('.weekend-hours').value) || 0;
                const weekdayHours = parseFloat(row.querySelector('.weekday-hours').value) || 0;
                updateEmployeeOvertime(employeeId, weekendHours, weekdayHours);
            }
        });


        // Open bulk modal for additions/deductions
        document.getElementById("bulkEditBtn").addEventListener("click", () => {
            const selected = [...document.querySelectorAll('.employeeCheckbox:checked')];
            if (selected.length === 0) {
                notyf.error("Please select at least one employee.");
                return;
            }
            document.getElementById("bulkEditModal").classList.remove("hidden");
        });

         // Monitor individual selections for total updates
        tableBody.addEventListener("change", (e) => {
            if (e.target.type === "checkbox") {
                updateTotals();
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
              notyf.error('Please select at least one employee to apply bulk hours.');
              return;
          }

          const weekendHours = parseFloat(document.getElementById('bulkWeekendHours').value) || 0;
          const weekdayHours = parseFloat(document.getElementById('bulkWeekdayHours').value) || 0;

          selectedEmployees.forEach(checkbox => {
              const row = checkbox.closest('tr');
              const employeeId = row.dataset.id;
              updateEmployeeOvertime(employeeId, weekendHours, weekdayHours);
          });

          closeModal('bulkHoursModal');
          notyf.success('Bulk overtime hours applied.');
      }

      // Generate payroll
      async function generatePayroll() {
          const selectedEmployees = document.querySelectorAll('.employeeCheckbox:checked');
          if (selectedEmployees.length === 0) {
              notyf.error('Please select at least one employee to generate payroll.');
              closeModal('confirmPayrollModal');
              return;
          }

          showLoading(); // Show loading overlay

          const payrollData = {
              month: new Date().getMonth() + 1,
              year: new Date().getFullYear(),
              unique_id: Date.now().toString(),
              status: 'drafted',
              employees: []
          };

          selectedEmployees.forEach(checkbox => {
              const row = checkbox.closest('tr');
              const employeeId = row.dataset.id;
              const employeeBufferData = employeeDataBuffer[employeeId] || {};

              payrollData.employees.push({
                  id: employeeId, // Include employee ID
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
                  weekend_overtime_hours: parseFloat(row.querySelector('.weekend-hours').value) || 0,
                  weekday_overtime_hours: parseFloat(row.querySelector('.weekday-hours').value) || 0,
                  weekend_overtime_pay: parseFloat(row.querySelector('.weekend-ot-pay').textContent) || 0,
                  weekday_overtime_pay: parseFloat(row.querySelector('.weekday-ot-pay').textContent) || 0,
                  bank_name: row.dataset.bankName || '',
                  bank_account_name: row.dataset.bankAccountName || '',
                  bank_account_number: row.dataset.bankAccountNumber || '',
                  bank_branch: row.dataset.bankBranch || '',
                  email: row.dataset.email || ''
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
              hideLoading(); // Hide loading overlay

              if (result.success) {
                  notyf.success('Payroll generated successfully');
                  closeModal('confirmPayrollModal');
                   // Optionally, clear the selection or refresh the employee list
                   document.getElementById('selectAllEmployees').checked = false;
                   document.querySelectorAll('.employeeCheckbox:checked').forEach(cb => cb.checked = false);
                   updateTotals();
              } else {
                  notyf.error(result.message || 'Failed to generate payroll');
              }
          } catch (error) {
              console.error('Error generating payroll:', error);
              hideLoading(); // Hide loading overlay
              notyf.error('Failed to generate payroll');
          }
      }

      // Rest of your existing JavaScript code...

      //updating totals
      function updateTotals() {
        let totalGross = 0;
        let totalNet = 0;
        let totalPaye = 0;
        let totalAdditions = 0;
        let totalDeductions = 0;
        let totalPension = 0;
        let totalWelfareFund = 0;
        let totalWeekendOvertimePay = 0;
        let totalWeekdayOvertimePay = 0;
        let totalOvertimePay = 0;

        const rows = document.querySelectorAll("#employeeTableBody tr");
        rows.forEach(row => {
          const checkbox = row.querySelector("input[type='checkbox']");
          // Only include selected employees in totals
          if (checkbox && checkbox.checked) {
            const id = row.dataset.id;
            const salary = parseFloat(row.dataset.salary || 0);
            const paye = parseFloat(row.dataset.paye || 0);
            const additions = parseFloat(row.dataset.additions || 0);
            const deductions = parseFloat(row.dataset.deductions || 0);
            const pension = parseFloat(row.dataset.pension || 0);
            const welfareFund = parseFloat(row.dataset.welfareFund || 0);
            const weekendOTPay = parseFloat(row.querySelector('.weekend-ot-pay').textContent || 0);
            const weekdayOTPay = parseFloat(row.querySelector('.weekday-ot-pay').textContent || 0);
            const currentNet = parseFloat(row.dataset.net || 0);


            totalGross += salary;
            totalNet += currentNet;
            totalPaye += paye;
            totalAdditions += additions;
            totalDeductions += deductions;
            totalPension += pension;
            totalWelfareFund += welfareFund;
            totalWeekendOvertimePay += weekendOTPay;
            totalWeekdayOvertimePay += weekdayOTPay;
            totalOvertimePay += (weekendOTPay + weekdayOTPay);
          }
        });

        document.getElementById("totalGrossDisplay").textContent = `MWK ${totalGross.toFixed(2)}`;
        document.getElementById("totalNetDisplay").textContent = `MWK ${totalNet.toFixed(2)}`;
        document.getElementById("totalPayeDisplay").textContent = `MWK ${totalPaye.toFixed(2)}`;
        document.getElementById("totalAdditionsDisplay").textContent = `MWK ${totalAdditions.toFixed(2)}`;
        document.getElementById("totalDeductionsDisplay").textContent = `MWK ${totalDeductions.toFixed(2)}`;
        document.getElementById("totalPensionDisplay").textContent = `MWK ${totalPension.toFixed(2)}`;
        document.getElementById("totalWelfareFund").textContent = `MWK ${totalWelfareFund.toFixed(2)}`;
        document.getElementById("totalWeekendOvertime").textContent = `MWK ${totalWeekendOvertimePay.toFixed(2)}`;
        document.getElementById("totalWeekdayOvertime").textContent = `MWK ${totalWeekdayOvertimePay.toFixed(2)}`;
        document.getElementById("totalOvertime").textContent = `MWK ${totalOvertimePay.toFixed(2)}`;
      }


      // ========== Helper Functions ==========
      function loadBranches() {
        fetch('api/get_branches.php')
          .then(res => res.json())
          .then(data => {
            if (!data.success) {
              console.error("Error loading branches:", data.message);
              return;
            }

            const branchSelect = document.getElementById("branchFilter");
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

            const deptSelect = document.getElementById("departmentFilter");
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
        const branchSelect = document.getElementById("branchFilter");
        const deptSelect = document.getElementById("departmentFilter");
        const genderSelect = document.getElementById("genderFilter");
        const minSalary = document.getElementById("minSalary");
        const maxSalary = document.getElementById("maxSalary");
        const tableBody = document.getElementById("employeeTableBody");

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
              // calculateTotalPayroll(data.employees); // This function seems unused for the totals displayed.
            } else {
              console.error('Error fetching employees:', data.message);
               tableBody.innerHTML = `
                <tr>
                  <td colspan="23" class="px-4 py-3 text-center text-gray-500">Error loading employees.</td>
                </tr>`;
            }
          })
          .catch(err => {
            console.error('Fetch failed:', err);
             tableBody.innerHTML = `
              <tr>
                <td colspan="23" class="px-4 py-3 text-center text-gray-500">Error fetching data.</td>
              </tr>`;
          });
      }

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


      function populateEmployees(employees) {
        const tableBody = document.getElementById("employeeTableBody");
        tableBody.innerHTML = "";
        employeeDataBuffer = {}; // Clear buffer

        if (employees.length === 0) {
          tableBody.innerHTML = `
          <tr>
            <td colspan="23" class="px-4 py-3 text-center text-gray-500">No employees found.</td>
          </tr>`;
          updateTotals(); // Update totals to 0
          return;
        }

        employees.forEach(emp => {
          const salary = parseFloat(emp.salary || 0);
          const hourlyRate = parseFloat(emp.hourly_rate || 0);
          const welfareFund = 2000; // Assuming fixed welfare fund

          // Initialize employee data in buffer
          employeeDataBuffer[emp.id] = {
              baseSalary: salary,
              additions: parseFloat(emp.additions || 0), // Load existing additions
              deductions: parseFloat(emp.deductions || 0), // Load existing deductions
              hourlyRate: hourlyRate,
              weekendHours: parseFloat(emp.weekend_overtime_hours || 0), // Load existing hours
              weekdayHours: parseFloat(emp.weekday_overtime_hours || 0), // Load existing hours
              welfareFund: welfareFund,
              bankName: emp.bank_name || '',
              bankAccountName: emp.bank_account_name || '',
              bankAccountNumber: emp.bank_account_number || '',
              bankBranch: emp.bank_branch || '',
              email: emp.email || ''
          };

          // Calculate initial payroll components
          let currentAdditions = employeeDataBuffer[emp.id].additions;
          let currentDeductions = employeeDataBuffer[emp.id].deductions;
          let taxableIncome = salary + currentAdditions - currentDeductions;
          let paye = calculatePAYE(taxableIncome);
          let netSalaryBeforePension = salary - paye;
          let pension = netSalaryBeforePension * 0.05;
          let baseNet = netSalaryBeforePension - pension - welfareFund;

          let weekendHours = employeeDataBuffer[emp.id].weekendHours;
          let weekdayHours = employeeDataBuffer[emp.id].weekdayHours;
          let weekendOTPay = weekendHours * hourlyRate * 2;
          let weekdayOTPay = weekdayHours * hourlyRate * 1.5;
          let totalOTPay = weekendOTPay + weekdayOTPay;
          let currentNet = baseNet + totalOTPay + currentAdditions - currentDeductions;


          const row = document.createElement("tr");
          row.id = `employeeRow_${emp.id}`;
          row.setAttribute("data-id", emp.id);
          row.setAttribute("data-salary", salary);
          row.setAttribute("data-paye", paye.toFixed(2));
          row.setAttribute("data-base-net", baseNet.toFixed(2));
          row.setAttribute("data-net", currentNet.toFixed(2));
          row.setAttribute("data-pension", pension.toFixed(2));
          row.setAttribute("data-additions", currentAdditions.toFixed(2));
          row.setAttribute("data-deductions", currentDeductions.toFixed(2));
          row.setAttribute("data-welfare-fund", welfareFund.toFixed(2));
          row.setAttribute("data-hourly-rate", hourlyRate.toFixed(2));
          row.setAttribute("data-hours-per-week", emp.hours_per_week || '');
          row.setAttribute("data-bank-name", emp.bank_name || '');
          row.setAttribute("data-bank-branch", emp.bank_branch || '');
          row.setAttribute("data-bank-account-name", emp.bank_account_name || '');
          row.setAttribute("data-bank-account-number", emp.bank_account_number || '');
          row.setAttribute("data-email", emp.email || '');

          row.innerHTML = `
          <td class="px-4 py-3"><input type="checkbox" class="employeeCheckbox" value="${emp.id}"></td>
          <td class="px-4 py-3">${emp.name}</td>
          <td class="px-4 py-3">${emp.department}</td>
          <td class="px-4 py-3">${emp.gender}</td>
          <td class="px-4 py-3">MWK ${salary.toFixed(2)}</td>
          <td class="px-4 py-3 paye-cell">MWK ${paye.toFixed(2)}</td>
          <td class="px-4 py-3 net-cell">MWK ${currentNet.toFixed(2)}</td>
          <td class="px-4 py-3 pension-cell">MWK ${pension.toFixed(2)}</td>
          <td class="px-4 py-3 additions-cell">MWK ${currentAdditions.toFixed(2)}</td>
          <td class="px-4 py-3 deductions-cell">MWK ${currentDeductions.toFixed(2)}</td>
          <td class="px-4 py-3 welfare-cell">MWK ${welfareFund.toFixed(2)}</td>
          <td class="px-4 py-3">${hourlyRate.toFixed(2)}</td>
          <td class="px-4 py-3">
              <input type="number" class="weekend-hours w-20 border p-1 rounded"
                     value="${weekendHours}" min="0" step="0.5">
          </td>
          <td class="px-4 py-3">
              <input type="number" class="weekday-hours w-20 border p-1 rounded"
                     value="${weekdayHours}" min="0" step="0.5">
          </td>
          <td class="px-4 py-3 weekend-ot-pay">MWK ${weekendOTPay.toFixed(2)}</td>
          <td class="px-4 py-3 weekday-ot-pay">MWK ${weekdayOTPay.toFixed(2)}</td>
          <td class="px-4 py-3 total-ot-pay">MWK ${totalOTPay.toFixed(2)}</td>
          <td class="px-4 py-3">${emp.hours_per_week || ''}</td>
          <td class="px-4 py-3 bank-name-cell">${emp.bank_name || ''}</td>
          <td class="px-4 py-3 bank-account-cell">${emp.bank_account_name || ''}</td>
          <td class="px-4 py-3 bank-account-number-cell">${emp.bank_account_number || ''}</td>
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

        updateTotals(); // Initial total calculation
      }

      function updateEmployeeOvertime(employeeId, weekendHours, weekdayHours) {
          const row = document.querySelector(`#employeeRow_${employeeId}`);
          if (!row) return;

          const hourlyRate = parseFloat(row.dataset.hourlyRate || 0);

          // Calculate overtime pay based on the manually entered hours
          const weekendOTPay = weekendHours * hourlyRate * 2;
          const weekdayOTPay = weekdayHours * hourlyRate * 1.5;
          const totalOTPay = weekendOTPay + weekdayOTPay;

          // Update the input field values and the calculated cells
          row.querySelector('.weekend-hours').value = weekendHours.toFixed(2);
          row.querySelector('.weekday-hours').value = weekdayHours.toFixed(2);
          row.querySelector('.weekend-ot-pay').textContent = `MWK ${weekendOTPay.toFixed(2)}`;
          row.querySelector('.weekday-ot-pay').textContent = `MWK ${weekdayOTPay.toFixed(2)}`;
          row.querySelector('.total-ot-pay').textContent = `MWK ${totalOTPay.toFixed(2)}`;

          // Update data attributes for use in generating payroll
          row.dataset.weekendOvertime = weekendOTPay.toFixed(2);
          row.dataset.weekdayOvertime = weekdayOTPay.toFixed(2);
          row.dataset.totalOvertime = totalOTPay.toFixed(2);

          // Recalculate and update Net Salary
          const baseNet = parseFloat(row.dataset.baseNet || 0);
          const additions = parseFloat(row.dataset.additions || 0);
          const deductions = parseFloat(row.dataset.deductions || 0);
          const currentNet = baseNet + totalOTPay + additions - deductions;
          row.dataset.net = currentNet.toFixed(2);
          row.querySelector('.net-cell').textContent = `MWK ${currentNet.toFixed(2)}`;


          // Update the buffer with the new hours
          if (employeeDataBuffer[employeeId]) {
              employeeDataBuffer[employeeId].weekendHours = weekendHours;
              employeeDataBuffer[employeeId].weekdayHours = weekdayHours;
          }


          updateTotals(); // Update overall totals
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
          notyf.error("No employees selected.");
          return;
        }

        selectedCheckboxes.forEach(cb => {
          const id = cb.value;
          const row = document.querySelector(`#employeeTableBody tr[data-id="${id}"]`);
          if (!row) return;

          // Update data attributes for additions and deductions
          row.dataset.additions = additions.toFixed(2);
          row.dataset.deductions = deductions.toFixed(2);

          // Recalculate and update displayed values
          const salary = parseFloat(row.dataset.salary || 0);
          const taxableIncome = salary + additions - deductions;
          const paye = calculatePAYE(taxableIncome);
          const netSalaryBeforePension = salary - paye;
          const pension = netSalaryBeforePension * 0.05; // Recalculate pension based on new net before pension
          const welfareFund = parseFloat(row.dataset.welfareFund || 0); // Assuming fixed welfare
          const totalOTPay = parseFloat(row.dataset.totalOvertime || 0); // Use calculated overtime pay

          const baseNet = netSalaryBeforePension - pension - welfareFund;
          const currentNet = baseNet + totalOTPay + additions - deductions;


          row.dataset.paye = paye.toFixed(2); // Update PAYE data attribute
          row.dataset.baseNet = baseNet.toFixed(2); // Update base net data attribute
          row.dataset.net = currentNet.toFixed(2); // Update net salary data attribute
          row.dataset.pension = pension.toFixed(2); // Update pension data attribute


          row.querySelector('.paye-cell').textContent = `MWK ${paye.toFixed(2)}`;
          row.querySelector('.additions-cell').textContent = `MWK ${additions.toFixed(2)}`;
          row.querySelector('.deductions-cell').textContent = `MWK ${deductions.toFixed(2)}`;
          row.querySelector('.pension-cell').textContent = `MWK ${pension.toFixed(2)}`;
          row.querySelector('.net-cell').textContent = `MWK ${currentNet.toFixed(2)}`;


          // Update the buffer
          if (employeeDataBuffer[id]) {
              employeeDataBuffer[id].additions = additions;
              employeeDataBuffer[id].deductions = deductions;
               // Note: Other calculated values in buffer will be updated when updateTotals is called or on next fetch
          }
        });

        updateTotals();
        closeBulkModal();
        notyf.success("Bulk updates applied successfully!");
      }

      // Salary Edit Modal logic
      window.openSalaryModal = function (employeeId) {
        const row = document.querySelector(`#employeeRow_${employeeId}`);
        if (!row) return;

        document.getElementById("editEmployeeId").value = employeeId;

        const baseSalary = parseFloat(row.dataset.salary || 0);
        const currentAdditions = parseFloat(row.dataset.additions || 0);
        const currentDeductions = parseFloat(row.dataset.deductions || 0);
        const paye = calculatePAYE(baseSalary + currentAdditions - currentDeductions);
        const netSalaryBeforePension = baseSalary - paye;
        const pension = netSalaryBeforePension * 0.05;
        const welfareFund = parseFloat(row.dataset.welfareFund || 0);
        const baseNet = netSalaryBeforePension - pension - welfareFund;
        const totalOTPay = parseFloat(row.dataset.totalOvertime || 0);
        const currentNet = baseNet + totalOTPay + currentAdditions - currentDeductions;


        document.getElementById("baseNetSalaryDisplay").value = baseNet.toFixed(2); // Display calculated base net
        document.getElementById("additionsInput").value = currentAdditions.toFixed(2);
        document.getElementById("deductionsInput").value = currentDeductions.toFixed(2);
         document.getElementById("currentNetSalary").value = currentNet.toFixed(2); // Display calculated current net


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

        // Extract base salary and welfare fund
        const baseSalary = parseFloat(row.dataset.salary) || 0;
        const welfareFund = parseFloat(row.dataset.welfareFund || 0);

        // Recalculate payroll components based on new additions/deductions
        const taxableIncome = baseSalary + additions - deductions;
        const paye = calculatePAYE(taxableIncome);
        const netSalaryBeforePension = baseSalary - paye;
        const pension = netSalaryBeforePension * 0.05; // Recalculate pension
        const baseNet = netSalaryBeforePension - pension - welfareFund;
        const totalOTPay = parseFloat(row.dataset.totalOvertime || 0); // Get current overtime pay
        const newNetSalary = baseNet + totalOTPay + additions - deductions;


        // Update data attributes
        row.dataset.additions = additions.toFixed(2);
        row.dataset.deductions = deductions.toFixed(2);
        row.dataset.paye = paye.toFixed(2); // Update PAYE data attribute
        row.dataset.baseNet = baseNet.toFixed(2); // Update base net data attribute
        row.dataset.net = newNetSalary.toFixed(2); // Update net salary data attribute
        row.dataset.pension = pension.toFixed(2); // Update pension data attribute

        // Update visible text in the table
        row.querySelector('.paye-cell').textContent = `MWK ${paye.toFixed(2)}`;
        row.querySelector('.net-cell').textContent = `MWK ${newNetSalary.toFixed(2)}`;
        row.querySelector('.pension-cell').textContent = `MWK ${pension.toFixed(2)}`;
        row.querySelector('.additions-cell').textContent = `MWK ${additions.toFixed(2)}`;
        row.querySelector('.deductions-cell').textContent = `MWK ${deductions.toFixed(2)}`;


        // Update the buffer
         if (employeeDataBuffer[id]) {
             employeeDataBuffer[id].additions = additions;
             employeeDataBuffer[id].deductions = deductions;
              // Note: Other calculated values in buffer will be updated when updateTotals is called or on next fetch
         }

        // Close modal and recalculate totals
        closeSalaryModal();
        updateTotals();
        notyf.success("Employee data updated successfully!");
      }

      function showLoading() {
        const overlay = document.getElementById('loaderModal');
        if(overlay) overlay.style.display = 'flex';
      }

      function hideLoading() {
        const overlay = document.getElementById('loaderModal');
         if(overlay) overlay.style.display = 'none';
      }

      // Initial Notyf and Notify.js initialization (ensure this is only done once)
      if (typeof notyf === 'undefined' || typeof $.notify === 'undefined') {
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
              window.notyf = notyf; // Make notyf globally accessible


             // Initialize Notify.js
             $.notify.defaults({
               className: "success",
               position: "top right",
               autoHide: true,
               clickToHide: true
             });
           });
      }

    </script>
  </body>
</html>