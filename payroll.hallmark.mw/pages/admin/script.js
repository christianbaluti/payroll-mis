      document.addEventListener("DOMContentLoaded", () => {
          fetchEmployees();
        });

            loadBranches();

            function loadBranches() {
              const branchSelect = document.getElementById("branchFilter");

              fetch('api/get_branches.php')
                .then(res => res.json())
                .then(data => {
                  if (!data.success) {
                    console.error("Error:", data.message);
                    return;
                  }

                  branchSelect.innerHTML = `<option value="">Select Branch</option>`; // Reset options

                  data.branches.forEach(b => {
                    const opt = document.createElement("option");
                    opt.value = b.id;
                    opt.text = b.name;
                    branchSelect.appendChild(opt);
                  });
                })
                .catch(err => console.error('Error loading branches:', err));
            }
          });

        document.addEventListener("DOMContentLoaded", () => {
              const branchSelect = document.getElementById("branchFilter");
              const deptSelect = document.getElementById("departmentFilter");

              loadBranches();

              branchSelect.addEventListener("change", () => {
                const branchId = branchSelect.value;
                if (branchId) {
                  loadDepartments(branchId);
                } else {
                  deptSelect.innerHTML = `<option value="">Select Department</option>`;
                }
              });

              function loadBranches() {
                fetch('api/get_branches.php')
                  .then(res => res.json())
                  .then(data => {
                    if (!data.success) return;
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
                    if (!data.success) return;

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
            });

            function fetchEmployees() {
              const filters = {
                branch_id: document.getElementById("branchFilter").value,
                department_id: document.getElementById("departmentFilter").value,
                gender: document.getElementById("genderFilter").value,
                min_salary: document.getElementById("minSalary").value,
                max_salary: document.getElementById("maxSalary").value,
                payroll_month: document.getElementById("payrollMonth").value
              };

              fetch('api/filter_employees.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(filters)
              })
              .then(res => res.json())
              .then(data => {
                if (data.success) {
                  populateEmployees(data.employees);
                } else {
                  console.error(data.message);
                }
              });
            }


        
          function populateEmployees(employees) {
            const tableBody = document.getElementById("employeeTableBody");
            tableBody.innerHTML = "";

            if (employees.length === 0) {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td colspan="8" class="px-4 py-3 text-center text-gray-500">No employees found.</td>
              `;
              tableBody.appendChild(row);
              return;
            }

            employees.forEach(emp => {
              const row = document.createElement("tr");
              row.className = "hover:bg-gray-50";

              row.innerHTML = `
                <td class="px-4 py-3"><input type="checkbox" class="employeeCheckbox" data-id="${emp.id}"></td>
                <td class="px-4 py-3">${emp.name}</td>
                <td class="px-4 py-3">${emp.email ?? '-'}</td>
                <td class="px-4 py-3">${emp.department}</td>
                <td class="px-4 py-3">${emp.branch}</td>
                <td class="px-4 py-3">${emp.gender}</td>
                <td class="px-4 py-3">MWK ${parseFloat(emp.salary).toLocaleString()}</td>
                <td class="px-4 py-3 text-center">
                  <button class="text-blue-600 hover:underline" onclick="editEmployee(${emp.id})">Edit</button>
                  <button class="text-red-600 hover:underline ml-2" onclick="deleteEmployee(${emp.id})">Delete</button>
                </td>
              `;

              tableBody.appendChild(row);
            });
          }

        
          const branchSelect = document.getElementById("branchFilter");
          const deptSelect = document.getElementById("departmentFilter");
          const genderSelect = document.getElementById("genderFilter");
          const minSalary = document.getElementById("minSalary");
          const maxSalary = document.getElementById("maxSalary");
          const tableBody = document.getElementById("employeeTableBody");
          const selectAll = document.getElementById("selectAllEmployees");
          const payrollMonth = document.getElementById("payrollMonth");

          loadDepartments();
          fetchEmployees();

          document.getElementById("payrollModal").addEventListener("click", (e) => {
            if (e.target.id === "payrollModal") closePayrollModal();
          });

          selectAll.addEventListener('change', () => {
            document.querySelectorAll('.empCheckbox').forEach(cb => cb.checked = selectAll.checked);
          });

          [branchSelect, deptSelect, genderSelect, minSalary, maxSalary].forEach(el => {
            el.addEventListener('change', fetchEmployees);
          });

          document.getElementById("generatePayrollBtn").addEventListener("click", () => {
            const selected = [...document.querySelectorAll('.empCheckbox:checked')].map(cb => cb.value);
            if (selected.length === 0) return notify.error("Please select at least one employee.");
            const month = payrollMonth.value;
            if (!month) return notify.error("Please select a payroll month.");

            fetch('api/generate_payroll.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ employees: selected, month: month })
            })
            .then(res => res.json())
            .then(res => {
              if (res.success) {
                notify.success("Payroll generated successfully!");
                fetchEmployees();
              } else {
                notify.error("Failed to generate payroll.");
              }
            });
          });
        });

        function openPayrollModal(empId) {
          const month = document.getElementById("payrollMonth").value;
          fetch(`api/get_payroll_details.php?employee_id=${empId}&month=${month}`)
            .then(res => res.json())
            .then(data => {
              const container = document.getElementById("payrollDetails");
              if (data.success) {
                const p = data.payroll;
                container.innerHTML = `
                  <p><strong>Employee:</strong> ${p.name}</p>
                  <p><strong>Email:</strong> ${p.email}</p>
                  <p><strong>Month:</strong> ${p.pay_month}</p>
                  <p><strong>Basic Salary:</strong> MK ${p.basic_salary}</p>
                  <p><strong>Allowances:</strong> MK ${p.allowances}</p>
                  <p><strong>Bonuses:</strong> MK ${p.bonuses}</p>
                  <p><strong>PAYE:</strong> MK ${p.paye}</p>
                  <p><strong>Pension:</strong> MK ${p.pension}</p>
                  <p class="mt-2 border-t pt-2"><strong>Gross Pay:</strong> MK ${p.gross_pay}</p>
                  <p><strong>Net Pay:</strong> MK ${p.net_pay}</p>
                `;
              } else {
                container.innerHTML = `<p class="text-red-500">No payroll record found for this employee in the selected month.</p>`;
              }
              document.getElementById("payrollModal").classList.remove("hidden");
            });
        }

        function closePayrollModal() {
          document.getElementById("payrollModal").classList.add("hidden");
        }

        function loadPayrollHistory() {
          window.location.href = "payroll_history.php";
        }