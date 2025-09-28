<?php
session_start();

// Check if user is logged in and has budget access (system administrator)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'budget') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/BudgetAllocation.php';
require_once __DIR__ . '/../classes/Notification.php';
require_once __DIR__ . '/../classes/ActivityLog.php';

$user = new User();
$budgetAllocation = new BudgetAllocation();
$notification = new Notification();
$activityLog = new ActivityLog();

// Get real data
$budgetSummary = $budgetAllocation->getBudgetSummary();
$recentActivities = $activityLog->getRecentActivities(5);
$notifications = $notification->getUserNotifications($_SESSION['user_id'], 5);
$unreadCount = $notification->getUnreadCount($_SESSION['user_id']);

// Calculate totals
$totalAllocated = array_sum(array_column($budgetSummary, 'total_allocated'));
$totalUtilized = array_sum(array_column($budgetSummary, 'total_utilized'));
$totalRemaining = array_sum(array_column($budgetSummary, 'total_remaining'));

// Check if user has permission to view admin dashboard
if (!$user->hasPermission($_SESSION['user_id'], 'view_admin_dashboard')) {
    header('Location: ../pages/dashboard.php');
    exit;
}

$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Administrator';
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$userRole = $_SESSION['user_role'];

// User is Budget/Finance Office (system administrator)
$isBudgetOffice = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetTrack - Budget/Finance Office Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <p class="text-sm text-gray-600">Administration Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="admin_dashboard.php" class="flex items-center px-6 py-3 text-maroon bg-red-50 border-r-4 border-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="allocations.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Allocations
                </a>
                <a href="ppmp_lib.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PPMP & LIB
                </a>
                <a href="reports_admin.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                </a>
                <a href="user_management.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    User Management
                </a>
                <a href="role_management.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Role Management
                </a>
                <a href="departments.php" class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-maroon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Departments
                </a>
                <?php include __DIR__ . '/../components/settings_dropdown.php'; ?>
                <button onclick="confirmLogout()" class="flex w-full items-center px-6 py-3 text-white bg-red-600 hover:bg-red-700 mt-4 rounded-none">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            <?php if ($isBudgetOffice): ?>
                                Budget/Accounting Control Dashboard
                            <?php else: ?>
                                Admin Dashboard
                            <?php endif; ?>
                        </h1>
                        <p class="text-gray-600">
                            Welcome back, <?php echo htmlspecialchars($username); ?>! 
                            <?php if ($isBudgetOffice): ?>
                                You have full control over the budget system and all user permissions.
                            <?php else: ?>
                                Monitor and manage the budget system.
                            <?php endif; ?>
                        </p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $isBudgetOffice ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>">
                                <?php echo $isBudgetOffice ? 'Budget/Accounting Office - Full Control' : ucfirst($userRole); ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <?php include __DIR__ . '/../components/notification_bell.php'; ?>
                        
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
                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Allocations</p>
                                <p class="text-3xl font-bold text-maroon">₱<?php echo number_format($totalAllocated / 1000000, 1); ?>M</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Utilization</p>
                                <p class="text-3xl font-bold text-green-600">₱<?php echo number_format($totalUtilized / 1000000, 1); ?>M</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                                <p class="text-3xl font-bold text-yellow-600"><?php echo $unreadCount; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Departments</p>
                                <p class="text-3xl font-bold text-blue-600"><?php echo count($budgetSummary); ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Allocations by Department</h3>
                        <canvas id="deptChart" class="!h-64"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Budget Utilization</h3>
                        <canvas id="utilChart" class="!h-64"></canvas>
                    </div>
                </div>
                
                <!-- Recent Activity and Notifications -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-4">
                            <?php if (empty($recentActivities)): ?>
                                <p class="text-gray-500 text-center py-4">No recent activities</p>
                            <?php else: ?>
                                <?php foreach ($recentActivities as $activity): ?>
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($activity['description'] ?: $activity['action']); ?></p>
                                            <p class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']); ?> • 
                                                <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Notifications</h3>
                        <div class="space-y-4">
                            <?php if (empty($notifications)): ?>
                                <p class="text-gray-500 text-center py-4">No notifications</p>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <?php
                                    $bgColor = '';
                                    $borderColor = '';
                                    $textColor = '';
                                    $iconColor = '';
                                    
                                    switch ($notif['type']) {
                                        case 'success':
                                            $bgColor = 'bg-green-50';
                                            $borderColor = 'border-green-400';
                                            $textColor = 'text-green-800';
                                            $iconColor = 'text-green-600';
                                            break;
                                        case 'warning':
                                            $bgColor = 'bg-yellow-50';
                                            $borderColor = 'border-yellow-400';
                                            $textColor = 'text-yellow-800';
                                            $iconColor = 'text-yellow-600';
                                            break;
                                        case 'error':
                                            $bgColor = 'bg-red-50';
                                            $borderColor = 'border-red-400';
                                            $textColor = 'text-red-800';
                                            $iconColor = 'text-red-600';
                                            break;
                                        default:
                                            $bgColor = 'bg-blue-50';
                                            $borderColor = 'border-blue-400';
                                            $textColor = 'text-blue-800';
                                            $iconColor = 'text-blue-600';
                                    }
                                    ?>
                                    <div class="flex items-start space-x-3 p-3 <?php echo $bgColor; ?> rounded-lg border-l-4 <?php echo $borderColor; ?>">
                                        <svg class="w-5 h-5 <?php echo $iconColor; ?> mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium <?php echo $textColor; ?>"><?php echo htmlspecialchars($notif['title']); ?></p>
                                            <p class="text-sm <?php echo $iconColor; ?>"><?php echo htmlspecialchars($notif['message']); ?></p>
                                            <p class="text-xs text-gray-500 mt-1"><?php echo date('M j, Y g:i A', strtotime($notif['created_at'])); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Budget/Accounting Office Controls -->
                <?php if ($isBudgetOffice): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl shadow-sm p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-red-800">Budget/Accounting Office Controls</h3>
                    </div>
                    <p class="text-red-700 mb-4">As Budget/Accounting Office, you have complete control over the system, including admin permissions.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="user_management.php" class="flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Manage All Users
                        </a>
                        <a href="role_management.php" class="flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Control Admin Permissions
                        </a>
                        <a href="admin_control.php" class="flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            System Override
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <button class="flex items-center justify-center px-4 py-3 bg-maroon text-white rounded-lg hover:bg-maroon-dark transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Allocation
                        </button>
                        <button class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Generate Report
                        </button>
                        <a href="user_management.php" class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                        <button class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </button>
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
        
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Department Chart
            const deptCtx = document.getElementById('deptChart').getContext('2d');
            const deptChart = new Chart(deptCtx, {
                type: 'bar',
                data: {
                    labels: ['CICS', 'COE', 'CET', 'CAS', 'CABA'],
                    datasets: [{
                        label: 'Allocated (M)',
                        data: [3.2, 2.4, 1.8, 2.0, 1.1],
                        backgroundColor: 'rgba(128,0,0,0.7)',
                        borderColor: '#800000',
                        borderWidth: 2,
                        maxBarThickness: 28
                    }, {
                        label: 'Utilized (M)',
                        data: [2.1, 1.7, 1.2, 1.3, 0.7],
                        backgroundColor: 'rgba(16,185,129,0.7)',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        maxBarThickness: 28
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + 'M';
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y} M`
                            }
                        }
                    }
                }
            });
            
            // Utilization Chart
            const utilCtx = document.getElementById('utilChart').getContext('2d');
            const utilChart = new Chart(utilCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Instruction', 'Research', 'Extension', 'Admin'],
                    datasets: [{
                        data: [45, 20, 15, 20],
                        backgroundColor: ['#800000', '#10b981', '#f59e0b', '#3b82f6'],
                        hoverOffset: 6,
                        borderWidth: 1,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.label}: ${ctx.parsed}%`
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>