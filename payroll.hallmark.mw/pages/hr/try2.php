// Keep both scrollbars in sync
const topScroll = document.getElementById('top-scroll');
const bottomScroll = document.getElementById('bottom-scroll');
topScroll.addEventListener('scroll', () => {
  bottomScroll.scrollLeft = topScroll.scrollLeft;
});
bottomScroll.addEventListener('scroll', () => {
  topScroll.scrollLeft = bottomScroll.scrollLeft;
});

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
            const welfareFund = parseFloat(row.dataset.welfareFund || 0);
            const weekendOvertime = parseFloat(row.dataset.weekendOvertime || 0);
            const weekdayOvertime = parseFloat(row.dataset.weekdayOvertime || 0);
            const totalOT = parseFloat(row.dataset.totalOvertime || 0);

            totalGross += salary;
            totalNet += net;
            totalPaye += paye;
            totalAdditions += additions;
            totalDeductions += deductions;
            totalPension += (baseNet * 0.05);
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
    
    // Removed payroll_month since it's not used in the backend

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
  const month = now.getMonth() + 1; // getMonth() returns 0-11

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
    let netSalary = salary -paye;
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

    console.log(theemployeeId);

    fetchEmployeeAttendance(theemployeeId).then(data => {
      weekdayHours = data.weekdayHours;
      weekendHours = data.weekendHours;

      console.log("Weekday Hours:", weekdayHours);
      console.log("Weekend Hours:", weekendHours);

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
      netSalary = salary -paye;
      pension = netSalary * 0.05;
      welfareFund = 2000;
      baseNet = netSalary - pension - welfareFund;
    }

    // Calculate hourly rate and overtime pay
    const hourlyRate = emp.hourly_rate;
    if (weekendHours>emp.hours_per_weekend*2) {
      let weekendOTPay = (weekendHours - emp.hours_per_weekend) * hourlyRate * 2;
    } else weekendOTPay = 0;

    if (weekdayHours>emp.hours_per_weekday*5) {
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
      <td class="px-4 py-3">${emp.hours_per_weekend}</td>
      <td class="px-4 py-3">${emp.hours_per_weekday}</td>
      <td class="px-4 py-3 weekend-ot-cell">${weekendOTPay.toFixed(2)}</td>
      <td class="px-4 py-3 weekday-ot-cell">${weekdayOTPay.toFixed(2)}</td>
      <td class="px-4 py-3 total-ot-cell">${overtimePay.toFixed(2)}</td>
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
  // Open bulk modal
  document.getElementById("bulkEditBtn").addEventListener("click", () => {
    const selected = [...document.querySelectorAll('.employeeCheckbox:checked')];
    if (selected.length === 0) {
      alert("Please select at least one employee.");
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

document.getElementById("generatePayrollBtn").addEventListener("click", function() {
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
        'Hours Per Weekend', 'Hours Per Weekday', 'Weekend Overtime', 'Weekday Overtime',
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
        'Yooo',
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
    const tableData = [];
    const selectedEmployees = Array.from(document.querySelectorAll('.employeeCheckbox:checked'));
    
    // Add header row
    tableData.push([
        'Name', 'Department', 'Gender', 'Salary', 'PAYE', 'Net Salary',
        'Pension', 'Additions', 'Deductions', 'Welfare Fund', 'Hourly Rate',
        'Hours Per Weekend', 'Hours Per Weekday', 'Weekend Overtime', 'Weekday Overtime',
        'Total Overtime', 'Hours Per Week', 'Bank Name', 'Bank Account Name', 
        'Bank Account Number', 'Bank Branch', 'Email'
    ]);
    
    // Add employee data
    selectedEmployees.forEach(checkbox => {
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

        // Send email via backend
        fetch('send_payslip.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(employeeData)
        })
        .then(response => response.json())
        .then(data => {
            console.log(`Email sent to ${employeeData.email}:`, data);
        })
        .catch(error => {
            console.error(`Error sending email to ${employeeData.email}:`, error);
        });

        // Now still push to tableData for Excel export
        tableData.push([
            employeeData.name,
            employeeData.department,
            employeeData.gender,
            employeeData.salary,
            employeeData.paye,
            employeeData.netSalary,
            employeeData.pension,
            employeeData.additions,
            employeeData.deductions,
            employeeData.welfareFund,
            '', '', '', '', '', '', '', // skipping overtime stuff here
            employeeData.bankName,
            employeeData.bankAccountName,
            employeeData.bankAccountNumber,
            employeeData.bankBranch,
            employeeData.email
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

// Add this to your main.js file
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('-translate-x-full');
}
