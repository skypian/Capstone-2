<?php
session_start();

// Check if user is logged in and has school admin access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'school_admin') {
    header('Location: ../login.php');
    exit;
}

$userRole = $_SESSION['user_role'];
$userName = $_SESSION['user_name'];
$userEmail = $_SESSION['user_email'];
$departmentName = $_SESSION['department_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Administrator Dashboard - BudgetTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .maroon { color: #800000; }
        .bg-maroon { background-color: #800000; }
        .border-maroon { border-color: #800000; }
        .hover\:bg-maroon:hover { background-color: #800000; }
        .hover\:text-maroon:hover { color: #800000; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <img src="../img/evsu_logo.png" alt="EVSU Logo" class="h-12 w-12 mr-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">BudgetTrack</h2>
                        <p class="text-sm text-gray-600">School Administrator</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6">
                <a href="school_admin_dashboard.php" class="flex items-center px-6 py-3 text-white bg-maroon">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="view_users.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <i class="fas fa-users mr-3"></i>
                    View Users
                </a>
                <a href="view_allocations.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <i class="fas fa-chart-pie mr-3"></i>
                    View Allocations
                </a>
                <a href="view_ppmp.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <i class="fas fa-file-alt mr-3"></i>
                    View PPMP Submissions
                </a>
                <a href="view_reports.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <i class="fas fa-chart-bar mr-3"></i>
                    View Reports
                </a>
                <a href="view_departments.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <i class="fas fa-building mr-3"></i>
                    View Departments
                </a>
                <?php include __DIR__ . '/../components/settings_dropdown.php'; ?>
                <button onclick="confirmLogout()" class="flex w-full items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">School Administrator Dashboard</h1>
                        <p class="text-gray-600">View-only access to monitor system activities</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <?php 
                        require_once __DIR__ . '/../classes/Notification.php';
                        $notification = new Notification();
                        $notifications = $notification->getUserNotifications($_SESSION['user_id'], 5);
                        $unreadCount = $notification->getUnreadCount($_SESSION['user_id']);
                        include __DIR__ . '/../components/notification_bell.php'; 
                        ?>
                        
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($userName); ?></p>
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($userEmail); ?></p>
                        </div>
                        <div class="w-10 h-10 bg-maroon rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Welcome Message -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl shadow-sm p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-eye text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-800">View-Only Access</h3>
                    </div>
                    <p class="text-blue-700 mb-4">As School Administrator, you have view-only access to monitor all system activities. You can see user activities, budget allocations, PPMP submissions, and reports, but cannot modify any data.</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-2xl font-bold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-building text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Departments</p>
                                <p class="text-2xl font-bold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-file-alt text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">PPMP Submissions</p>
                                <p class="text-2xl font-bold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Budget Allocated</p>
                                <p class="text-2xl font-bold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent User Activities</h3>
                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">New user registered</p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-file-upload text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">PPMP submitted</p>
                                    <p class="text-xs text-gray-500">4 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-yellow-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Budget allocation updated</p>
                                    <p class="text-xs text-gray-500">6 hours ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Overview</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">System Status</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Online</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Last Backup</span>
                                <span class="text-sm text-gray-900">Today, 3:00 AM</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Database Size</span>
                                <span class="text-sm text-gray-900">2.4 MB</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Active Sessions</span>
                                <span class="text-sm text-gray-900">3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Logout</h3>
                    <button onclick="closeLogoutModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="px-6 py-4">
                    <p class="text-gray-600 mb-6">Are you sure you want to logout? You will need to login again to access the dashboard.</p>
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button onclick="performLogout()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logout functionality
        function confirmLogout() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function performLogout() {
            window.location.href = '../auth/logout.php';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target === modal) {
                closeLogoutModal();
            }
        }
    </script>
</body>
</html>
