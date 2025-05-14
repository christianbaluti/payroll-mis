<!-- Add Employee Button -->
<button onclick="toggleAddEmployeeModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm flex items-center">
  <i class="ti-plus mr-2"></i> Add Employee
</button>

<!-- User Creation Modal -->
<div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
  <div class="max-w-3xl w-full bg-white shadow-xl rounded-2xl p-8 relative">
    <!-- Close Button -->
    <button onclick="toggleAddEmployeeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">
      &times;
    </button>

    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
      <i class="ti-user mr-2 text-blue-600 text-3xl"></i> Create New User
    </h2>

    <form id="createUserForm" class="space-y-5">
      <!-- Email -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
          <i class="ti-email text-gray-400 mr-2"></i>
          <input type="email" name="email" required class="w-full outline-none" placeholder="Enter user email" />
        </div>
      </div>

      <!-- Role -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Role</label>
        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
          <i class="ti-id-badge text-gray-400 mr-2"></i>
          <select name="role" required class="w-full outline-none bg-transparent">
            <option value="" disabled selected>Select Role</option>
            <option value="admin">Admin</option>
            <option value="hr">HR</option>
            <option value="manager">Manager</option>
          </select>
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md">
          <i class="ti-email mr-2"></i> Send Account Invitation
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-60 flex items-center justify-center z-50 hidden">
  <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-75"></div>
</div>

<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- Notyf (for Toasts) -->
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" />

<!-- Toggle Modal Script -->
<script>
  function toggleAddEmployeeModal() {
    const modal = document.getElementById("addEmployeeModal");
    modal.classList.toggle("hidden");
  }

  const notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' }
  });

  document.getElementById("createUserForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const spinner = document.getElementById("loadingSpinner");

    spinner.classList.remove("hidden");

    fetch("create_user_invite.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      spinner.classList.add("hidden");

      if (data.status === "success") {
        notyf.success(data.message);
        e.target.reset();
        toggleAddEmployeeModal(); // Close modal after success
      } else {
        notyf.error(data.message);
      }
    })
    .catch(error => {
      spinner.classList.add("hidden");
      notyf.error("Something went wrong. Try again.");
      console.error("Fetch error:", error);
    });
  });
</script>
