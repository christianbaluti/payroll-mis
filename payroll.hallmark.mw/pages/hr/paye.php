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
  <title>HR Payroll - Employees</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="../../themify/themify-icons.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="./../../includes/logo.png">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }

    .transition-transform {
      transition: transform 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex">

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed md:static z-30 bg-white shadow-lg w-64 h-full overflow-y-auto transform md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 flex items-center text-xl font-bold text-blue-600 border-b">
      <i class="ti-menu-alt mr-2 text-2xl"></i> <a href="./">HR Dashboard</a>
    </div>
    <nav class="mt-6">
  <ul class="space-y-2 font-medium text-gray-700 text-sm">
    <li>
      <a href="./paye.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
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
    <header class="bg-white sticky top-0 z-10 shadow flex justify-between items-center px-4 py-3 border-b">
      <div class="flex items-center gap-3">
        <!-- Toggle Button for Mobile -->
        <button onclick="toggleSidebar()" class="md:hidden text-blue-600 text-2xl focus:outline-none">
          <i class="ti-layout-grid2-alt"></i>
        </button>
        <!-- Title -->
        <span class="hidden md:inline-flex text-xl font-semibold text-blue-600 items-center">
          <i class="ti-layout-grid2-alt mr-2"></i> <a href="./"> Dashboard</a>
        </span>
      </div>
      <div class="flex items-center gap-4 text-sm text-gray-700">
        <span class="whitespace-nowrap"><i class="ti-email text-blue-500 mr-1"></i> <?= $email ?></span>
        <span class="whitespace-nowrap"><i class="ti-user text-purple-500 mr-1"></i> HR</span>
        <a href="../../includes/logout.php" class="text-red-500 hover:text-red-700 font-medium flex items-center">
          <i class="ti-power-off mr-1"></i> Logout
        </a>

      </div>
    </header>

    <!-- Main Dashboard Area -->
    <main class="p-6">
      <!-- Page Title & Button -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-600 flex items-center">
          <i class="ti-notepad mr-2 text-2xl"></i> PAYE Calculator
        </h1>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Gross → Net Calculator -->
        <div class="bg-white p-6 rounded-xl shadow border border-blue-100 hover:shadow-lg transition">
          <h3 class="text-lg font-semibold text-blue-600 flex items-center mb-3">
            🧮 Gross → Net
            <span class="ml-2 text-sm text-gray-400">(Estimate Net Income after PAYE)</span>
          </h3>

          <label for="grossInput" class="block text-sm text-gray-700 mb-1">Gross Salary (MWK)</label>
          <input 
            type="number" 
            id="grossInput" 
            placeholder="e.g., 1,000,000"
            class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >

          <button 
            onclick="calculateFromGross()" 
            class="w-full mt-4 bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition-all">
            Calculate Net Salary
          </button>

          <div id="grossResult" class="text-sm bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2 mt-4 min-h-[100px]">
            <p class="text-gray-400 italic">Results will appear here...</p>
          </div>
        </div>

        <!-- Net → Gross Estimator -->
        <div class="bg-white p-6 rounded-xl shadow border border-green-100 hover:shadow-lg transition">
          <h3 class="text-lg font-semibold text-green-600 flex items-center mb-3">
            🔁 Net → Gross
            <span class="ml-2 text-sm text-gray-400">(Estimate Required Gross for Desired Net)</span>
          </h3>

          <label for="netInput" class="block text-sm text-gray-700 mb-1">Desired Net Salary (MWK)</label>
          <input 
            type="number" 
            id="netInput" 
            placeholder="e.g., 800,000"
            class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500"
          >

          <button 
            onclick="estimateGrossFromNet()" 
            class="w-full mt-4 bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition-all">
            Estimate Required Gross
          </button>

          <div id="netResult" class="text-sm bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2 mt-4 min-h-[100px]">
            <p class="text-gray-400 italic">Results will appear here...</p>
          </div>
        </div>
      </div>

  </div>

  <script>
    function showLoading(container) {
      container.innerHTML = `
        <div class="flex items-center gap-3 text-blue-600">
          <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
          </svg>
          <span>Calculating, please wait...</span>
        </div>
      `;
    }

    function calculateTax(gross) {
      let remaining = gross,
        totalTax = 0,
        breakdown = [];

      if (remaining <= 150000) {
        breakdown.push({
          range: "K0–150,000",
          rate: "0%",
          tax: 0
        });
        return {
          totalTax: 0,
          breakdown
        };
      }
      breakdown.push({
        range: "K0–150,000",
        rate: "0%",
        tax: 0
      });
      remaining -= 150000;

      if (remaining >= 350000) {
        const t = 350000 * .25;
        totalTax += t;
        breakdown.push({
          range: "150,001–500,000",
          rate: "25%",
          tax: t
        });
        remaining -= 350000;
      } else {
        const t = remaining * .25;
        totalTax += t;
        breakdown.push({
          range: `150,001–${150000+remaining}`,
          rate: "25%",
          tax: t
        });
        return {
          totalTax,
          breakdown
        };
      }

      if (remaining >= 2050000) {
        const t = 2050000 * .30;
        totalTax += t;
        breakdown.push({
          range: "500,001–2,550,000",
          rate: "30%",
          tax: t
        });
        remaining -= 2050000;
      } else {
        const t = remaining * .30;
        totalTax += t;
        breakdown.push({
          range: `500,001–${gross}`,
          rate: "30%",
          tax: t
        });
        return {
          totalTax,
          breakdown
        };
      }

      if (remaining > 0) {
        const t = remaining * .35;
        totalTax += t;
        breakdown.push({
          range: "2,550,001+",
          rate: "35%",
          tax: t
        });
      }
      return {
        totalTax,
        breakdown
      };
    }

    function marginalRate(gross) {
      if (gross <= 150000) return 0;
      if (gross <= 500000) return 0.25;
      if (gross <= 2550000) return 0.30;
      return 0.35;
    }

    function calculateFromGross() {
      const g = parseFloat(document.getElementById("grossInput").value);
      const out = document.getElementById("grossResult");
      if (isNaN(g) || g <= 0) {
        out.innerHTML = `<p class="text-red-500">⚠️ Enter a valid gross salary.</p>`;
        return;
      }
      showLoading(out);
      setTimeout(() => {
        const {
          totalTax,
          breakdown
        } = calculateTax(g);
        const net = g - totalTax;
        let html = breakdown.map(b =>
          `<p><strong>${b.range}</strong> @ ${b.rate} = <span class="text-blue-700">K${b.tax.toLocaleString()}</span></p>`
        ).join("");
        html += `<hr class="my-2">
                 <p><strong>Total Tax:</strong> K${totalTax.toLocaleString()}</p>
                 <p><strong>Net Salary:</strong> <span class="text-green-600 font-bold">K${net.toLocaleString()}</span></p>`;
        out.innerHTML = html;
      }, 3000);
    }

    function estimateGrossFromNet() {
      const targetNet = parseFloat(document.getElementById("netInput").value);
      const out = document.getElementById("netResult");
      if (isNaN(targetNet) || targetNet <= 0) {
        out.innerHTML = `<p class="text-red-500">⚠️ Enter a valid desired net salary.</p>`;
        return;
      }
      showLoading(out);
      setTimeout(() => {
        // Newton–Raphson
        let gross = targetNet * 1.3 || 1000; // initial guess
        for (let i = 0; i < 20; i++) {
          const {
            totalTax
          } = calculateTax(gross);
          const net = gross - totalTax;
          const error = net - targetNet;
          if (Math.abs(error) < 1) break;
          const fprime = 1 - marginalRate(gross);
          gross = gross - error / fprime;
        }
        gross = Math.max(0, Math.round(gross));
        const {
          totalTax,
          breakdown
        } = calculateTax(gross);
        const actualNet = gross - totalTax;

        let html = `<p><strong>Estimated Gross Salary:</strong> K${gross.toLocaleString()}</p>
                    <p><strong>Target Net:</strong> K${targetNet.toLocaleString()}</p>
                    <p><strong>Actual Net:</strong> K${actualNet.toLocaleString()}</p>
                    <p><strong>Total Tax:</strong> K${totalTax.toLocaleString()}</p>
                    <hr class="my-2">`;
        html += breakdown.map(b =>
          `<p><strong>${b.range}</strong> @ ${b.rate} = <span class="text-blue-700">K${b.tax.toLocaleString()}</span></p>`
        ).join("");

        out.innerHTML = html;
      }, 3000);
    }
  </script>
  </main>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("-translate-x-full");
    }
  </script>
</body>

</html>