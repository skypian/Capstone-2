<?php
session_start();

// Check if user is logged in and has permission
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Role.php';
require_once __DIR__ . '/../classes/Department.php';

$user = new User();
$role = new Role();
$department = new Department();

// Check if user has permission to manage users
if (!$user->hasPermission($_SESSION['user_id'], 'create_users')) {
    header('Location: ../pages/dashboard.php');
    exit;
}

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_user':
                $new_user = new User();
                $new_user->email = trim($_POST['email']);
                $new_user->password_hash = $_POST['password'];
                $new_user->first_name = trim($_POST['first_name']);
                $new_user->last_name = trim($_POST['last_name']);
                $new_user->middle_name = trim($_POST['middle_name']);
                $new_user->employee_id = trim($_POST['employee_id']);
                $new_user->department_id = $_POST['department_id'];
                $new_user->role_id = $_POST['role_id'];
                $new_user->created_by = $_SESSION['user_id'];

                // Validate required fields
                if (empty($new_user->email) || empty($new_user->password_hash) || 
                    empty($new_user->first_name) || empty($new_user->last_name) || 
                    empty($new_user->employee_id) || empty($new_user->department_id) || 
                    empty($new_user->role_id)) {
                    $error = 'All fields are required.';
                } elseif ($new_user->emailExists($new_user->email)) {
                    $error = 'Email already exists.';
                } elseif ($new_user->employeeIdExists($new_user->employee_id)) {
                    $error = 'Employee ID already exists.';
                } else {
                    if ($new_user->create()) {
                        $message = 'User created successfully.';
                    } else {
                        $error = 'Failed to create user.';
                    }
                }
                break;

            case 'update_user':
                $update_user = new User();
                $update_user->id = $_POST['user_id'];
                $update_user->email = trim($_POST['email']);
                $update_user->first_name = trim($_POST['first_name']);
                $update_user->last_name = trim($_POST['last_name']);
                $update_user->middle_name = trim($_POST['middle_name']);
                $update_user->employee_id = trim($_POST['employee_id']);
                $update_user->department_id = $_POST['department_id'];
                $update_user->role_id = $_POST['role_id'];
                $update_user->is_active = isset($_POST['is_active']) ? 1 : 0;

                if ($update_user->emailExists($update_user->email, $update_user->id)) {
                    $error = 'Email already exists.';
                } elseif ($update_user->employeeIdExists($update_user->employee_id, $update_user->id)) {
                    $error = 'Employee ID already exists.';
                } else {
                    if ($update_user->update()) {
                        $message = 'User updated successfully.';
                    } else {
                        $error = 'Failed to update user.';
                    }
                }
                break;

            case 'delete_user':
                $delete_user = new User();
                $delete_user->id = $_POST['user_id'];
                if ($delete_user->delete()) {
                    $message = 'User deactivated successfully.';
                } else {
                    $error = 'Failed to deactivate user.';
                }
                break;

            case 'reset_password':
                $reset_user = new User();
                $reset_user->id = $_POST['user_id'];
                $new_password = $_POST['new_password'];
                
                if (empty($new_password) || strlen($new_password) < 6) {
                    $error = 'New password must be at least 6 characters long.';
                } else {
                    if ($reset_user->resetPassword($reset_user->id, $new_password)) {
                        $message = 'Password reset successfully.';
                    } else {
                        $error = 'Failed to reset password.';
                    }
                }
                break;
        }
    }
}

// Get all users, roles, and departments
$users = $user->getAllUsers();
$roles = $role->getAllRoles();
$departments = $department->getAllDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - BudgetTrack</title>
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
                    <span>User Management</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <button onclick="confirmLogout()" class="bg-red-700 hover:bg-red-600 px-3 py-2 rounded">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
            </div>
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Create New User
            </button>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($u['email']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($u['employee_id']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo htmlspecialchars($u['dept_name'] ?? 'N/A'); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <?php if ($u['role_name'] === 'admin'): ?>
                                    <i class="fas fa-crown text-yellow-500 mr-1"></i>
                                <?php elseif ($u['role_name'] === 'budget'): ?>
                                    <i class="fas fa-shield-alt text-red-500 mr-1"></i>
                                <?php elseif ($u['role_name'] === 'offices'): ?>
                                    <i class="fas fa-building text-blue-500 mr-1"></i>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($u['role_name']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $u['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $u['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($u)); ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openResetPasswordModal(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?>')" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Reset Password">
                                <i class="fas fa-key"></i>
                            </button>
                            <button onclick="deleteUser(<?php echo $u['id']; ?>)" class="text-red-600 hover:text-red-900" title="Deactivate User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="create_user">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Create New User</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password *</label>
                            <input type="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="first_name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="last_name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" name="middle_name" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Employee ID *</label>
                            <input type="text" name="employee_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department *</label>
                            <select name="department_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['dept_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role *</label>
                            <select name="role_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['role_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email" id="edit_email" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="first_name" id="edit_first_name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="last_name" id="edit_last_name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                            <input type="text" name="middle_name" id="edit_middle_name" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Employee ID *</label>
                            <input type="text" name="employee_id" id="edit_employee_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department *</label>
                            <select name="department_id" id="edit_department_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['dept_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role *</label>
                            <select name="role_id" id="edit_role_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['role_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="reset_password">
                    <input type="hidden" name="user_id" id="reset_user_id">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Reset Password</h3>
                        <p class="mt-1 text-sm text-gray-500">Reset password for: <span id="reset_user_name" class="font-medium"></span></p>
                    </div>
                    <div class="px-6 py-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                            <input type="password" name="new_password" id="reset_new_password" required minlength="6" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <p class="mt-1 text-sm text-gray-500">Password must be at least 6 characters long</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                        <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md hover:bg-yellow-700">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" id="delete_user_id">
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900">Confirm Deactivation</h3>
                        <p class="mt-2 text-sm text-gray-500">Are you sure you want to deactivate this user? This action can be undone.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                            Deactivate User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_first_name').value = user.first_name;
            document.getElementById('edit_last_name').value = user.last_name;
            document.getElementById('edit_middle_name').value = user.middle_name || '';
            document.getElementById('edit_employee_id').value = user.employee_id;
            document.getElementById('edit_department_id').value = user.department_id || '';
            document.getElementById('edit_role_id').value = user.role_id;
            document.getElementById('edit_is_active').checked = user.is_active == 1;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openResetPasswordModal(userId, userName) {
            document.getElementById('reset_user_id').value = userId;
            document.getElementById('reset_user_name').textContent = userName;
            document.getElementById('reset_new_password').value = '';
            document.getElementById('resetPasswordModal').classList.remove('hidden');
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }

        function deleteUser(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            const resetPasswordModal = document.getElementById('resetPasswordModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === createModal) {
                closeCreateModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
            if (event.target === resetPasswordModal) {
                closeResetPasswordModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
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
