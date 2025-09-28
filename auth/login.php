<?php
session_start();

require_once __DIR__ . '/../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Please enter both email and password.';
        header('Location: ../../Capstone/login.php');
        exit;
    }

    $user = new User();
    $user_data = $user->authenticate($email, $password);

    if ($user_data) {
        // Set session variables
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['user_role'] = $user_data['role_name'];
        $_SESSION['user_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
        $_SESSION['user_email'] = $user_data['email'];
        $_SESSION['department_id'] = $user_data['department_id'];
        $_SESSION['department_name'] = $user_data['dept_name'];
        $_SESSION['login_success'] = true;

        // Redirect based on role - Corrected hierarchy
        switch ($user_data['role_name']) {
            case 'budget':
                header('Location: ../../capstone/pages/admin_dashboard.php');
                break;
            case 'school_admin':
                header('Location: ../../capstone/pages/school_admin_dashboard.php');
                break;
            case 'offices':
                header('Location: ../../capstone/pages/dept_dashboard.php');
                break;
            default:
                header('Location: ../../capstone/pages/dept_dashboard.php');
                break;
        }
        exit;
    } else {
        $_SESSION['login_error'] = 'Invalid email or password. Please try again.';
        header('Location: ../../Capstone/login.php');
        exit;
    }
}

header('Location: ../../Capstone/login.php');
exit;
?>


