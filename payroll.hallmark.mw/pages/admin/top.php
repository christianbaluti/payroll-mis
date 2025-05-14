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
      <nav class="mt-4">
        <ul class="space-y-2 font-medium text-gray-700">
          <li><a href="./employees.php"
              class="block px-6 py-3 hover:bg-blue-100"><i
                class="ti-user mr-2"></i> Employees</a></li>
          <li><a href="./departments.php"
              class="block px-6 py-3 hover:bg-blue-100"><i
                class="ti-id-badge mr-2"></i> Departments</a></li>
          <li><a href="./branches.php"
              class="block px-6 py-3 hover:bg-blue-100"><i
                class="ti-map-alt mr-2"></i> Branches</a></li>
          <li><a href="./payrolls.php"
              class="block px-6 py-3 bg-blue-100 hover:bg-blue-300"><i
                class="ti-money mr-2"></i> Generate Payroll</a></li>
          <li><a href="./leave.php" class="block px-6 py-3 hover:bg-blue-100"><i
                class="ti-files mr-2"></i> Leave Requests</a></li>
          <li><a href="./settings.php"
              class="block px-6 py-3 hover:bg-blue-100"><i
                class="ti-settings mr-2"></i> Settings</a></li>
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
