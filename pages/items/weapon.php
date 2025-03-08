<?php
// Include configuration files
require_once '../../config/config.php';
require_once '../../config/constants.php';

// Include database connection
require_once '../../includes/core/Database.php';

// Include helper functions
require_once '../../includes/functions.php';

// Include models
require_once '../../includes/models/Item.php';

// Initialize models
$itemModel = new Item();

// Get current page from query string
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Get filters from query string
$filters = [];
if (isset($_GET['name']) && !empty($_GET['name'])) {
    $filters['name'] = $_GET['name'];
}
if (isset($_GET['grade']) && !empty($_GET['grade'])) {
    $filters['grade'] = $_GET['grade'];
}
if (isset($_GET['type']) && !empty($_GET['type'])) {
    $filters['type'] = $_GET['type'];
}

// Get weapons with pagination
$weapons = $itemModel->getWeapons($page, ITEMS_PER_PAGE, $filters);

// Get weapon type counts for stats
$weaponTypes = $itemModel->getWeaponTypeCounts();

// Page title
$pageTitle = "Weapons";

// Include header template
include_once '../../includes/templates/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Weapons Database 
                    <span class="badge bg-light text-dark"><?php echo formatNumber($weapons['total']); ?> items</span>
                </h5>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form action="<?php echo SITE_URL; ?>/pages/items/weapon.php" method="get" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($filters['name']) ? htmlspecialchars($filters['name']) : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="grade" class="form-label">Grade</label>
                            <select class="form-select" id="grade" name="grade">
                                <option value="">All Grades</option>
                                <option value="ONLY" <?php echo (isset($filters['grade']) && $filters['grade'] == 'ONLY') ? 'selected' : ''; ?>>Unique</option>
                                <option value="MYTH" <?php echo (isset($filters['grade']) && $filters['grade'] == 'MYTH') ? 'selected' : ''; ?>>Mythical</option>
                                <option value="LEGEND" <?php echo (isset($filters['grade']) && $filters['grade'] == 'LEGEND') ? 'selected' : ''; ?>>Legendary</option>
                                <option value="HERO" <?php echo (isset($filters['grade']) && $filters['grade'] == 'HERO') ? 'selected' : ''; ?>>Hero</option>
                                <option value="RARE" <?php echo (isset($filters['grade']) && $filters['grade'] == 'RARE') ? 'selected' : ''; ?>>Rare</option>
                                <option value="ADVANC" <?php echo (isset($filters['grade']) && $filters['grade'] == 'ADVANC') ? 'selected' : ''; ?>>Advanced</option>
                                <option value="NORMAL" <?php echo (isset($filters['grade']) && $filters['grade'] == 'NORMAL') ? 'selected' : ''; ?>>Normal</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                <?php foreach($weaponTypes as $type => $count): ?>
                                <option value="<?php echo $type; ?>" <?php echo (isset($filters['type']) && $filters['type'] == $type) ? 'selected' : ''; ?>>
                                    <?php echo formatItemType($type); ?> (<?php echo $count; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="<?php echo SITE_URL; ?>/pages/items/weapon.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <?php if ($weapons['data']->num_rows > 0): ?>
                <!-- Weapons Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Type</th>
                                <th>Damage</th>
                                <th>Weight</th>
                                <th>Safe Enchant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($weapon = $weapons['data']->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo getItemIconUrl($weapon['iconId']); ?>" alt="<?php echo $weapon['desc_en']; ?>" width="32" height="32">
                                </td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=<?php echo $weapon['item_id']; ?>&type=weapon">
                                        <?php echo $weapon['desc_en']; ?>
                                    </a>
                                </td>
                                <td><?php echo formatItemGrade($weapon['itemGrade']); ?></td>
                                <td><?php echo formatItemType($weapon['type']); ?></td>
                                <td><?php echo $weapon['dmg_small']; ?>/<?php echo $weapon['dmg_large']; ?></td>
                                <td><?php echo $weapon['weight']; ?></td>
                                <td><?php echo $weapon['safenchant']; ?></td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=<?php echo $weapon['item_id']; ?>&type=weapon" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php 
                $paginationUrl = SITE_URL . '/pages/items/weapon.php?page=%d';
                
                // Add any applied filters to pagination URL
                if (!empty($filters)) {
                    foreach ($filters as $key => $value) {
                        $paginationUrl .= '&' . $key . '=' . urlencode($value);
                    }
                }
                
                echo generatePagination($weapons['pages'], $page, $paginationUrl); 
                ?>
                
                <?php else: ?>
                <div class="alert alert-info">
                    <h4 class="alert-heading">No weapons found</h4>
                    <p>No weapons matching your criteria were found in the database.</p>
                    <p>Try broadening your search or use different filter settings.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Weapon Info Sidebar -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Weapon Information</h5>
            </div>
            <div class="card-body">
                <p>Weapons are essential equipment for combat in Lineage II Remastered. Each weapon has unique stats, properties, and potential enchant effects.</p>
                
                <h6>Weapon Stats</h6>
                <ul>
                    <li><strong>Damage:</strong> The base damage output of the weapon (Small/Large)</li>
                    <li><strong>Weight:</strong> The weight of the item, affecting inventory capacity</li>
                    <li><strong>Safe Enchant:</strong> The maximum level to which a weapon can be enchanted without risk of destruction</li>
                </ul>
                
                <h6>Weapon Types</h6>
                <ul>
                    <li><strong>Sword:</strong> One-handed weapons balanced in damage and speed</li>
                    <li><strong>Two-Hand Sword:</strong> Slower but more powerful than one-handed swords</li>
                    <li><strong>Dagger:</strong> Fast, lightweight weapons with low damage</li>
                    <li><strong>Bow:</strong> Ranged weapons for attacking from a distance</li>
                    <li><strong>Spear:</strong> Two-handed weapons with long reach</li>
                    <li><strong>Blunt:</strong> One-handed blunt weapons with moderate damage</li>
                    <li><strong>Staff:</strong> Magic-enhancing weapons primarily for spellcasters</li>
                </ul>
            </div>
        </div>
        
        <!-- Weapon Grade Distribution -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Weapon Distribution</h5>
            </div>
            <div class="card-body">
                <h6>By Type</h6>
                <div class="mb-3">
                    <canvas id="weaponTypeChart"></canvas>
                </div>
                
                <h6>By Grade</h6>
                <div class="mb-3">
                    <canvas id="weaponGradeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Javascript for Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weapon Type Chart
    var typeCtx = document.getElementById('weaponTypeChart').getContext('2d');
    var typeChart = new Chart(typeCtx, {
        type: 'pie',
        data: {
            labels: [
                <?php 
                $typeLabels = [];
                foreach($weaponTypes as $type => $count) {
                    $typeLabels[] = "'" . formatItemType($type) . "'";
                }
                echo implode(', ', $typeLabels);
                ?>
            ],
            datasets: [{
                data: [
                    <?php echo implode(', ', array_values($weaponTypes)); ?>
                ],
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', 
                    '#c9cbcf', '#ff9f40', '#8dd681', '#ff9999', '#9999ff'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Weapon Grade Chart - This would need actual data from your database
    // Placeholder data
    var gradeCtx = document.getElementById('weaponGradeChart').getContext('2d');
    var gradeChart = new Chart(gradeCtx, {
        type: 'pie',
        data: {
            labels: ['Normal', 'Advanced', 'Rare', 'Hero', 'Legendary', 'Mythical', 'Unique'],
            datasets: [{
                data: [65, 12, 8, 6, 5, 3, 1], // Placeholder data
                backgroundColor: [
                    '#c9cbcf', '#36a2eb', '#4bc0c0', '#ffce56', 
                    '#ff9f40', '#ff6384', '#9966ff'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<?php
// Include footer template
include_once '../../includes/templates/footer.php';
?>