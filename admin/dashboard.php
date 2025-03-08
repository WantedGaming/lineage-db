<?php
// Start session
session_start();

// Include configuration files
require_once '../config/config.php';
require_once '../config/constants.php';

// Include database connection
require_once '../includes/core/Database.php';

// Include helper functions
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Get database instance
$db = Database::getInstance();

// Get database statistics
$stats = [];

// Get total items
$result = $db->query("SELECT COUNT(*) as count FROM weapon");
$row = $result->fetch_assoc();
$stats['weapons'] = $row['count'];

$result = $db->query("SELECT COUNT(*) as count FROM armor");
$row = $result->fetch_assoc();
$stats['armor'] = $row['count'];

$result = $db->query("SELECT COUNT(*) as count FROM etcitem");
$row = $result->fetch_assoc();
$stats['etcitems'] = $row['count'];

// Get total NPCs
$result = $db->query("SELECT COUNT(*) as count FROM npc");
$row = $result->fetch_assoc();
$stats['npcs'] = $row['count'];

// Get boss NPCs
$result = $db->query("SELECT COUNT(*) as count FROM npc WHERE is_bossmonster = 'true'");
$row = $result->fetch_assoc();
$stats['bosses'] = $row['count'];

// Get total skills
$result = $db->query("SELECT COUNT(*) as count FROM skills");
$row = $result->fetch_assoc();
$stats['skills'] = $row['count'];

// Get passive skills
$result = $db->query("SELECT COUNT(*) as count FROM skills_passive");
$row = $result->fetch_assoc();
$stats['passive_skills'] = $row['count'];

// Get total spawns
$result = $db->query("SELECT COUNT(*) as count FROM spawnlist");
$row = $result->fetch_assoc();
$stats['spawns'] = $row['count'];

// Get total maps
$result = $db->query("SELECT COUNT(*) as count FROM mapids");
$row = $result->fetch_assoc();
$stats['maps'] = $row['count'];

// Get recent access logs (placeholder - implement based on your logging system)
$recentLogs = [];
for ($i = 0; $i < 5; $i++) {
    $recentLogs[] = [
        'ip' => '192.168.1.' . rand(1, 255),
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
        'page' => '/pages/items/view.php?id=' . rand(1000, 5000),
        'datetime' => date('Y-m-d H:i:s', time() - rand(0, 86400))
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LineageII Remastered Database</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/public/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="mb-4 px-3 d-flex align-items-center">
                        <img src="<?php echo SITE_URL; ?>/public/images/logo.png" alt="Logo" height="40" class="me-2">
                        <span class="fs-4 text-white">Admin Panel</span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="items/">
                                <i class="fas fa-box me-2"></i>
                                Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="npcs/">
                                <i class="fas fa-dragon me-2"></i>
                                NPCs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="skills/">
                                <i class="fas fa-magic me-2"></i>
                                Skills
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="spawns/">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Spawns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="drops/">
                                <i class="fas fa-coins me-2"></i>
                                Drops
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="<?php echo SITE_URL; ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="fas fa-external-link-alt"></i> View Site
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-sync"></i> Refresh Stats
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome message -->
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h4>
                    <p>This is the admin panel for LineageII Remastered Database. From here, you can manage all aspects of the database.</p>
                    <hr>
                    <p class="mb-0">Last login: <?php echo date('Y-m-d H:i:s'); ?></p>
                </div>
                
                <!-- Stats cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Items</h5>
                                        <h2 class="mb-0"><?php echo formatNumber($stats['weapons'] + $stats['armor'] + $stats['etcitems']); ?></h2>
                                    </div>
                                    <div>
                                        <i class="fas fa-box fa-3x"></i>
                                    </div>
                                </div>
                                <small>
                                    Weapons: <?php echo formatNumber($stats['weapons']); ?> | 
                                    Armor: <?php echo formatNumber($stats['armor']); ?> | 
                                    ETC: <?php echo formatNumber($stats['etcitems']); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">NPCs</h5>
                                        <h2 class="mb-0"><?php echo formatNumber($stats['npcs']); ?></h2>
                                    </div>
                                    <div>
                                        <i class="fas fa-dragon fa-3x"></i>
                                    </div>
                                </div>
                                <small>Including <?php echo formatNumber($stats['bosses']); ?> boss monsters</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Skills</h5>
                                        <h2 class="mb-0"><?php echo formatNumber($stats['skills'] + $stats['passive_skills']); ?></h2>
                                    </div>
                                    <div>
                                        <i class="fas fa-magic fa-3x"></i>
                                    </div>
                                </div>
                                <small>
                                    Active: <?php echo formatNumber($stats['skills']); ?> | 
                                    Passive: <?php echo formatNumber($stats['passive_skills']); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Spawns</h5>
                                        <h2 class="mb-0"><?php echo formatNumber($stats['spawns']); ?></h2>
                                    </div>
                                    <div>
                                        <i class="fas fa-map-marker-alt fa-3x"></i>
                                    </div>
                                </div>
                                <small>Across <?php echo formatNumber($stats['maps']); ?> different maps</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Quick Actions -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <a href="items/add.php" class="btn btn-primary w-100">
                                            <i class="fas fa-plus-circle me-2"></i> Add New Item
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="npcs/add.php" class="btn btn-danger w-100">
                                            <i class="fas fa-plus-circle me-2"></i> Add New NPC
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="skills/add.php" class="btn btn-success w-100">
                                            <i class="fas fa-plus-circle me-2"></i> Add New Skill
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="spawns/add.php" class="btn btn-info w-100 text-white">
                                            <i class="fas fa-plus-circle me-2"></i> Add New Spawn
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="drops/manage.php" class="btn btn-warning w-100">
                                            <i class="fas fa-edit me-2"></i> Manage Drops
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="backup.php" class="btn btn-secondary w-100">
                                            <i class="fas fa-database me-2"></i> Backup Database
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($recentLogs as $log): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?php echo $log['ip']; ?></strong> accessed <code><?php echo $log['page']; ?></code>
                                            </div>
                                            <small class="text-muted"><?php echo $log['datetime']; ?></small>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="logs.php" class="btn btn-sm btn-outline-secondary">View All Logs</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">System Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
                                <p><strong>MySQL Version:</strong> <?php echo $db->getConnection()->server_info; ?></p>
                                <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Database Size:</strong> <?php echo '~500 MB'; // Placeholder ?></p>
                                <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                                <p><strong>Memory Usage:</strong> <?php echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB'; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Site URL:</strong> <?php echo SITE_URL; ?></p>
                                <p><strong>Admin Email:</strong> <?php echo ADMIN_EMAIL; ?></p>
                                <p><strong>Error Reporting:</strong> <?php echo error_reporting() ? 'Enabled' : 'Disabled'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="text-center text-muted py-3 mt-4 border-top">
                    <p>&copy; <?php echo date('Y'); ?> LineageII Remastered Database - Admin Panel</p>
                </footer>
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin JS -->
    <script src="<?php echo SITE_URL; ?>/public/js/admin.js"></script>
</body>
</html>