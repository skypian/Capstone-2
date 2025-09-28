<?php
/**
 * User Class
 * BudgetTrack System - EVSU Ormoc Campus
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password_hash;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $employee_id;
    public $department_id;
    public $role_id;
    public $is_active;
    public $last_login;
    public $created_by;

    public function __construct() {
        $this->conn = getDB();
    }

    /**
     * Authenticate user login
     */
    public function authenticate($email, $password) {
        $query = "SELECT u.*, r.role_name, d.dept_name 
                  FROM " . $this->table_name . " u
                  LEFT JOIN roles r ON u.role_id = r.id
                  LEFT JOIN departments d ON u.department_id = d.id
                  WHERE u.email = :email AND u.is_active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $row['password_hash'])) {
                // Update last login
                $this->updateLastLogin($row['id']);
                
                return $row;
            }
        }
        return false;
    }

    /**
     * Update last login timestamp
     */
    private function updateLastLogin($user_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET last_login = CURRENT_TIMESTAMP 
                  WHERE id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    /**
     * Create new user
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (email, password_hash, first_name, last_name, middle_name, 
                   employee_id, department_id, role_id, created_by) 
                  VALUES 
                  (:email, :password_hash, :first_name, :last_name, :middle_name, 
                   :employee_id, :department_id, :role_id, :created_by)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $this->password_hash = password_hash($this->password_hash, PASSWORD_DEFAULT);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password_hash', $this->password_hash);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':middle_name', $this->middle_name);
        $stmt->bindParam(':employee_id', $this->employee_id);
        $stmt->bindParam(':department_id', $this->department_id);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->bindParam(':created_by', $this->created_by);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Get all users with role and department info
     */
    public function getAllUsers() {
        $query = "SELECT u.*, r.role_name, d.dept_name, 
                         CONCAT(creator.first_name, ' ', creator.last_name) as created_by_name
                  FROM " . $this->table_name . " u
                  LEFT JOIN roles r ON u.role_id = r.id
                  LEFT JOIN departments d ON u.department_id = d.id
                  LEFT JOIN users creator ON u.created_by = creator.id
                  ORDER BY u.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $query = "SELECT u.*, r.role_name, d.dept_name 
                  FROM " . $this->table_name . " u
                  LEFT JOIN roles r ON u.role_id = r.id
                  LEFT JOIN departments d ON u.department_id = d.id
                  WHERE u.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Update user
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET email = :email, first_name = :first_name, last_name = :last_name, 
                      middle_name = :middle_name, employee_id = :employee_id, 
                      department_id = :department_id, role_id = :role_id, 
                      is_active = :is_active
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':middle_name', $this->middle_name);
        $stmt->bindParam(':employee_id', $this->employee_id);
        $stmt->bindParam(':department_id', $this->department_id);
        $stmt->bindParam(':role_id', $this->role_id);
        $stmt->bindParam(':is_active', $this->is_active);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Delete user (soft delete)
     */
    public function delete() {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_active = 0 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        
        if ($exclude_id) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Check if employee ID exists
     */
    public function employeeIdExists($employee_id, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE employee_id = :employee_id";
        
        if ($exclude_id) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':employee_id', $employee_id);
        
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions($user_id) {
        $query = "SELECT p.permission_name, p.module
                  FROM permissions p
                  INNER JOIN role_permissions rp ON p.id = rp.permission_id
                  INNER JOIN users u ON rp.role_id = u.role_id
                  WHERE u.id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($user_id, $permission_name) {
        $query = "SELECT COUNT(*) as count
                  FROM permissions p
                  INNER JOIN role_permissions rp ON p.id = rp.permission_id
                  INNER JOIN users u ON rp.role_id = u.role_id
                  WHERE u.id = :user_id AND p.permission_name = :permission_name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':permission_name', $permission_name);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Change user password
     */
    public function changePassword($user_id, $current_password, $new_password) {
        // First verify current password
        $query = "SELECT password_hash FROM " . $this->table_name . " WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($current_password, $row['password_hash'])) {
                // Current password is correct, update to new password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                
                $update_query = "UPDATE " . $this->table_name . " 
                                SET password_hash = :new_password_hash 
                                WHERE id = :user_id";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(':new_password_hash', $new_password_hash);
                $update_stmt->bindParam(':user_id', $user_id);
                
                if ($update_stmt->execute()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Reset user password (for admin use)
     */
    public function resetPassword($user_id, $new_password) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $query = "UPDATE " . $this->table_name . " 
                  SET password_hash = :new_password_hash 
                  WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':new_password_hash', $new_password_hash);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
