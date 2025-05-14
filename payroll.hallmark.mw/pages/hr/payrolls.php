<?php include 'top.php' ?>
<!-- Actions -->
<div id="totalsSection" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-6 text-xs text-white font-semibold">
  
  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-wallet text-2xl text-blue-500"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Gross Pay</h3>
      <p id="totalGrossDisplay" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-money text-2xl text-blue-600"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Net Pay</h3>
      <p id="totalNetDisplay" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-bar-chart-alt text-2xl text-blue-500"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">PAYE</h3>
      <p id="totalPayeDisplay" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-briefcase text-2xl text-blue-600"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Pension (5%)</h3>
      <p id="totalPensionDisplay" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-blue-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-plus text-2xl text-green-500"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Additions</h3>
      <p id="totalAdditionsDisplay" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-green-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-minus text-2xl text-green-700"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Deductions</h3>
      <p id="totalDeductionsDisplay" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-red-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-heart-broken text-2xl text-red-500"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Welfare Fund</h3>
      <p id="totalWelfareFund" class="text-xs text-gray-500">MWK 0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-pink-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-calendar text-2xl text-pink-600"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Weekend OT</h3>
      <p id="totalWeekendOvertime" class="text-xs text-gray-500">0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-yellow-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-calendar text-2xl text-yellow-500"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Weekday OT</h3>
      <p id="totalWeekdayOvertime" class="text-xs text-gray-500">0.00</p>
    </div>
  </div>

  <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105 transition-transform duration-300 flex items-center cursor-pointer">
    <div class="bg-gray-100 rounded-lg p-2 mr-3 flex items-center justify-center">
      <i class="ti-time text-2xl text-gray-500"></i>
    </div>
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Total OT</h3>
      <p id="totalOvertime" class="text-xs text-gray-500">0.00</p>
    </div>
  </div>

</div>

<br>  
<!--All totals-->
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
<div class="scroll-wrapper" id="top-scroll">
  <div class="scroll-shadow" style="width: 1250px; display: none;"></div>
</div>

<div id="bottom-scroll" class="table-container bg-white rounded-lg shadow overflow-x-auto">
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
          <th class="px-4 py-3">Welfare Fund</th>
          <th class="px-4 py-3">Hourly Rate</th>
          <th class="px-4 py-3">Hours Per Weekend</th>
          <th class="px-4 py-3">Hours Per Weekday</th>
          <th class="px-4 py-3">Weekend Overtime</th>
          <th class="px-4 py-3">Weekday Overtime</th>
          <th class="px-4 py-3">Total Overtime</th>
          <th class="px-4 py-3">Hours/Month</th>
          <th class="px-4 py-3">Bank Name</th>
          <th class="px-4 py-3">Bank Account Name</th>
          <th class="px-4 py-3">Bank Account Number</th>
          <th class="px-4 py-3">Bank Branch</th>
          <th class="px-4 py-3">Email</th>
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
<script src="main.js"></script>
</body>
</html>