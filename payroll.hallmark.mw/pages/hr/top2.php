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
    <!-- Remove duplicate jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notifyjs-browser/dist/notify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Notyf CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
      // Initialize Notyf with custom settings
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
        height: 20px; /* height to show scrollbar only */
      }
      .scroll-shadow {
        height: 1px;  /* tiny height so scrollbar appears but nothing inside */
        pointer-events: none;
      }
      .table-container {
        width: 100%;
        overflow-x: auto;
      }
      /* Sync scroll between top and bottom scrollbars */
      .scroll-wrapper,
      .table-container {
        scrollbar-width: thin;
      }
    </style>
  </head>
  <!--keep this kaye-->
    <!-- Loader Modal -->
    <div id="loaderModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:#000000aa; z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:30px; border-radius:10px; text-align:center;">
            <h3>Processing...</h3>
            <p>Payslips are being generated and sent. Please wait.</p>
            <div class="spinner" style="margin-top:20px;">
                <div style="border: 8px solid #f3f3f3; border-top: 8px solid #3498db; border-radius: 50%; width: 60px; height: 60px; animation: spin 1s linear infinite;"></div>
            </div>
        </div>
    </div>

    <style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>

  <!--keep this kaye-->

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
          Payroll Generation
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
          <input type="month" id="payrollMonth" title="Select payroll month"
            class="border p-2 rounded w-full" value="<?= date('Y-m') ?>">
        </div>
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
</script>