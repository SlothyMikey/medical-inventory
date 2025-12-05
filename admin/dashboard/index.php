<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

require_once '../../includes/db.php';

// Get counts for dashboard stats
try {
    $pdo = getDBConnection();
    
    $nurseCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'nurse'")->fetchColumn();
    $workerCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'worker'")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
} catch (Exception $e) {
    $nurseCount = 0;
    $workerCount = 0;
    $totalUsers = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medical Inventory</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../admin.css">
    <link rel="stylesheet" href="../components/navbar.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrapper">
        <?php include '../components/navbar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>! Here's your overview.</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon nurses">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo $nurseCount; ?></span>
                        <span class="stat-label">Total Nurses</span>
                    </div>
                    <a href="../nurses/" class="stat-link">
                        <span>Manage</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="stat-card">
                    <div class="stat-icon workers">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo $workerCount; ?></span>
                        <span class="stat-label">Total Workers</span>
                    </div>
                    <a href="../workers/" class="stat-link">
                        <span>Manage</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo $totalUsers; ?></span>
                        <span class="stat-label">All Users</span>
                    </div>
                    <div class="stat-badge">Active</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            <div class="quick-actions">
                <a href="../nurses/?openModal=true" class="action-card">
                    <div class="action-icon nurses">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-content">
                        <h3>Add New Nurse</h3>
                        <p>Create a nurse account</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="../workers/?openModal=true" class="action-card">
                    <div class="action-icon workers">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-content">
                        <h3>Add New Worker</h3>
                        <p>Create a worker account</p>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <!-- Recent Activity Placeholder -->
            <div class="section-header">
                <h2>System Information</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-server"></i>
                            <div>
                                <span class="info-label">Server Status</span>
                                <span class="info-value status-online">Online</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-database"></i>
                            <div>
                                <span class="info-label">Database</span>
                                <span class="info-value">medical_inventory</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <span class="info-label">Last Login</span>
                                <span class="info-value"><?php echo date('M d, Y H:i'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
