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
