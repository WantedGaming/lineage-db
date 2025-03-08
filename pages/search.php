<?php
// Include configuration files
require_once '../config/config.php';
require_once '../config/constants.php';

// Include database connection
require_once '../includes/core/Database.php';

// Include helper functions
require_once '../includes/functions.php';

// Include models
require_once '../includes/models/Item.php';
require_once '../includes/models/NPC.php';
require_once '../includes/models/Skill.php';
require_once '../includes/models/Spawn.php';

// Initialize models
$itemModel = new Item();
$npcModel = new NPC();
$skillModel = new Skill();
$spawnModel = new Spawn();

// Get search query from GET parameter
$searchQuery = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$searchType = isset($_GET['type']) ? sanitizeInput($_GET['type']) : 'all';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Initialize results arrays
$itemResults = [];
$npcResults = [];
$skillResults = [];
$totalResults = 0;

// Perform search if query is not empty
if (!empty($searchQuery)) {
    if ($searchType == 'all' || $searchType == 'item') {
        // Search items
        $itemResults = $itemModel->searchItems($searchQuery, $page, ITEMS_PER_PAGE);
        $totalResults += $itemResults['total'];
    }
    
    if ($searchType == 'all' || $searchType == 'npc') {
        // Search NPCs
        $npcResults = $npcModel->searchNPCs($searchQuery, $page, ITEMS_PER_PAGE);
        $totalResults += $npcResults['total'];
    }
    
    if ($searchType == 'all' || $searchType == 'skill') {
        // Search skills
        $skillResults = $skillModel->searchSkills($searchQuery, $page, ITEMS_PER_PAGE);
        $totalResults += $skillResults['total'];
    }
}

// Page title
$pageTitle = 'Search Results: ' . $searchQuery;

