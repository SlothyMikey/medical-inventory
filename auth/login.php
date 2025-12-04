<?php
session_start();

// If user is already logged in, redirect to their dashboard
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: ../admin/dashboard/index.php");
            exit();
        case 'nurse':
            header("Location: ../nurse/dashboard/index.php");
            exit();
        case 'worker':
            header("Location: ../workers/dashboard/index.php");
            exit();
    }
}

require_once '../database/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($role) || empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Query user by username and role
        $sql = "SELECT * FROM users WHERE username = '$username' AND role = '$role'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password correct, start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                switch ($role) {
                    case 'admin':
                        header("Location: ../admin/dashboard/index.php");
                        break;
                    case 'nurse':
                        header("Location: ../nurse/dashboard/index.php");
                        break;
                    case 'worker':
                        header("Location: ../workers/dashboard/index.php");
                        break;
                    default:
                        $error = "Invalid role.";
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found with that role.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Health Center Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="login.css?v=1.1">
</head>
<body>
    <div class="grid-background"></div>
    <div class="blob-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <div class="logo-placeholder">
                <i class="fa-solid fa-heart-pulse fa-2x"></i>
            </div>
            <h1>Welcome Back</h1>
            <p>Barangay Health Center Inventory System</p>
        </div>
        
        <form class="login-form" id="loginForm" method="POST" action="">
            <?php if ($error): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Select Role</label>
                <div class="role-selection">
                    <label class="role-card">
                        <input type="radio" name="role" value="nurse" required>
                        <div class="card-content">
                            <i class="fa-solid fa-user-nurse"></i>
                            <span>Nurse</span>
                        </div>
                        <div class="check-indicator"></div>
                    </label>
                    <label class="role-card">
                        <input type="radio" name="role" value="worker">
                        <div class="card-content">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span>Workers</span>
                        </div>
                        <div class="check-indicator"></div>
                    </label>
                    <label class="role-card">
                        <input type="radio" name="role" value="admin">
                        <div class="card-content">
                            <i class="fa-solid fa-fingerprint"></i>
                            <span>Admin</span>
                        </div>
                        <div class="check-indicator"></div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    <i class="fa-regular fa-user input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-actions">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-login">
                Sign In
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>
    </div>

    <script src="login.js?v=1.1"></script>
</body>
</html>
