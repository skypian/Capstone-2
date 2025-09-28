<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../classes/User.php';
$user = new User();

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
    <title>Profile - BudgetTrack</title>
    <link rel="stylesheet" href="../public/css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-red-800 text-white p-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold">BudgetTrack</h1>
                    <span class="text-red-200">|</span>
                    <span>Profile</span>
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

    <div class="max-w-4xl mx-auto p-6">
        <!-- Header -->
        <div class="flex items-center space-x-4 mb-6">
            <button onclick="goBack()" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </button>
            <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
                <p class="text-sm text-gray-600">View and manage your profile details</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50" id="first_name_display">
                                <?php echo htmlspecialchars($user_info['first_name']); ?>
                            </div>
                            <button onclick="editField('first_name')" class="p-2 text-gray-400 hover:text-gray-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <input type="text" id="first_name_input" class="hidden w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo htmlspecialchars($user_info['first_name']); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50" id="last_name_display">
                                <?php echo htmlspecialchars($user_info['last_name']); ?>
                            </div>
                            <button onclick="editField('last_name')" class="p-2 text-gray-400 hover:text-gray-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <input type="text" id="last_name_input" class="hidden w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo htmlspecialchars($user_info['last_name']); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50" id="middle_name_display">
                                <?php echo htmlspecialchars($user_info['middle_name'] ?: 'N/A'); ?>
                            </div>
                            <button onclick="editField('middle_name')" class="p-2 text-gray-400 hover:text-gray-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <input type="text" id="middle_name_input" class="hidden w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo htmlspecialchars($user_info['middle_name']); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50" id="employee_id_display">
                                <?php echo htmlspecialchars($user_info['employee_id']); ?>
                            </div>
                            <button onclick="editField('employee_id')" class="p-2 text-gray-400 hover:text-gray-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <input type="text" id="employee_id_input" class="hidden w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo htmlspecialchars($user_info['employee_id']); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50" id="email_display">
                                <?php echo htmlspecialchars($user_info['email']); ?>
                            </div>
                            <button onclick="editField('email')" class="p-2 text-gray-400 hover:text-gray-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <input type="email" id="email_input" class="hidden w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="<?php echo htmlspecialchars($user_info['email']); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                            <?php 
                            $role_names = [
                                'budget' => 'Budget/Finance Office',
                                'school_admin' => 'School Administrator',
                                'offices' => 'Department Office'
                            ];
                            echo $role_names[$user_info['role_name']] ?? ucfirst($user_info['role_name']);
                            ?>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                            <?php echo htmlspecialchars($user_info['dept_name'] ?? 'N/A'); ?>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                        <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $user_info['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $user_info['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Account Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created Date</label>
                            <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                <?php echo date('F j, Y g:i A', strtotime($user_info['created_at'])); ?>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Login</label>
                            <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                <?php echo $user_info['last_login'] ? date('F j, Y g:i A', strtotime($user_info['last_login'])) : 'Never'; ?>
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

        // Edit field functionality
        function editField(fieldName) {
            const display = document.getElementById(fieldName + '_display');
            const input = document.getElementById(fieldName + '_input');
            const editBtn = display.parentElement.querySelector('button');
            
            // Hide display, show input
            display.classList.add('hidden');
            input.classList.remove('hidden');
            input.focus();
            
            // Change edit button to save/cancel
            editBtn.innerHTML = '<i class="fas fa-check"></i>';
            editBtn.onclick = () => saveField(fieldName);
            editBtn.title = 'Save';
            
            // Add cancel button
            const cancelBtn = document.createElement('button');
            cancelBtn.innerHTML = '<i class="fas fa-times"></i>';
            cancelBtn.className = 'p-2 text-gray-400 hover:text-gray-600 ml-1';
            cancelBtn.title = 'Cancel';
            cancelBtn.onclick = () => cancelEdit(fieldName);
            editBtn.parentElement.appendChild(cancelBtn);
        }

        function saveField(fieldName) {
            const input = document.getElementById(fieldName + '_input');
            const display = document.getElementById(fieldName + '_display');
            const newValue = input.value.trim();
            
            if (newValue === '') {
                alert('This field cannot be empty');
                return;
            }
            
            // Update display
            display.textContent = newValue === '' ? 'N/A' : newValue;
            
            // Hide input, show display
            input.classList.add('hidden');
            display.classList.remove('hidden');
            
            // Reset buttons
            const editBtn = display.parentElement.querySelector('button');
            editBtn.innerHTML = '<i class="fas fa-edit"></i>';
            editBtn.onclick = () => editField(fieldName);
            editBtn.title = 'Edit';
            
            // Remove cancel button
            const cancelBtn = editBtn.parentElement.querySelector('button:last-child');
            if (cancelBtn) cancelBtn.remove();
            
            // Here you would typically send an AJAX request to update the database
            console.log('Saving ' + fieldName + ':', newValue);
            // TODO: Implement AJAX save functionality
        }

        function cancelEdit(fieldName) {
            const input = document.getElementById(fieldName + '_input');
            const display = document.getElementById(fieldName + '_display');
            const originalValue = display.textContent;
            
            // Reset input value
            input.value = originalValue === 'N/A' ? '' : originalValue;
            
            // Hide input, show display
            input.classList.add('hidden');
            display.classList.remove('hidden');
            
            // Reset buttons
            const editBtn = display.parentElement.querySelector('button');
            editBtn.innerHTML = '<i class="fas fa-edit"></i>';
            editBtn.onclick = () => editField(fieldName);
            editBtn.title = 'Edit';
            
            // Remove cancel button
            const cancelBtn = editBtn.parentElement.querySelector('button:last-child');
            if (cancelBtn) cancelBtn.remove();
        }

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