// Include header template
include_once '../includes/templates/header.php';
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Search Results</h5>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="<?php echo SITE_URL; ?>/pages/search.php" method="get" class="mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search for items, NPCs, skills, etc.">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="type">
                        <option value="all" <?php echo ($searchType == 'all') ? 'selected' : ''; ?>>All</option>
                        <option value="item" <?php echo ($searchType == 'item') ? 'selected' : ''; ?>>Items</option>
                        <option value="npc" <?php echo ($searchType == 'npc') ? 'selected' : ''; ?>>NPCs</option>
                        <option value="skill" <?php echo ($searchType == 'skill') ? 'selected' : ''; ?>>Skills</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>

        <?php if (empty($searchQuery)): ?>
        <div class="alert alert-info">
            <h4 class="alert-heading">Search for anything in the database</h4>
            <p>Enter a search term above to find items, NPCs, skills, and more.</p>
            <hr>
            <p class="mb-0">You can search by name, description, or ID numbers.</p>
        </div>
        <?php elseif ($totalResults == 0): ?>
        <div class="alert alert-warning">
            <h4 class="alert-heading">No results found</h4>
            <p>Your search for <strong>"<?php echo htmlspecialchars($searchQuery); ?>"</strong> did not match any records in the database.</p>
            <hr>
            <p class="mb-0">Try using different keywords or check your spelling.</p>
        </div>
        <?php else: ?>
        <div class="alert alert-success">
            <h4 class="alert-heading">Search Results</h4>
            <p>Found <strong><?php echo $totalResults; ?></strong> results for <strong>"<?php echo htmlspecialchars($searchQuery); ?>"</strong></p>
        </div>
        
        <!-- Items Results -->
        <?php if (!empty($itemResults) && $itemResults['total'] > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Items (<?php echo $itemResults['total']; ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Grade</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = $itemResults['data']->fetch_assoc()): ?>
                            <tr>
                                <td class="search-content">
                                    <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=<?php echo $item['item_id']; ?>&type=<?php echo $item['item_type']; ?>">
                                        <?php echo $item['desc_en']; ?>
                                    </a>
                                </td>
                                <td><?php echo ucfirst($item['item_type']); ?></td>
                                <td><?php echo formatItemGrade($item['itemGrade']); ?></td>
                                <td class="search-content"><?php echo substr($item['note'] ?? '', 0, 100) . (strlen($item['note'] ?? '') > 100 ? '...' : ''); ?></td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=<?php echo $item['item_id']; ?>&type=<?php echo $item['item_type']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($searchType == 'item'): ?>
                <!-- Pagination for item results when only showing items -->
                <?php 
                $paginationUrl = SITE_URL . '/pages/search.php?q=' . urlencode($searchQuery) . '&type=item&page=%d';
                echo generatePagination($itemResults['pages'], $page, $paginationUrl); 
                ?>
                <?php endif; ?>
                
                <?php if ($itemResults['total'] > ITEMS_PER_PAGE && $searchType == 'all'): ?>
                <div class="text-center mt-3">
                    <a href="<?php echo SITE_URL; ?>/pages/search.php?q=<?php echo urlencode($searchQuery); ?>&type=item" class="btn btn-outline-success">
                        View All Item Results
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- NPCs Results -->
        <?php if (!empty($npcResults) && $npcResults['total'] > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">NPCs (<?php echo $npcResults['total']; ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Type</th>
                                <th>HP</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($npc = $npcResults['data']->fetch_assoc()): ?>
                            <tr>
                                <td class="search-content">
                                    <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=<?php echo $npc['npcid']; ?>">
                                        <?php echo $npc['desc_en']; ?>
                                    </a>
                                </td>
                                <td><?php echo $npc['lvl']; ?></td>
                                <td>
                                    <?php if ($npc['is_bossmonster'] == 'true'): ?>
                                    <span class="badge bg-danger">Boss</span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">NPC</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo formatNumber($npc['hp']); ?></td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/npcs/view.php?id=<?php echo $npc['npcid']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($searchType == 'npc'): ?>
                <!-- Pagination for NPC results when only showing NPCs -->
                <?php 
                $paginationUrl = SITE_URL . '/pages/search.php?q=' . urlencode($searchQuery) . '&type=npc&page=%d';
                echo generatePagination($npcResults['pages'], $page, $paginationUrl); 
                ?>
                <?php endif; ?>
                
                <?php if ($npcResults['total'] > ITEMS_PER_PAGE && $searchType == 'all'): ?>
                <div class="text-center mt-3">
                    <a href="<?php echo SITE_URL; ?>/pages/search.php?q=<?php echo urlencode($searchQuery); ?>&type=npc" class="btn btn-outline-danger">
                        View All NPC Results
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Skills Results -->
        <?php if (!empty($skillResults) && $skillResults['total'] > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Skills (<?php echo $skillResults['total']; ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Class</th>
                                <th>Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($skill = $skillResults['data']->fetch_assoc()): ?>
                            <tr>
                                <td class="search-content">
                                    <a href="<?php echo SITE_URL; ?>/pages/skills/view.php?id=<?php echo $skill['id']; ?>&type=<?php echo $skill['skill_type']; ?>">
                                        <?php echo $skill['name']; ?>
                                    </a>
                                </td>
                                <td><?php echo ucfirst($skill['skill_type']); ?></td>
                                <td>
                                    <?php if ($skill['classType'] != 'none'): ?>
                                    <?php echo formatClassType($skill['classType']); ?>
                                    <?php else: ?>
                                    All Classes
                                    <?php endif; ?>
                                </td>
                                <td><?php echo formatItemGrade($skill['grade']); ?></td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/skills/view.php?id=<?php echo $skill['id']; ?>&type=<?php echo $skill['skill_type']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($searchType == 'skill'): ?>
                <!-- Pagination for skill results when only showing skills -->
                <?php 
                $paginationUrl = SITE_URL . '/pages/search.php?q=' . urlencode($searchQuery) . '&type=skill&page=%d';
                echo generatePagination($skillResults['pages'], $page, $paginationUrl); 
                ?>
                <?php endif; ?>
                
                <?php if ($skillResults['total'] > ITEMS_PER_PAGE && $searchType == 'all'): ?>
                <div class="text-center mt-3">
                    <a href="<?php echo SITE_URL; ?>/pages/search.php?q=<?php echo urlencode($searchQuery); ?>&type=skill" class="btn btn-outline-info">
                        View All Skill Results
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>
</div>

<!-- Search Tips -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Search Tips</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6>Item Search</h6>
                <ul>
                    <li>Search by item name: "Dragon Slayer"</li>
                    <li>Search by item type: "Sword" or "Dagger"</li>
                    <li>Search by grade: "Legendary" or "Rare"</li>
                    <li>Search by effect: "Critical" or "MP Recovery"</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>NPC Search</h6>
                <ul>
                    <li>Search by NPC name: "Antharas" or "Shopkeeper"</li>
                    <li>Search by level range: "Level 80" or "Boss"</li>
                    <li>Search by location: "Aden" or "Oren"</li>
                    <li>Search by drop: "Dragon Scale"</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Skill Search</h6>
                <ul>
                    <li>Search by skill name: "Fireball" or "Heal"</li>
                    <li>Search by class: "Knight" or "Wizard"</li>
                    <li>Search by effect: "Stun" or "Slow"</li>
                    <li>Search by level: "Level 10"</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer template
include_once '../includes/templates/footer.php';
?>