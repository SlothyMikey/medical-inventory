<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

require_once '../../includes/db.php';

// Handle form submission for creating new worker
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $pdo = getDBConnection();
    
    if ($_POST['action'] === 'create') {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $fullName = sanitize($_POST['full_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        
        // Validation
        if (empty($username) || empty($password)) {
            $message = 'Username and password are required.';
            $messageType = 'error';
        } elseif (strlen($password) < 6) {
            $message = 'Password must be at least 6 characters.';
            $messageType = 'error';
        } else {
            // Check if username exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $message = 'Username already exists. Please choose a different one.';
                $messageType = 'error';
            } else {
                // Create the user
                $hashedPassword = hashPassword($password);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email, phone) VALUES (?, ?, 'worker', ?, ?, ?)");
                
                try {
                    $stmt->execute([$username, $hashedPassword, $fullName, $email, $phone]);
                    $message = 'Worker account created successfully!';
                    $messageType = 'success';
                } catch (PDOException $e) {
                    $message = 'Error creating account. Please try again.';
                    $messageType = 'error';
                }
            }
        }
    } elseif ($_POST['action'] === 'delete') {
        $userId = intval($_POST['user_id'] ?? 0);
        
        if ($userId > 0) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'worker'");
            $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                $message = 'Worker account deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Could not delete the account.';
                $messageType = 'error';
            }
        }
    }
}

// Fetch all workers
try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, username, full_name, email, phone, created_at FROM users WHERE role = 'worker' ORDER BY created_at DESC");
    $workers = $stmt->fetchAll();
} catch (Exception $e) {
    $workers = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Workers - Medical Inventory</title>
    
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
                <div class="header-content">
                    <div>
                        <h1 class="page-title">Manage Workers</h1>
                        <p class="page-subtitle">Create and manage worker accounts for the system.</p>
                    </div>
                    <button class="btn btn-primary" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Add New Worker
                    </button>
                </div>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <!-- Workers Table -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-headset"></i> Worker Accounts</h3>
                    <span class="badge badge-warning"><?php echo count($workers); ?> Total</span>
                </div>
                <div class="card-body table-responsive">
                    <?php if (empty($workers)): ?>
                    <div class="empty-state">
                        <i class="fas fa-headset"></i>
                        <h3>No workers found</h3>
                        <p>Click "Add New Worker" to create the first worker account.</p>
                    </div>
                    <?php else: ?>
                    <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workers as $worker): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-sm">
                                            <i class="fas fa-headset"></i>
                                        </div>
                                        <span><?php echo htmlspecialchars($worker['username']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($worker['full_name'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($worker['email'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($worker['phone'] ?? '-'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($worker['created_at'])); ?></td>
                                <td>
                                    <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this worker account?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?php echo $worker['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Create Worker Modal -->
    <div class="modal-overlay" id="createModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-user-plus"></i> Create Worker Account</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="username">Username <span class="required">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span class="required">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimum 6 characters" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="+63 XXX XXX XXXX">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('createModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('createModal').classList.remove('active');
        }
        
        // Close modal on outside click
        document.getElementById('createModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
