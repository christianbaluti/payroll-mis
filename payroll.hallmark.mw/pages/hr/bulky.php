<?php 
  include "backend.php"; // Pulls current user data
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Settings | HR Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="../../themify/themify-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="./../../includes/logo.png">
  <style>
    body { font-family: 'Segoe UI', sans-serif; }
    .transition-transform { transition: transform 0.3s ease-in-out; }
    .disabled * { pointer-events: none; opacity: 0.6; }
  </style>
</head>
<body class="bg-gray-100 flex overflow-hidden h-screen flex">

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg overflow-y-auto flex-shrink-0 transition-transform transform -translate-x-full md:translate-x-0 md:static md:inset-auto md:shadow-none z-30">
  <div class="p-6 text-xl font-bold text-blue-600 flex items-center">
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
      <a href="./bulky.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
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
<div class="flex-1 flex flex-col h-full">

  <!-- Topbar -->
  <header class="bg-white shadow-md sticky top-0 z-10 flex justify-between items-center px-4 py-3 border-b">
    <div class="flex items-center space-x-3">
      <button onclick="toggleSidebar()" class="md:hidden text-blue-600 text-2xl focus:outline-none">
        <i class="ti-layout-grid2-alt"></i>
      </button>
      <span class="text-xl font-semibold text-blue-600 hidden md:flex items-center">
        <i class="ti-layout-grid2-alt mr-2"></i> <a href="./">Dashboard</a>
      </span>
    </div>
    <div class="text-sm text-gray-700 space-x-4 flex items-center">
      <span><i class="ti-email text-blue-500 mr-1"></i> <?= $email ?></span>
      <span><i class="ti-user text-purple-500 mr-1"></i> HR</span>
      <a href="../../includes/logout.php" class="text-red-500 hover:text-red-700 font-medium flex items-center">
        <i class="ti-power-off mr-1"></i> Logout
      </a>
    </div>
  </header>

  <!-- Bulky Emails -->
  <main class="flex-1 px-4 sm:px-8 py-8">
    <div class="bg-white shadow rounded p-6 max-w-5xl mx-auto w-full">
      <h1 class="text-2xl font-bold mb-6 text-blue-600">Send Bulk Email to Employees</h1>

      <!-- Employee Selection -->
      <div class="mb-6">
        <label class="font-semibold mb-2 block text-gray-700">Select Employees:</label>
        <div id="employeeList" class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-72 overflow-y-auto p-2 border rounded bg-gray-50"></div>
        <div class="mt-2">
          <button id="selectAll" class="text-sm text-blue-600 hover:underline">Select All</button>
          <button id="deselectAll" class="text-sm text-red-600 hover:underline ml-4">Deselect All</button>
        </div>
      </div>

      <!-- Editor -->
      <div id="editor" class="bg-white h-64 mb-6"></div>

      <!-- Send Button -->
      <button id="sendButton" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
        <i class="ti-email mr-2"></i> Send Email
      </button>
    </div>
  </main>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('-translate-x-full');
}

  // Handle outside clicks for mobile sidebar
  document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (event) => {
      if (window.innerWidth <= 768 && // Mobile breakpoint
          sidebar.classList.contains('transform') &&
          !sidebar.classList.contains('-translate-x-full') &&
          !sidebar.contains(event.target) &&
          !event.target.closest('.md\\:hidden')) {
        toggleSidebar();
      }
    });
  });

var quill = new Quill('#editor', { theme: 'snow' });

// Fetch employees
fetch('employees_to_mail.php')
  .then(response => response.json())
  .then(data => {
    const employeeList = document.getElementById('employeeList');
    data.employees.forEach(emp => {
      const div = document.createElement('div');
      div.innerHTML = `
        <label class="flex items-center space-x-2 text-gray-700">
          <input type="checkbox" value="${emp.email}" class="employeeCheckbox">
          <span>${emp.name} (${emp.email})</span>
        </label>
      `;
      employeeList.appendChild(div);
    });
  })
  .catch(err => console.error(err));

// Select All / Deselect All
document.getElementById('selectAll').addEventListener('click', () => {
  document.querySelectorAll('.employeeCheckbox').forEach(c => c.checked = true);
});
document.getElementById('deselectAll').addEventListener('click', () => {
  document.querySelectorAll('.employeeCheckbox').forEach(c => c.checked = false);
});

// Add this at the bottom of your existing script section
let isProcessing = false;



  document.getElementById('sendButton').addEventListener('click', async () => {
    const selectedEmails = Array.from(document.querySelectorAll('.employeeCheckbox:checked')).map(c => c.value);
    const content = quill.root.innerHTML.trim();

    if (selectedEmails.length === 0) {
      Swal.fire('No Employees', 'Please select at least one employee.', 'warning');
      return;
    }
    if (content === "<p><br></p>") {
      Swal.fire('Empty Email', 'Please write some content to send.', 'warning');
      return;
    }

    if (isProcessing) return;
    isProcessing = true;

    try {
      // Show loading spinner
      const loading = Swal.fire({
        didOpen: () => {
          Swal.showLoading();
        },
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        backdrop: true
      });

      // Actually send the emails
      const response = await fetch('send_bulk_email.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          emails: selectedEmails,
          message: content
        })
      });

      const result = await response.json();

      Swal.close(); // Close loading spinner

      if (result.status === 'success') {
        Swal.fire('Success', result.message, 'success');
      } else {
        throw new Error(result.message);
      }
    } catch (error) {
      Swal.close(); // Ensure loading spinner is closed even on error
      Swal.fire('Error', error.message || 'Failed to send emails.', 'error');
      console.error(error);
    } finally {
      isProcessing = false;
    }
  });

</script>

</body>
</html>
