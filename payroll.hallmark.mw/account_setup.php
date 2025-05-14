<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account Setup - Hallmark Payroll</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="./themify/themify-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
  </style>
<?php
require "./db2.php";
$token = $_GET['token'] ?? '';
if (!$token) {
    die("Invalid or missing token.");
}
$stmt = $conn->prepare("SELECT email FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
    exit;
}
$row = $result->fetch_assoc();
$email = $row['email'];
?>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

<!-- Spinner -->
<div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-60 flex items-center justify-center z-50 hidden">
  <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-opacity-75"></div>
</div>

<!-- Setup Form -->
<div class="max-w-xl w-full bg-white shadow-xl rounded-2xl p-8">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
    <i class="ti-lock mr-2 text-blue-600 text-3xl"></i> Set Your Password for <?= htmlspecialchars($email) ?>
  </h2>
  <form id="setupForm" class="space-y-5">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <!-- Password -->
    <div>
      <label class="block text-gray-700 font-medium mb-1">New Password</label>
      <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
        <i class="ti-key text-gray-400 mr-2"></i>
        <input type="password" name="password" required minlength="8" class="w-full outline-none"
               placeholder="Enter password"
               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
               title="Password must be at least 8 characters, contain uppercase, lowercase, number and special character">
      </div>
    </div>

    <!-- Confirm Password -->
    <div>
      <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
      <div class="flex items-center border border-gray-300 rounded-md px-3 py-2">
        <i class="ti-key text-gray-400 mr-2"></i>
        <input type="password" name="confirm_password" required minlength="8" class="w-full outline-none"
               placeholder="Confirm password">
      </div>
    </div>

    <div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md">
        <i class="ti-check mr-2"></i> Complete Account Setup
      </button>
    </div>
  </form>
</div>

<script>
    const notyf = new Notyf({ duration: 3000, ripple: true });

  $('#setupForm').on('submit', function (e) {
    e.preventDefault();

    $('#loadingSpinner').removeClass('hidden');

    $.ajax({
      url: 'set_password_backend.php',
      type: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        $('#loadingSpinner').addClass('hidden');

        try {
          const res = JSON.parse(response);

          if (res.status === 'success') {
            notyf.success(res.message);
            setTimeout(() => {
              window.location.href = 'index.html';
            }, 2000);
          } else {
            notyf.error(res.message);
          }
        } catch (e) {
          notyf.error('Unexpected server error.');
        }
      },
      error: function () {
        $('#loadingSpinner').addClass('hidden');
        notyf.error('Failed to connect to server.');
      }
    });
  });
</script>

</body>
</html>
