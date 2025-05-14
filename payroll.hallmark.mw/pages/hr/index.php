<?php 

  include "backend.php";

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

    .chart-container {
      position: relative;
      width: 100%;
      aspect-ratio: 2 / 1; /* This keeps a nice wide chart shape without stretching */
      max-height: 350px;
    }
    canvas {
      width: 100% !important;
      height: 100% !important;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex relative min-h-screen overflow-x-hidden">

  <!-- Sidebar (Mobile + Desktop) -->
 <aside id="sidebar" class="fixed md:sticky top-0 left-0 w-64 bg-white shadow-lg h-screen overflow-y-auto flex-shrink-0 transition-transform transform -translate-x-full md:translate-x-0 md:block z-30">
    <div class="p-6 text-xl font-bold text-blue-600 flex items-center">
      <i class="ti-menu-alt mr-2 text-2xl"></i> HR Dashboard
    </div>
    <nav class="mt-6">
  <ul class="space-y-2 text-gray-700 font-medium text-sm">
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
  <div class="flex-1 flex flex-col overflow-auto">

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
    <main class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

  <div class="bg-white p-6 rounded-2xl shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-blue-100 rounded-2xl p-4 mr-5 flex items-center justify-center">
      <i class="ti-user text-3xl text-blue-500"></i>
    </div>
    <div>
      <h3 class="text-lg font-semibold text-gray-800 mb-1">Employees</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalEmployees ?></p>
    </div>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-green-100 rounded-2xl p-4 mr-5 flex items-center justify-center">
      <i class="ti-id-badge text-3xl text-green-500"></i>
    </div>
    <div>
      <h3 class="text-lg font-semibold text-gray-800 mb-1">Departments</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalDepartments ?></p>
    </div>
  </div>

  <div class="bg-white p-6 rounded-2xl shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-yellow-100 rounded-2xl p-4 mr-5 flex items-center justify-center">
      <i class="ti-map-alt text-3xl text-yellow-500"></i>
    </div>
    <div>
      <h3 class="text-lg font-semibold text-gray-800 mb-1">Branches</h3>
      <p class="text-sm text-gray-500">Total: <?= $totalBranches ?></p>
    </div>
  </div>

</main>

      <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">

      <!-- Chart 1 -->
      <div class="bg-white p-5 rounded-xl shadow-md flex flex-col">
        <h3 class="text-lg font-bold mb-2">Employee Gender Distribution</h3>
        <div class="chart-container">
          <canvas id="genderChart"></canvas>
        </div>
      </div>

      <!-- Chart 3 -->
      <div class="bg-white p-5 rounded-xl shadow-md flex flex-col">
          <h3 class="text-lg font-bold mb-2">Employees per Department</h3>
          <div class="chart-container flex-1">
              <canvas id="employeesPerDepartmentChart"></canvas>
          </div>
      </div>

      <!-- Chart 4 -->
      <div class="bg-white p-5 rounded-xl shadow-md flex flex-col">
          <h3 class="text-lg font-bold mb-2">Employees per Branch</h3>
          <div class="chart-container flex-1">
              <canvas id="employeesPerBranchChart"></canvas>
          </div>
      </div>

    </div>

    <div class="p-4 grid grid-cols-1 md:grid-cols-1 gap-4">
      <div class="bg-white p-5 rounded-xl shadow-md flex flex-col">
        <h3 class="text-lg font-bold mb-2">Monthly Calendar</h3>
        <div id="calendar" class="flex-1"></div>
      </div>
    </div>



  </div>

  <!-- JS to toggle sidebar -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    }

    // Optional: Close sidebar when clicking outside on mobile
    window.addEventListener('click', function (e) {
      const sidebar = document.getElementById('sidebar');
      if (!sidebar.contains(e.target) && !e.target.closest('button')) {
        if (window.innerWidth < 768) {
          sidebar.classList.add('-translate-x-full');
        }
      }
    });
  </script>
  <script>
    fetch('api/get_salary_gender_charts.php')
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        const genderConfig = {
          type: 'pie',
          data: {
            labels: ['Male', 'Female'],
            datasets: [{
              data: [
                data.employeeByGender.male || 0,
                data.employeeByGender.female || 0
              ],
              backgroundColor: ['#3490dc', '#ff69b4']
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        };

        const salaryConfig = {
          type: 'bar',
          data: {
            labels: ['0-100K', '100K-300K', '300K-500K', '500K+'],
            datasets: [{
              data: [
                data.salaryRanges['0-100K'] || 0,
                data.salaryRanges['100K-300K'] || 0,
                data.salaryRanges['300K-500K'] || 0,
                data.salaryRanges['500K+'] || 0
              ],
              backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#f56565']
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        };

        // Create charts only after data is ready
        new Chart(document.getElementById('genderChart'), genderConfig);
        new Chart(document.getElementById('salaryChart'), salaryConfig);
      })
      .catch(error => {
        console.error('Error fetching data:', error);
        // Show error message to user
        document.getElementById('genderChart').parentElement.innerHTML = 
          '<div class="text-red-500">Error loading gender distribution chart</div>';
        document.getElementById('salaryChart').parentElement.innerHTML = 
          '<div class="text-red-500">Error loading salary distribution chart</div>';
      });
  </script>

  <script type="text/javascript">
    // Add this to your existing dashboard.js file
      document.addEventListener('DOMContentLoaded', function() {
          // Initialize all charts
          initializeCharts();
      });

      function initializeCharts() {
          // Initialize all charts with their respective configurations
          initializeEmployeesPerDepartmentChart();
          initializeEmployeesPerBranchChart();
          initializeDepartmentsPerBranchChart();
          initializeMonthlyAttendanceChart();
          initializeDailyAttendanceChart();
          initializeWeeklyAttendanceChart();
      }

      function initializeEmployeesPerDepartmentChart() {
          fetch('api/get_employees_per_department.php')
              .then(response => response.json())
              .then(data => {
                  // Extract labels and counts from the API response
                  const labels = data.data.map(item => item.department);
                  const counts = data.data.map(item => parseInt(item.count));

                  new Chart(document.getElementById('employeesPerDepartmentChart'), {
                      type: 'bar',
                      data: {
                          labels: labels,
                          datasets: [{
                              label: 'Employees',
                              data: counts,
                              backgroundColor: '#4A90E2',
                              barThickness: 20,
                              maxBarThickness: 20,
                              minBarLength: 2,
                          }]
                      },
                      options: {
                          responsive: true,
                          maintainAspectRatio: false,
                          scales: {
                              x: {
                                  ticks: {
                                      autoSkip: false,
                                      maxRotation: 90,
                                      minRotation: 70,
                                      fontSize: '0.2rem'
                                  },
                                  grid: {
                                      display: false
                                  }
                              },
                              y: {
                                  beginAtZero: true,
                                  grid: {
                                      display: true
                                  }
                              }
                          },
                          plugins: {
                              legend: {
                                  display: false
                              }
                          }
                      }
                  });
              });
      }

      function initializeEmployeesPerBranchChart() {
          fetch('api/get_employees_per_branch.php')
              .then(response => response.json())
              .then(data => {
                  // Extract labels and counts from the API response
                  const labels = data.data.map(item => item.branch);
                  const counts = data.data.map(item => parseInt(item.count));

                  new Chart(document.getElementById('employeesPerBranchChart'), {
                      type: 'bar',
                      data: {
                          labels: labels,
                          datasets: [{
                              label: 'Employees',
                              data: counts,
                              backgroundColor: '#34D399',
                              barThickness: 20,
                              maxBarThickness: 20,
                              minBarLength: 2,
                          }]
                      },
                      options: {
                          responsive: true,
                          maintainAspectRatio: false,
                          scales: {
                              x: {
                                  ticks: {
                                      autoSkip: false,
                                      maxRotation: 90,
                                      minRotation: 70,
                                      fontSize: '0.2rem'
                                  },
                                  grid: {
                                      display: false
                                  }
                              },
                              y: {
                                  beginAtZero: true,
                                  grid: {
                                      display: true
                                  }
                              }
                          },
                          plugins: {
                              legend: {
                                  display: false
                              }
                          }
                      }
                  });
              });
      }


      // Common function to fetch chart data
      function fetchChartData(url, chart) {
          fetch(url)
              .then(response => response.json())
              .then(data => {
                  if (data.status) {
                      chart.data.labels = data.data.map(item => item.department || item.branch || item.date || item.week);
                      chart.data.datasets.forEach((dataset, index) => {
                          dataset.data = data.data.map(item => item.count || 
                              (item.present ? item.present : 0) || 
                              (item.absent ? item.absent : 0));
                      });
                      chart.update();
                  }
              })
              .catch(error => console.error('Error fetching chart data:', error));
      }
  </script>

  <!-- FullCalendar CSS -->
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  if (!calendarEl) {
    console.error('Calendar div not found!');
    return;
  }

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: ''
    },
    events: []
  });

  calendar.render();

  async function loadHolidays() {
    try {
      const year = new Date().getFullYear();
      
      const malawiRes = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/MW`);
      if (!malawiRes.ok) throw new Error('Failed to fetch Malawi holidays');
      const malawiHolidays = await malawiRes.json();

      const usaRes = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/US`);
      if (!usaRes.ok) throw new Error('Failed to fetch USA holidays');
      const usaHolidays = await usaRes.json();

      let allEvents = [];

      malawiHolidays.forEach(holiday => {
        allEvents.push({
          title: holiday.localName + ' (Malawi)',
          date: holiday.date
        });
      });

      usaHolidays.forEach(holiday => {
        if ([
          "New Year's Day", 
          "Labour Day", 
          "Christmas Day", 
          "Independence Day", 
          "Good Friday", 
          "Thanksgiving Day"
        ].includes(holiday.name)) {
          allEvents.push({
            title: holiday.name + ' (International)',
            date: holiday.date
          });
        }
      });

      allEvents.forEach(event => {
        calendar.addEvent(event);
      });

    } catch (error) {
      console.error('Error loading holidays:', error.message);
    }
  }

  loadHolidays();
});
</script>




</body>
</html>
