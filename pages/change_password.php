<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../classes/User.php';

$user = new User();
$message = '';
$error = '';

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate inputs
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'All fields are required.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New password and confirm password do not match.';
        } elseif (strlen($new_password) < 6) {
            $error = 'New password must be at least 6 characters long.';
        } else {
            // Attempt to change password
            if ($user->changePassword($_SESSION['user_id'], $current_password, $new_password)) {
                $message = 'Password changed successfully.';
            } else {
                $error = 'Current password is incorrect.';
            }
        }
    }
}

// Get user information
$user_info = $user->getUserById($_SESSION['user_id']);
$user_role = $_SESSION['user_role'];
$is_admin_or_budget = in_array($user_role, ['budget', 'school_admin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - BudgetTrack</title>
    <link rel="stylesheet" href="../public/css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-red-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold">BudgetTrack</h1>
                    <span class="text-red-200">|</span>
                    <span>Change Password</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <?php if ($is_admin_or_budget): ?>
                        <a href="admin_dashboard.php" class="bg-red-700 hover:bg-red-600 px-3 py-2 rounded">Dashboard</a>
                    <?php else: ?>
                        <a href="dept_dashboard.php" class="bg-red-700 hover:bg-red-600 px-3 py-2 rounded">Dashboard</a>
                    <?php endif; ?>
                    <button onclick="confirmLogout()" class="bg-red-700 hover:bg-red-600 px-3 py-2 rounded">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Change Password Form -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-4 mb-2">
                    <button onclick="goBack()" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </button>
                    <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
                </div>
                <p class="text-sm text-gray-600">Update your account password for security</p>
            </div>
            
            <form method="POST" action="" class="p-6">
                <input type="hidden" name="action" value="change_password">
                
                <div class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <button type="button" 
                                    onclick="togglePassword('current_password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   required
                                   minlength="6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <button type="button" 
                                    onclick="togglePassword('new_password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Password must be at least 6 characters long</p>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required
                                   minlength="6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <button type="button" 
                                    onclick="togglePassword('confirm_password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="history.back()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <i class="fas fa-key mr-2"></i>
                        Change Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Tips -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">
                <i class="fas fa-shield-alt mr-2"></i>
                Password Security Tips
            </h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Use a combination of uppercase and lowercase letters</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Include numbers and special characters</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Make it at least 8 characters long</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Avoid using personal information</li>
                <li><i class="fas fa-check text-blue-600 mr-2"></i>Don't reuse passwords from other accounts</li>
            </ul>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirm password do not match.');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('New password must be at least 6 characters long.');
                return false;
            }
        });

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
</body>
</html>
