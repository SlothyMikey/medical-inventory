<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - Medical Inventory</title>
    
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
                <h1 class="page-title">Patients</h1>
                <p class="page-subtitle">Manage patient records and information.</p>
            </div>

            <!-- Coming Soon Card -->
            <div class="coming-soon-card">
                <div class="coming-soon-icon">
                    <i class="fas fa-procedures"></i>
                </div>
                <h2>Coming Soon</h2>
                <p>The Patients management module is currently under development.</p>
                <p class="coming-soon-features">
                    Upcoming features include:
                </p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Patient registration and profiles</li>
                    <li><i class="fas fa-check"></i> Medical history tracking</li>
                    <li><i class="fas fa-check"></i> Appointment scheduling</li>
                    <li><i class="fas fa-check"></i> Treatment records</li>
                </ul>
                <a href="../dashboard/" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </main>
    </div>
</body>
</html>
