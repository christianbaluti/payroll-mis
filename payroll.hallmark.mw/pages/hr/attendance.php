<?php
  include "attendance_backend.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>HR Payroll Dashboard</title>
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
   #loadingSpinner {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
    }

    .spinner {
      border: 4px solid rgba(255, 255, 255, 0.3);
      border-top: 4px solid #3498db;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" />
</head>

<body class="bg-gray-100 h-screen flex overflow-x-hidden">

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed top-0 left-0 z-30 bg-white shadow-lg w-64 h-full overflow-hidden transform md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-6 flex items-center text-xl font-bold text-blue-600 border-b">
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
      <a href="./attendance.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-300 transition-all duration-300">
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
  <div class="flex-1 flex flex-col min-h-screen md:ml-64 ml-0">
    <!-- Topbar -->
    <header class="bg-white shadow-md sticky top-0 z-10 flex justify-between items-center px-4 py-3 border-b">
      <div class="flex items-center space-x-3">
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleSidebar()" class="md:hidden text-blue-600 text-2xl focus:outline-none">
          <i class="ti-layout-grid2-alt"></i>
        </button>
        <!-- Title (Hidden on mobile) -->
        <span class="text-xl font-semibold text-blue-600 hidden md:flex items-center">
          <i class="ti-layout-grid2-alt mr-2"></i> Dashboard
        </span>
      </div>
      <div class="text-sm text-gray-700 space-x-4 flex items-center">
        <span class="whitespace-nowrap"><i class="ti-email text-blue-500 mr-1"></i> <?= $email ?></span>
        <span class="whitespace-nowrap"><i class="ti-user text-purple-500 mr-1"></i> HR</span>
        <a href="../../includes/logout.php" class="text-red-500 hover:text-red-700 font-medium flex items-center">
          <i class="ti-power-off mr-1"></i> Logout
        </a>
      </div>
    </header>

    <!-- Dashboard Content -->
    <main class="p-4 overflow-y-auto flex-1">

        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-green-100 rounded-2xl p-4 mr-5 flex items-center justify-center">
              <i class="ti-id-badge text-3xl text-green-500"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-1">Date</h3>
              <p class="text-sm text-gray-500">
                <?php
                $date = new DateTime($current_date);
                $formatted_date = $date->format('d F, Y');
                echo $formatted_date;
                ?>
              </p>
            </div>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-2xl p-4 mr-5 flex items-center justify-center">
              <i class="ti-alarm-clock text-3xl text-blue-500"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-1">Time (GMT+2)</h3>
              <p id="realtime" class="text-sm text-gray-500"></p>
            </div>
          </div>

          <div class="bg-white p-6 rounded-2xl shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
            <div class="bg-blue-100 rounded-2xl p-4 mr-5 flex items-center justify-center">
              <i class="ti-user text-3xl text-blue-500"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-1">Present</h3>
              <p class="text-sm text-gray-500">Employees present today: <?= $totalEmployees ?></p>
            </div>
          </div>
        </div>
      
      <h2 class="text-2xl font-semibold text-gray-700 mb-4">Attendance</h2>
      <div class="overflow-auto bg-white rounded-xl shadow-md">
        <!-- Search Filter -->
        <div class="px-4 py-2 mb-4">
          <input
            type="text"
            id="searchInput"
            class="px-4 py-2 rounded border border-gray-300"
            placeholder="Search by employee name..."
            onkeyup="filterTable()">
        </div>

        <!-- Table -->
        <table class="min-w-full text-sm text-left text-gray-600">
          <thead class="bg-gray-100 text-gray-700 text-sm">
            <tr>
              <th class="px-4 py-2">#</th>
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Position</th>
              <th class="px-4 py-2">Department</th>
              <th class="px-4 py-2">Action</th>
            </tr>
          </thead>
          <tbody id="employeeTableBody">
            <?php $i = 1;
            while ($row = $employees->fetch_assoc()): ?>
              <tr class="border-t">
                <td class="px-4 py-2"><?= $i++ ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['position']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['department']) ?></td>
                <td class="px-4 py-2">
                  <?php if (!$row['time_in']): ?>
                    <button onclick="markAttendance(<?= $row['id'] ?>, 'in')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Clock In</button>
                  <?php elseif (!$row['time_out']): ?>
                    <button onclick="markAttendance(<?= $row['id'] ?>, 'out')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Clock Out</button>
                  <?php else: ?>
                    <span class="text-green-600 font-medium">Completed</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <!-- Attendance Summary Filters -->
      <div class="bg-white p-4 mt-8 rounded-xl shadow-md">
        <h3 class="text-xl font-semibold mb-4 text-gray-700">Attendance Summary</h3>

        <div class="flex flex-wrap gap-4 mb-4 items-end">
          <div>
            <label class="block text-gray-600 mb-1">Select Date</label>
            <input type="date" id="datePicker" class="border rounded px-3 py-2" onchange="loadAttendanceSummary('date')">
          </div>
          <div>
            <label class="block text-gray-600 mb-1">Select Week</label>
            <input type="week" id="weekPicker" class="border rounded px-3 py-2" onchange="loadAttendanceSummary('week')">
          </div>
          <div>
            <label class="block text-gray-600 mb-1">Select Month</label>
            <input type="month" id="monthPicker" class="border rounded px-3 py-2" onchange="loadAttendanceSummary('month')">
          </div>
          <div>
            <button onclick="clearFilters()" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">
              Clear Filters
            </button>
          </div>
        </div>

        <!-- Attendance Summary Table -->
        <div class="overflow-auto">
          <table class="min-w-full text-sm text-left text-gray-600" id="summaryTable">
            <thead class="bg-gray-100 text-gray-700 text-sm">
              <tr id="summaryHeader">
                <!-- Headers will be inserted dynamically -->
              </tr>
            </thead>
            <tbody id="summaryBody">
              <!-- Rows will be populated via JS -->
            </tbody>
          </table>
        </div>
      </div>
    </main>

  <!-- Loading Spinner (Initially hidden) -->
  <div id="loadingSpinner" style="display:none;">
    <div class="spinner"></div>
  </div>
  
  <script>

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('-translate-x-full');
    }

    //notify
    const notyf = new Notyf();

    //mark attendance
    function markAttendance(id, type) {
      // Show the loading spinner
      const loadingSpinner = document.getElementById('loadingSpinner');
      loadingSpinner.style.display = 'block';

      fetch('mark_attendance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id,
            type
          })
        })
        .then(res => res.json())
        .then(data => {
          // Hide the loading spinner once the response is received
          loadingSpinner.style.display = 'none';

          if (data.status === 'success') {
            notyf.success(data.message);
            setTimeout(() => location.reload(), 1000);
          } else {
            notyf.error(data.message);
          }
        })
        .catch(err => {
          // Hide the loading spinner in case of error
          loadingSpinner.style.display = 'none';
          console.error(err);
          notyf.error("Something went wrong.");
        });
    }

    //toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    }

    // Optional: Close sidebar when clicking outside on mobile
    window.addEventListener('click', function(e) {
      const sidebar = document.getElementById('sidebar');
      if (!sidebar.contains(e.target) && !e.target.closest('button')) {
        if (window.innerWidth < 768) {
          sidebar.classList.add('-translate-x-full');
        }
      }
    });

    // Filter function to search employee names
    function filterTable() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll('#employeeTableBody tr');

      rows.forEach(row => {
        const nameCell = row.querySelector('td:nth-child(2)'); // 2nd column is the name column
        const name = nameCell ? nameCell.textContent.toLowerCase() : '';

        // Show or hide the row based on the input value
        if (name.includes(input)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // Attendance search for days, weeks, and months
    async function loadAttendanceSummary(type) {
      const date = document.getElementById('datePicker').value;
      const week = document.getElementById('weekPicker').value;
      const month = document.getElementById('monthPicker').value;
      let param = '';

      if (type === 'date') param = `date=${date}`;
      else if (type === 'week') param = `week=${week}`;
      else if (type === 'month') param = `month=${month}`;

      if (!param) return; // No value, don't fetch

      try {
        const response = await fetch(`attendance_summary_backend.php?${param}`);
        const data = await response.json();
        renderSummaryTable(data, type);
      } catch (err) {
        console.error('Failed to fetch summary:', err);
      }
    }

    function renderSummaryTable(data, type) {
      const header = document.getElementById('summaryHeader');
      const body = document.getElementById('summaryBody');
      header.innerHTML = '';
      body.innerHTML = '';

      if (type === 'date') {
        header.innerHTML = `
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Clock In</th>
          <th class="px-4 py-2">Clock Out</th>
          <th class="px-4 py-2">Hours</th>
          <th class="px-4 py-2">Remarks</th>`;
        data.forEach(row => {
          body.innerHTML += `
            <tr class="border-t">
              <td class="px-4 py-2">${row.name}</td>
              <td class="px-4 py-2">${row.time_in}</td>
              <td class="px-4 py-2">${row.time_out}</td>
              <td class="px-4 py-2">${row.hours}</td>
              <td class="px-4 py-2">${row.remarks}</td>
            </tr>`;
        });
      } else if (type === 'week') {
        header.innerHTML = `<th class="px-4 py-2">Name</th>` + 
                           data.days.map(day => `<th class="px-4 py-2">${day}</th>`).join('') + 
                           `<th class="px-4 py-2">Total Hours</th>`;

        data.records.forEach(row => {
          let rowHTML = `<td class="px-4 py-2">${row.name}</td>`;
          data.days.forEach((day, idx) => {
            rowHTML += `<td class="px-4 py-2">${row.hours[Object.keys(row.hours)[idx]] || '-'}</td>`;
          });
          rowHTML += `<td class="px-4 py-2 font-semibold">${row.total_hours}</td>`;
          body.innerHTML += `<tr class="border-t">${rowHTML}</tr>`;
        });
      } // Modified month summary rendering
        else if (type === 'month') {
            // Get current year and month from picker
            const [year, month] = document.getElementById('monthPicker').value.split('-');
            
            // Generate days array for the selected month
            const daysInMonth = new Date(year, parseInt(month) + 1, 0).getDate();
            const days = Array.from({length: daysInMonth}, (_, i) => i + 1);
            
            header.innerHTML = `<th class="px-4 py-2">Name</th>` + 
                               days.map(day => `<th class="px-1 py-2">${day}</th>`).join('') + 
                               `<th class="px-4 py-2">Total Hours</th>`;
            
            data.records.forEach(row => {
                let rowHTML = `<td class="px-4 py-2">${row.name}</td>`;
                days.forEach(day => {
                    const dateKey = `${year}-${month.padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    rowHTML += `<td class="px-1 py-2 text-center">${row.present_days.includes(dateKey) ? '✔️' : '❌'}</td>`;
                });
                rowHTML += `<td class="px-4 py-2 font-semibold">${row.total_hours}</td>`;
                body.innerHTML += `<tr class="border-t">${rowHTML}</tr>`;
            });
        }
    }

    // Clear filters and reset table
    function clearFilters() {
      document.getElementById('datePicker').value = '';
      document.getElementById('weekPicker').value = '';
      document.getElementById('monthPicker').value = '';
      document.getElementById('summaryHeader').innerHTML = '';
      document.getElementById('summaryBody').innerHTML = '';
    }    
  </script>

  <?php
// Set timezone to Africa/Blantyre (GMT+2)
date_default_timezone_set('Africa/Blantyre');
$currentTime = date('Y-m-d H:i:s');
?>
<script>
    // Set initial time from server
    let serverTime = new Date('<?php echo $currentTime; ?>');

    function updateClock() {
        const timeElement = document.getElementById('realtime');

        // Format time as HH:mm:ss
        const hours = String(serverTime.getHours()).padStart(2, '0');
        const minutes = String(serverTime.getMinutes()).padStart(2, '0');
        const seconds = String(serverTime.getSeconds()).padStart(2, '0');

        timeElement.textContent = `${hours}:${minutes}:${seconds}`;

        // Increment serverTime by 1 second
        serverTime.setSeconds(serverTime.getSeconds() + 1);
    }

    // Update every second
    setInterval(updateClock, 1000);

    // Initialize immediately
    updateClock();
</script>


</body>
</html>