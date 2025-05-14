<?php include 'top.php' ?>
      <!-- Main Dashboard Area -->
      <main class="p-6">
        <!-- Page Title -->
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-blue-600 flex items-center">
            Payroll Generation
          </h1>
          <!--
          <button onclick="loadPayrollHistory()"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm">
            <i class="ti-agenda mr-1"></i> View Payroll History
          </button>
          -->
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
            <input type="month" id="payrollMonth" title="Select payroll month"
              class="border p-2 rounded w-full" value="<?= date('Y-m') ?>">
          </div>
        </div>

        <!-- Actions -->
        <div id="totalsSection" class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4 text-sm text-gray-800 font-semibold">
          <div class="bg-blue-50 p-3 rounded shadow">
            Gross Pay: <span id="totalGrossDisplay">MWK 0.00</span>
          </div>
          <div class="bg-green-50 p-3 rounded shadow">
            Net Pay: <span id="totalNetDisplay">MWK 0.00</span>
          </div>
          <div class="bg-yellow-50 p-3 rounded shadow">
            PAYE: <span id="totalPayeDisplay">MWK 0.00</span>
          </div>
          <div class="bg-indigo-50 p-3 rounded shadow">
            Pension (5%): <span id="totalPensionDisplay">MWK 0.00</span>
          </div>
          <div class="bg-purple-50 p-3 rounded shadow">
            Additions: <span id="totalAdditionsDisplay">MWK 0.00</span>
          </div>
          <div class="bg-red-50 p-3 rounded shadow">
            Deductions: <span id="totalDeductionsDisplay">MWK 0.00</span>
          </div>
        </div>


        
        <div class="mb-4 text-right">
          <button id="bulkEditBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Bulk Edit Additions/Deductions
          </button>

          <button id="generatePayrollBtn"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            <i class="ti-settings mr-1"></i> Generate Payroll
          </button>
        </div>

        <!-- Employee Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
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
                <th class="px-4 py-3 text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="employeeTableBody" class="divide-y divide-gray-200">
              <!-- Dynamically filled -->
            </tbody>
          </table>
        </div>

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
        <script type="text/javascript">
          document.addEventListener("DOMContentLoaded", () => {
            const branchSelect = document.getElementById("branchFilter");
            const deptSelect = document.getElementById("departmentFilter");
            const genderSelect = document.getElementById("genderFilter");
            const minSalary = document.getElementById("minSalary");
            const maxSalary = document.getElementById("maxSalary");
            const payrollMonth = document.getElementById("payrollMonth");
            const tableBody = document.getElementById("employeeTableBody");
            const selectAll = document.getElementById("selectAllEmployees");

            const employeeDataBuffer = {}; // Buffer to store employee data

            function updateTotals() {
              let totalGross = 0;
              let totalNet = 0;
              let totalPaye = 0;
              let totalAdditions = 0;
              let totalDeductions = 0;
              let totalPension = 0;

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
                  const baseNet = parseFloat(row.dataset.baseNet || 0); // Use base net for pension
                  const pension = (baseNet * 0.05);

                  totalGross += salary;
                  totalNet += net;
                  totalPaye += paye;
                  totalAdditions += additions;
                  totalDeductions += deductions;
                  totalPension += pension;
                }
              });

              document.getElementById("totalGrossDisplay").textContent = `MWK ${totalGross.toFixed(2)}`;
              document.getElementById("totalNetDisplay").textContent = `MWK ${totalNet.toFixed(2)}`;
              document.getElementById("totalPayeDisplay").textContent = `MWK ${totalPaye.toFixed(2)}`;
              document.getElementById("totalAdditionsDisplay").textContent = `MWK ${totalAdditions.toFixed(2)}`;
              document.getElementById("totalDeductionsDisplay").textContent = `MWK ${totalDeductions.toFixed(2)}`;
              document.getElementById("totalPensionDisplay").textContent = `MWK ${totalPension.toFixed(2)}`;
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
              if (minSalary.value) filters.min_salary = minSalary.value;
              if (maxSalary.value) filters.max_salary = maxSalary.value;
              if (payrollMonth.value) filters.payroll_month = payrollMonth.value;

              fetch('api/filter_employees.php', {
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
                  console.error(data.message);
                }
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

            function populateEmployees(employees) {
              tableBody.innerHTML = "";

              if (employees.length === 0) {
                tableBody.innerHTML = `
                  <tr>
                    <td colspan="11" class="px-4 py-3 text-center text-gray-500">No employees found.</td>
                  </tr>`;
                return;
              }

              employees.forEach(emp => {
                const paye = calculatePAYE(emp.salary);
                const baseNet = emp.salary - paye;
                const pension = baseNet * 0.05;
                let additions = 0;
                let deductions = 0;

                // Check if previous values exist in the buffer
                if (employeeDataBuffer[emp.id]) {
                  additions = employeeDataBuffer[emp.id].additions;
                  deductions = employeeDataBuffer[emp.id].deductions;
                } else {
                  // Initialize buffer if not present
                  employeeDataBuffer[emp.id] = {
                    baseSalary: emp.salary,
                    paye: paye,
                    baseNet: baseNet,
                    additions: additions,
                    deductions: deductions,
                    pension: pension
                  };
                }

                const currentNet = baseNet + additions - deductions;

                const row = document.createElement("tr");
                row.id = `employeeRow_${emp.id}`;
                row.setAttribute("data-id", emp.id);
                row.setAttribute("data-salary", emp.salary);
                row.setAttribute("data-paye", paye);
                row.setAttribute("data-base-net", baseNet); // Store base net for pension calculation
                row.setAttribute("data-net", currentNet);
                row.setAttribute("data-additions", additions);
                row.setAttribute("data-deductions", deductions);

                row.innerHTML = `
                  <td class="px-4 py-3"><input type="checkbox" class="employeeCheckbox" value="${emp.id}"></td>
                  <td class="px-4 py-3">${emp.name}</td>
                  <td class="px-4 py-3">${emp.department}</td>
                  <td class="px-4 py-3">${emp.gender}</td>
                  <td class="px-4 py-3">MWK ${emp.salary}</td>
                  <td class="px-4 py-3">MWK ${paye}</td>
                  <td class="px-4 py-3 net-cell" data-id="${emp.id}" data-salary="${currentNet}">MWK ${currentNet}</td>
                  <td class="px-4 py-3 pension-cell">MWK ${pension}</td>
                  <td class="px-4 py-3 additions-cell">MWK ${additions}</td>
                  <td class="px-4 py-3 deductions-cell">MWK ${deductions}</td>
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
            // Open bulk modal
            document.getElementById("bulkEditBtn").addEventListener("click", () => {
              const selected = [...document.querySelectorAll('.employeeCheckbox:checked')];
              if (selected.length === 0) {
                notify.error("Please select at least one employee.");
                return;
              }
              document.getElementById("bulkEditModal").classList.remove("hidden");
            });

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
                row.querySelector('.pension-cell').textContent = `MWK ${pension.toFixed(2)}`;

                employeeDataBuffer[id].additions = additions;
                employeeDataBuffer[id].deductions = deductions;
              });

              updateTotals();
              closeBulkModal();
              notify.success("Bulk updates applied successfully!");
            }

          });

          // Salary Edit Modal logic
          window.openSalaryModal = function(employeeId) {
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

          window.closeSalaryModal = function() {
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
            row.querySelector('.pension-cell').textContent = `MWK ${pension.toFixed(2)}`;

            // Update the buffer
            employeeDataBuffer[id].additions = additions;
            employeeDataBuffer[id].deductions = deductions;

            // Close modal and recalculate totals
            closeSalaryModal();
            updateTotals();
          }


          function loadPayrollHistory() {
            window.location.href = "payroll_history.php";
          }

          function closePayrollModal() {
            document.getElementById("payrollModal").classList.add("hidden");
          }


        </script>

        <script type="text/javascript">
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
          document.getElementById("generatePayrollBtn").addEventListener("click", function() {
              const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));
              if (selectedEmployees.length === 0) {
                  notify.error("Please select at least one employee to generate payroll.");
                  return;
              }
              
              // Generate PDF
              generatePayrollPDF();
              
              // Generate Excel
              exportToExcel();
          });

          function generatePayrollPDF() {
            showLoading();
            const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));
            if (selectedEmployees.length === 0) {
                notyf.error("Please select at least one employee to generate payroll.");
                return;
            }

            const employeeData = selectedEmployees.map(checkbox => {
                const row = checkbox.closest('tr');
                return {
                    name: row.querySelector('td:nth-child(2)').textContent,
                    department: row.querySelector('td:nth-child(3)').textContent,
                    salary: parseFloat(row.dataset.salary),
                    paye: parseFloat(row.dataset.paye),
                    net: parseFloat(row.dataset.net),
                    pension: parseFloat(row.dataset.pension),
                    additions: parseFloat(row.dataset.additions),
                    deductions: parseFloat(row.dataset.deductions)
                };
            });

            const totals = {
                gross: document.getElementById("totalGrossDisplay").textContent.replace('MWK ', ''),
                net: document.getElementById("totalNetDisplay").textContent.replace('MWK ', ''),
                paye: document.getElementById("totalPayeDisplay").textContent.replace('MWK ', ''),
                pension: document.getElementById("totalPensionDisplay").textContent.replace('MWK ', ''),
                additions: document.getElementById("totalAdditionsDisplay").textContent.replace('MWK ', ''),
                deductions: document.getElementById("totalDeductionsDisplay").textContent.replace('MWK ', '')
            };

            const pdfContent = document.createElement('div');
            pdfContent.innerHTML = `
                <style>
                    @page {
                        size: letter landscape;
                        margin: 1in;
                    }
                    .pdf-container {
                        font-family: 'Segoe UI', sans-serif;
                        padding: 20px;
                    }
                    .totals-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 10px;
                        margin-bottom: 20px;
                    }
                    .totals-box {
                        padding: 15px;
                        border-radius: 5px;
                        border: 1px solid #ddd;
                    }
                    .totals-box h3 {
                        margin: 0 0 10px 0;
                        color: #333;
                    }
                    .totals-box p {
                        margin: 0;
                        font-size: 1.2em;
                        font-weight: bold;
                    }
                    .pdf-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    .pdf-table th,
                    .pdf-table td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    .pdf-table th {
                        background-color: #f8f9fa;
                        font-weight: bold;
                    }
                    .pdf-table tr:nth-child(even) {
                        background-color: #f8f9fa;
                    }
                </style>
                <div class="pdf-container">
                    <h1 class="text-2xl font-bold text-blue-600 mb-4">Payroll Report</h1>
                    <div class="totals-grid">
                        <div class="totals-box bg-blue-50">
                            <h3>Gross Pay</h3>
                            <p>MWK ${totals.gross}</p>
                        </div>
                        <div class="totals-box bg-green-50">
                            <h3>Net Pay</h3>
                            <p>MWK ${totals.net}</p>
                        </div>
                        <div class="totals-box bg-yellow-50">
                            <h3>PAYE</h3>
                            <p>MWK ${totals.paye}</p>
                        </div>
                        <div class="totals-box bg-indigo-50">
                            <h3>Pension</h3>
                            <p>MWK ${totals.pension}</p>
                        </div>
                        <div class="totals-box bg-purple-50">
                            <h3>Additions</h3>
                            <p>MWK ${totals.additions}</p>
                        </div>
                        <div class="totals-box bg-red-50">
                            <h3>Deductions</h3>
                            <p>MWK ${totals.deductions}</p>
                        </div>
                    </div>
                    <table class="pdf-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Salary</th>
                                <th>PAYE</th>
                                <th>Net</th>
                                <th>Pension</th>
                                <th>Additions</th>
                                <th>Deductions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${employeeData.map(emp => `
                                <tr>
                                    <td>${emp.name}</td>
                                    <td>${emp.department}</td>
                                    <td>MWK ${emp.salary.toFixed(2)}</td>
                                    <td>MWK ${emp.paye.toFixed(2)}</td>
                                    <td>MWK ${emp.net.toFixed(2)}</td>
                                    <td>MWK ${emp.pension.toFixed(2)}</td>
                                    <td>MWK ${emp.additions.toFixed(2)}</td>
                                    <td>MWK ${emp.deductions.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;

            html2pdf()
                .set({
                    margin: 1,
                    filename: 'payroll_report_' + new Date().toISOString().split('T')[0] + '.pdf',
                    jsPDF: { 
                        unit: 'in', 
                        format: 'letter', 
                        orientation: 'landscape' 
                    },
                    html2canvas: {
                        scale: 2,
                        letterRendering: true,
                        allowTaint: true
                    }
                })
                .from(pdfContent)
                .save()
                .finally(() => {
                    hideLoading();
                    notyf.success("Payroll report generated successfully!");
                });
        }

          function exportToExcel() {
              const tableData = [];
              const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));
              
              // Add header row
              tableData.push([
                  'Name', 'Department', 'Salary', 'PAYE', 'Net Salary', 
                  'Pension', 'Additions', 'Deductions'
              ]);
              
              // Add employee data
              selectedEmployees.forEach(checkbox => {
                  const row = checkbox.closest('tr');
                  tableData.push([
                      row.querySelector('td:nth-child(2)').textContent,
                      row.querySelector('td:nth-child(3)').textContent,
                      row.querySelector('td:nth-child(5)').textContent,
                      row.querySelector('td:nth-child(6)').textContent,
                      row.querySelector('td:nth-child(7)').textContent,
                      row.querySelector('td:nth-child(8)').textContent,
                      row.querySelector('td:nth-child(9)').textContent,
                      row.querySelector('td:nth-child(10)').textContent
                  ]);
              });
              
              // Add totals row
              tableData.push([
                  'Totals',
                  '',
                  document.getElementById("totalGrossDisplay").textContent,
                  document.getElementById("totalPayeDisplay").textContent,
                  document.getElementById("totalNetDisplay").textContent,
                  document.getElementById("totalPensionDisplay").textContent,
                  document.getElementById("totalAdditionsDisplay").textContent,
                  document.getElementById("totalDeductionsDisplay").textContent
              ]);
              
              const csvContent = tableData.map(row => row.join(',')).join('\n');
              const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
              const url = URL.createObjectURL(blob);
              
              const link = document.createElement('a');
              link.href = url;
              link.download = 'payroll_report_' + new Date().toISOString().split('T')[0] + '.csv';
              
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
          }
        </script>

        <!-- Payroll Modal -->
        <div id="payrollModal"
          class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
          <div
            class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg relative">
            <button onclick="closePayrollModal()"
              class="absolute top-2 right-2 text-gray-400 hover:text-red-600">
              <i class="ti-close text-xl"></i>
            </button>
            <h2 class="text-xl font-semibold mb-4 text-blue-600"><i
                class="ti-clipboard mr-2"></i> Payroll Details</h2>
            <div id="payrollDetails" class="text-sm text-gray-800">
              <!-- Payroll details injected here -->
            </div>
          </div>
        </div>

      </main>
    </div>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notifyjs-browser/dist/notify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="your-custom-script.js"></script>
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
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
    </script>
    <!-- Sidebar Toggle Script -->
    <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("-translate-x-full");
    }
  </script>
  </body>
</html>