<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_role'])) {
    header('Location: ../login.php');
    exit;
}

$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetTrack - Department Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: '#800000',
                        'maroon-dark': '#5a0000',
                        'maroon-light': '#a00000',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-inter">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg border-r border-gray-200 relative">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-maroon">BudgetTrack</h2>
                <p class="text-sm text-gray-600">Department Portal</p>
            </div>
            
            <nav class="mt-6">
                <a href="dept_dashboard.php" class="flex items-center px-6 py-3 text-maroon bg-red-50 border-r-4 border-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard (Budget Overview)
                </a>
                <a href="lib_submit.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Submit LIB
                </a>
                <a href="ppmp_submit.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Submit PPMP
                </a>
                <a href="track_requests.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Track Requests
                </a>
                <a href="reports.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                </a>
                <a href="notifications.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5a15 15 0 01-1.44-2.1A5.5 5.5 0 014.5 19.5zM19.5 4.5a15 15 0 00-1.44 2.1A5.5 5.5 0 0119.5 4.5z"></path>
                    </svg>
                    Notifications
                </a>
                <a href="announcements.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                    Announcements
                </a>
                <?php include __DIR__ . '/../components/settings_dropdown.php'; ?>
                <a href="account_settings.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Account Settings
                </a>
            </nav>
            
            <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-200">
                <button onclick="confirmLogout()" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-maroon">BudgetTrack - Head Department Dashboard</h1>
                        <p class="text-gray-600">Welcome, <?php echo htmlspecialchars($username); ?></p>
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
                        
                        <div class="flex items-center space-x-3 bg-gray-50 px-4 py-2 rounded-lg">
                            <div class="w-10 h-10 bg-maroon rounded-full flex items-center justify-center text-white font-semibold">
                                <?php echo strtoupper(substr($username, 0, 1)); ?>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($username); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($userEmail); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="flex-1 p-6">
                <!-- Budget Overview Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-maroon mb-6">Budget Overview</h2>
                    
                    <!-- Budget Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-600 mb-2">Allocated</p>
                            <p class="text-3xl font-bold text-green-600">₱1,200,000</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-600 mb-2">Used</p>
                            <p class="text-3xl font-bold text-red-600">₱850,000</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-600 mb-2">Remaining</p>
                            <p class="text-3xl font-bold text-yellow-600">₱350,000</p>
                        </div>
                    </div>
                    
                    <!-- Budget Visualization -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Budget Breakdown</h3>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700">Allocated</span>
                                <span class="font-medium text-gray-700">₱1,200,000</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-green-500 h-4 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700">Used</span>
                                <span class="font-medium text-gray-700">₱850,000 (70.8%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-red-500 h-4 rounded-full" style="width: 70.8%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700">Remaining</span>
                                <span class="font-medium text-gray-700">₱350,000 (29.2%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-yellow-500 h-4 rounded-full" style="width: 29.2%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-maroon mb-6">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <button class="flex items-center justify-center px-6 py-4 bg-maroon text-white rounded-lg hover:bg-maroon-dark transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Submit PPMP
                        </button>
                        <button class="flex items-center justify-center px-6 py-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Submit LIB
                        </button>
                        <button class="flex items-center justify-center px-6 py-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            Track Requests
                        </button>
                        <button class="flex items-center justify-center px-6 py-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Generate Budget Report
                        </button>
                    </div>
                </div>
                
                <!-- Notifications and Recent Requests -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Notifications/Alerts Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-maroon mb-4">Notifications / Alerts</h2>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border-l-4 border-green-400">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-green-800">Q3 Funds have been released</p>
                                    <p class="text-sm text-green-600">August 15, 2025</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg border-l-4 border-red-400">
                                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-red-800">Purchase Request #124 is awaiting approval</p>
                                    <p class="text-sm text-red-600">Action required</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-yellow-800">Reminder: Submit PPMP for 2026 before Sept. 10</p>
                                    <p class="text-sm text-yellow-600">Deadline approaching</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Requests Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-maroon mb-4">Recent Requests</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">#124 Laboratory Supplies</p>
                                    <p class="text-sm text-gray-600">₱50,000</p>
                                </div>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">Pending</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">#123 Department Seminar</p>
                                    <p class="text-sm text-gray-600">₱30,000</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">Approved</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">#122 Office Equipment</p>
                                    <p class="text-sm text-gray-600">₱25,000</p>
                                </div>
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">Rejected</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Announcements Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-maroon mb-4">Announcements</h2>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-blue-800">New CHED guidelines uploaded</p>
                                <p class="text-sm text-blue-600">Download here for latest updates</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-blue-800">Procurement deadline extended to Sept. 15</p>
                                <p class="text-sm text-blue-600">Additional time for submissions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Logout</h3>
                    <button onclick="closeLogoutModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
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
        
        // Add click handlers for quick actions
        document.addEventListener('DOMContentLoaded', function() {
            const quickActions = document.querySelectorAll('button');
            quickActions.forEach(button => {
                if (button.textContent.includes('Submit') || button.textContent.includes('Track') || button.textContent.includes('Generate')) {
                    button.addEventListener('click', function() {
                        const action = this.textContent.trim();
                        alert(`Action clicked: ${action}`);
                    });
                }
            });
        });
    </script>
</body>
</html>