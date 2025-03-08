<?php
// Include configuration files
require_once 'config/config.php';  // Changed from '../config/config.php'
require_once 'config/constants.php';

// Include database connection
require_once 'includes/core/Database.php';

// Include helper functions
require_once 'includes/functions.php';

// Include models
require_once 'includes/models/Item.php';
require_once 'includes/models/NPC.php';
require_once 'includes/models/Skill.php';
require_once 'includes/models/Spawn.php';

// Initialize models
$itemModel = new Item();
$npcModel = new NPC();
$skillModel = new Skill();
$spawnModel = new Spawn();

// Recent bosses
$bossSpawns = $npcModel->getBossSpawnTimes();

// Database statistics
$weaponTypes = $itemModel->getWeaponTypeCounts();
$armorTypes = $itemModel->getArmorTypeCounts();
$etcItemTypes = $itemModel->getEtcItemTypeCounts();
$npcLevelRanges = $npcModel->getNPCCountByLevelRange();
$skillClasses = $skillModel->getSkillCountByClass();
$mapSpawnCounts = $spawnModel->getSpawnCountByMap();

// Page title
$pageTitle = "Home";

// Include header template
include_once 'includes/templates/header.php';
?>

<!-- Hero Section -->
<section class="jumbotron text-center bg-light p-5 mb-4 rounded">
    <h1 class="display-4">Lineage-R Database</h1>
    <p class="lead">A comprehensive database for Lineage Remastered game.</p>
    <hr class="my-4">
    <p>Browse information about items, NPCs, skills, spawns, and more.</p>
    <div class="d-flex justify-content-center gap-2">
        <a class="btn btn-primary btn-lg" href="<?php echo SITE_URL; ?>/pages/items/" role="button">Browse Items</a>
        <a class="btn btn-secondary btn-lg" href="<?php echo SITE_URL; ?>/pages/npcs/" role="button">Browse NPCs</a>
        <a class="btn btn-success btn-lg" href="<?php echo SITE_URL; ?>/pages/skills/" role="button">Browse Skills</a>
    </div>
</section>

<!-- Quick Search Section -->
<section class="mb-5">
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Quick Search</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo SITE_URL; ?>/pages/search.php" method="get" class="row g-3">
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-lg" name="q" placeholder="Search for items, NPCs, skills, etc.">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-lg" name="type">
                        <option value="all">All</option>
                        <option value="item">Items</option>
                        <option value="npc">NPCs</option>
                        <option value="skill">Skills</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>

<div class="row">
    <!-- Boss Timers Section -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Boss Timers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Boss</th>
                                <th>Level</th>
                                <th>Location</th>
                                <th>Spawn Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($bossSpawns, 0, 5) as $boss): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=<?php echo $boss['npcid']; ?>">
                                        <?php echo $boss['boss_name']; ?>
                                    </a>
                                </td>
                                <td><?php echo $boss['level']; ?></td>
                                <td><?php echo $boss['map_name'] ?? 'Unknown'; ?></td>
                                <td><?php echo formatSpawnTime($boss['spawnTime']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="<?php echo SITE_URL; ?>/pages/tools/boss_timer.php" class="btn btn-outline-danger">View All Boss Timers</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Database Statistics -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Database Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="stat-card mb-3">
                            <h6>Items</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Weapons:</span>
                                <span class="fw-bold"><?php echo formatNumber(array_sum($weaponTypes)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Armor:</span>
                                <span class="fw-bold"><?php echo formatNumber(array_sum($armorTypes)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>ETC Items:</span>
                                <span class="fw-bold"><?php echo formatNumber(array_sum($etcItemTypes)); ?></span>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <h6>NPCs</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Total NPCs:</span>
                                <span class="fw-bold"><?php echo formatNumber(array_sum($npcLevelRanges)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Boss NPCs:</span>
                                <span class="fw-bold"><?php echo formatNumber(count($bossSpawns)); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <div class="stat-card mb-3">
                            <h6>Skills</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Total Skills:</span>
                                <span class="fw-bold"><?php echo formatNumber(array_sum($skillClasses)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Class Skills:</span>
                                <span class="fw-bold"><?php echo count($skillClasses); ?> Classes</span>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <h6>Maps & Spawns</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Total Maps:</span>
                                <span class="fw-bold"><?php echo formatNumber(count($mapSpawnCounts)); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Spawn Points:</span>
                                <span class="fw-bold"><?php echo formatNumber(array_sum($mapSpawnCounts)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary">View More Statistics</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Content -->
<div class="row">
    <!-- Featured Items -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Featured Items</h5>
            </div>
            <div class="card-body">
                <div class="featured-items">
                    <!-- This would typically be populated from a "featured" table in your database -->
                    <div class="featured-item mb-3">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/public/images/items/placeholder.png" alt="Item" class="me-2" width="32" height="32">
                            <div>
                                <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=123" class="fw-bold">Dragon Slayer</a>
                                <div class="small text-muted">Legendary Two-Handed Sword</div>
                            </div>
                        </div>
                    </div>
                    <div class="featured-item mb-3">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/public/images/items/placeholder.png" alt="Item" class="me-2" width="32" height="32">
                            <div>
                                <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=456" class="fw-bold">Draconic Plate Armor</a>
                                <div class="small text-muted">Legendary Full Plate</div>
                            </div>
                        </div>
                    </div>
                    <div class="featured-item">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/public/images/items/placeholder.png" alt="Item" class="me-2" width="32" height="32">
                            <div>
                                <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=789" class="fw-bold">Einhasad's Blessing</a>
                                <div class="small text-muted">Rare Buff Item</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="<?php echo SITE_URL; ?>/pages/items/" class="btn btn-outline-success">Browse All Items</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Featured NPCs -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Featured NPCs</h5>
            </div>
            <div class="card-body">
                <div class="featured-npcs">
                    <div class="featured-npc mb-3">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/public/images/npcs/placeholder.png" alt="NPC" class="me-2" width="32" height="32">
                            <div>
                                <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=123" class="fw-bold">Antharas</a>
                                <div class="small text-muted">Ancient Dragon Boss, Level 85</div>
                            </div>
                        </div>
                    </div>
                    <div class="featured-npc mb-3">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/public/images/npcs/placeholder.png" alt="NPC" class="me-2" width="32" height="32">
                            <div>
                                <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=456" class="fw-bold">High Priest Raymond</a>
                                <div class="small text-muted">Quest NPC, Aden Castle</div>
                            </div>
                        </div>
                    </div>
                    <div class="featured-npc">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo SITE_URL; ?>/public/images/npcs/placeholder.png" alt="NPC" class="me-2" width="32" height="32">
                            <div>
                                <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=789" class="fw-bold">Dread Avenger</a>
                                <div class="small text-muted">Elite Monster, Level 65</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="<?php echo SITE_URL; ?>/pages/npcs/" class="btn btn-outline-info">Browse All NPCs</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Tools -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Quick Tools</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?php echo SITE_URL; ?>/pages/tools/drop_calculator.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Drop Calculator
                        <i class="fas fa-calculator"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/tools/exp_calculator.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        EXP Calculator
                        <i class="fas fa-chart-line"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/tools/enchant_simulator.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Enchant Simulator
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/tools/boss_timer.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        Boss Timer
                        <i class="fas fa-stopwatch"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/pages/maps.php" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        World Maps
                        <i class="fas fa-map"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer template
include_once 'includes/templates/footer.php';
?>