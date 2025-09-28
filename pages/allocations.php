<?php
session_start();
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['budget', 'school_admin'])) {
    header('Location: ../login.php');
    exit;
}
$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin • Allocations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { maroon: '#800000','maroon-dark':'#5a0000' } } } }
    </script>
</head>
<body class="bg-gray-50">
<div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-maroon">BudgetTrack</h2>
            <p class="text-sm text-gray-600">Administration Panel</p>
        </div>
        <nav class="mt-6">
            <a href="admin_dashboard.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">Dashboard</a>
            <a href="allocations.php" class="flex items-center px-6 py-3 text-maroon bg-red-50 border-r-4 border-maroon">Allocations</a>
            <a href="ppmp_lib.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">PPMP & LIB</a>
            <a href="reports_admin.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">Reports</a>
            <a href="users.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">User Management</a>
            <a href="departments.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">Departments</a>
            <a href="notifications_admin.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">Notifications</a>
            <a href="settings_admin.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">Settings</a>
            <button onclick="confirmLogout()" class="flex w-full items-center px-6 py-3 text-white bg-red-600 hover:bg-red-700 mt-4">Logout</button>
        </nav>
    </aside>
    <main class="flex-1">
        <header class="bg-white border-b px-6 py-4">
            <div class="flex items-center space-x-4 mb-2">
                <button onclick="goBack()" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <h1 class="text-2xl font-bold text-gray-900">Allocations</h1>
            </div>
            <p class="text-gray-600">Set and manage allocations by department and category.</p>
        </header>
        <section class="p-6">
            <div class="bg-white border rounded-xl p-6">
                <p class="text-gray-600">This is a placeholder page. Implement forms and tables here.</p>
            </div>
        </section>
    </main>
</div>
<div id="logoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Confirm Logout</h3>
        <button onclick="closeLogoutModal()" class="text-gray-400 hover:text-gray-600">✕</button>
      </div>
      <p class="text-gray-600 mb-6">Are you sure you want to logout?</p>
      <div class="flex justify-end gap-3">
        <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
        <button onclick="performLogout()" class="px-4 py-2 bg-red-600 text-white rounded">Logout</button>
      </div>
    </div>
  </div>
</div>
<script>
function confirmLogout(){document.getElementById('logoutModal').classList.remove('hidden')} 
function closeLogoutModal(){document.getElementById('logoutModal').classList.add('hidden')} 
function performLogout(){window.location.href='../auth/logout.php'}

// Back button functionality
function goBack() {
  // Check user role and redirect to appropriate dashboard
  <?php if ($_SESSION['user_role'] === 'budget'): ?>
    window.location.href = 'admin_dashboard.php';
  <?php elseif ($_SESSION['user_role'] === 'school_admin'): ?>
    window.location.href = 'school_admin_dashboard.php';
  <?php else: ?>
    window.location.href = 'dept_dashboard.php';
  <?php endif; ?>
}
</script>
</body>
</html>
