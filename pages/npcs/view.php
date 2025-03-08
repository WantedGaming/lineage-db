<?php
// Include configuration files
require_once '../../config/config.php';
require_once '../../config/constants.php';

// Include database connection
require_once '../../includes/core/Database.php';

// Include helper functions
require_once '../../includes/functions.php';

// Include models
require_once '../../includes/models/NPC.php';
require_once '../../includes/models/Spawn.php';

// Initialize models
$npcModel = new NPC();
$spawnModel = new Spawn();

// Get NPC ID from query string
$npcId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get NPC data
$npc = $npcModel->getNPCById($npcId);

// If NPC exists, get additional information
if ($npc) {
    // Get NPC drops
    $npcDrops = $npcModel->getNPCDrops($npcId);
    
    // Get NPC spawn locations
    $npcSpawns = $npcModel->getNPCSpawns($npcId);
    
    // Get NPC skills
    $npcSkills = $npcModel->getNPCSkills($npcId);
}

// Page title
$pageTitle = $npc ? $npc['desc_en'] : 'NPC Not Found';

// Include header template
include_once '../../includes/templates/header.php';
?>

<?php if (!$npc): ?>
<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">NPC Not Found</h4>
    <p>The requested NPC could not be found in the database.</p>
    <hr>
    <p class="mb-0">Please check the NPC ID and try again, or browse our NPC categories below.</p>
    
    <div class="mt-3">
        <a href="<?php echo SITE_URL; ?>/pages/npcs/monsters.php" class="btn btn-outline-primary">Browse Monsters</a>
        <a href="<?php echo SITE_URL; ?>/pages/npcs/bosses.php" class="btn btn-outline-primary">Browse Bosses</a>
        <a href="<?php echo SITE_URL; ?>/pages/npcs/" class="btn btn-outline-primary">Browse All NPCs</a>
    </div>
</div>
<?php else: ?>

<!-- NPC Details -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">NPC Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="<?php echo SITE_URL; ?>/public/images/npcs/<?php echo $npc['spriteId']; ?>.png" alt="<?php echo $npc['desc_en']; ?>" class="npc-sprite img-fluid">
                    <h4 class="mt-2"><?php echo $npc['desc_en']; ?></h4>
                    <div>
                        <span class="badge bg-secondary">Level <?php echo $npc['lvl']; ?></span>
                        <?php if ($npc['is_bossmonster'] == 'true'): ?>
                        <span class="badge bg-danger">Boss</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <table class="table table-striped table-sm">
                    <tr>
                        <th>NPC ID:</th>
                        <td><?php echo $npc['npcid']; ?></td>
                    </tr>
                    <tr>
                        <th>Class ID:</th>
                        <td><?php echo $npc['classId']; ?></td>
                    </tr>
                    <tr>
                        <th>Korean Name:</th>
                        <td><?php echo $npc['desc_kr']; ?></td>
                    </tr>
                    <tr>
                        <th>Level:</th>
                        <td><?php echo $npc['lvl']; ?></td>
                    </tr>
                    <tr>
                        <th>HP:</th>
                        <td><?php echo formatNumber($npc['hp']); ?></td>
                    </tr>
                    <tr>
                        <th>MP:</th>
                        <td><?php echo formatNumber($npc['mp']); ?></td>
                    </tr>
                    <tr>
                        <th>AC:</th>
                        <td><?php echo $npc['ac']; ?></td>
                    </tr>
                    <tr>
                        <th>Alignment:</th>
                        <td><?php echo formatAlignment($npc['alignment']); ?></td>
                    </tr>
                    <tr>
                        <th>Size:</th>
                        <td><?php echo $npc['big'] == 'true' ? 'Large' : 'Normal'; ?></td>
                    </tr>
                    <tr>
                        <th>Family:</th>
                        <td><?php echo !empty($npc['family']) ? $npc['family'] : 'None'; ?></td>
                    </tr>
                    <tr>
                        <th>Aggressive:</th>
                        <td><?php echo formatBoolean($npc['is_agro']); ?></td>
                    </tr>
                    <tr>
                        <th>Can be Tamed:</th>
                        <td><?php echo formatBoolean($npc['is_taming']); ?></td>
                    </tr>
                    <tr>
                        <th>Undead Type:</th>
                        <td><?php echo $npc['undead']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Stats</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Base Stats</h6>
                        <table class="table table-striped table-sm">
                            <tr>
                                <th>STR:</th>
                                <td><?php echo $npc['str']; ?></td>
                            </tr>
                            <tr>
                                <th>DEX:</th>
                                <td><?php echo $npc['dex']; ?></td>
                            </tr>
                            <tr>
                                <th>CON:</th>
                                <td><?php echo $npc['con']; ?></td>
                            </tr>
                            <tr>
                                <th>WIS:</th>
                                <td><?php echo $npc['wis']; ?></td>
                            </tr>
                            <tr>
                                <th>INT:</th>
                                <td><?php echo $npc['intel']; ?></td>
                            </tr>
                            <tr>
                                <th>Magic Resistance:</th>
                                <td><?php echo $npc['mr']; ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Combat Stats</h6>
                        <table class="table table-striped table-sm">
                            <tr>
                                <th>Attack Speed:</th>
                                <td><?php echo $npc['atkspeed']; ?></td>
                            </tr>
                            <tr>
                                <th>Movement Speed:</th>
                                <td><?php echo $npc['passispeed']; ?></td>
                            </tr>
                            <tr>
                                <th>HP Regen:</th>
                                <td><?php echo $npc['hpr']; ?> / <?php echo $npc['hprinterval']; ?>s</td>
                            </tr>
                            <tr>
                                <th>MP Regen:</th>
                                <td><?php echo $npc['mpr']; ?> / <?php echo $npc['mprinterval']; ?>s</td>
                            </tr>
                            <tr>
                                <th>Damage Reduction:</th>
                                <td><?php echo $npc['damage_reduction']; ?></td>
                            </tr>
                            <tr>
                                <th>Experience:</th>
                                <td><?php echo formatNumber($npc['exp']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($npcSpawns)): ?>
        <!-- NPC Spawn Locations -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Spawn Locations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Map</th>
                                <th>Location</th>
                                <th>Count</th>
                                <th>Movement Range</th>
                                <th>Respawn Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($npcSpawns as $spawn): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/maps.php?id=<?php echo $spawn['mapid']; ?>">
                                        <?php echo $spawn['map_name'] ?? 'Unknown Map'; ?>
                                    </a>
                                </td>
                                <td><?php echo formatCoordinates($spawn['locx'], $spawn['locy']); ?></td>
                                <td><?php echo $spawn['count']; ?></td>
                                <td><?php echo $spawn['movement_distance']; ?></td>
                                <td>
                                    <?php 
                                    $minTime = $spawn['min_respawn_delay'] / 1000; // Convert to seconds
                                    $maxTime = $spawn['max_respawn_delay'] / 1000; // Convert to seconds
                                    
                                    if ($minTime == $maxTime) {
                                        echo $minTime . 's';
                                    } else {
                                        echo $minTime . 's - ' . $maxTime . 's';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($npcDrops)): ?>
        <!-- NPC Drops -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Drops</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Drop Rate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($npcDrops as $drop): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=<?php echo $drop['itemId']; ?>&type=<?php echo $drop['item_type']; ?>">
                                        <?php echo $drop['item_name']; ?>
                                    </a>
                                    <?php if ($drop['enchant'] > 0): ?>
                                    <span class="text-success">+<?php echo $drop['enchant']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo ucfirst($drop['item_type']); ?></td>
                                <td><?php echo $drop['min']; ?> - <?php echo $drop['max']; ?></td>
                                <td class="<?php echo getDropRateColor($drop['chance']); ?>">
                                    <?php echo chanceToPercent($drop['chance']); ?>
                                </td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/pages/items/view.php?id=<?php echo $drop['itemId']; ?>&type=<?php echo $drop['item_type']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($npcSkills)): ?>
        <!-- NPC Skills -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Skills</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Probability</th>
                                <th>HP Trigger</th>
                                <th>Effects</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($npcSkills as $skill): ?>
                            <tr>
                                <td><?php echo $skill['desc_en']; ?></td>
                                <td><?php echo $skill['type']; ?></td>
                                <td><?php echo $skill['prob']; ?>%</td>
                                <td>
                                    <?php 
                                    if ($skill['enableHp'] > 0) {
                                        echo "HP <= {$skill['enableHp']}%";
                                    } else {
                                        echo 'Any';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($skill['type'] == 'ATTACK'): ?>
                                    Damage: <?php echo $skill['Leverage']; ?>%
                                    <?php endif; ?>
                                    
                                    <?php if ($skill['type'] == 'SPELL' && $skill['SkillId'] > 0): ?>
                                    Skill ID: <?php echo $skill['SkillId']; ?>
                                    <?php endif; ?>
                                    
                                    <?php if ($skill['type'] == 'SUMMON'): ?>
                                    Summons: <?php echo $skill['SummonId']; ?> (<?php echo $skill['SummonMin']; ?> - <?php echo $skill['SummonMax']; ?>)
                                    <?php endif; ?>
                                    
                                    <?php if ($skill['type'] == 'POLY'): ?>
                                    Poly ID: <?php echo $skill['PolyId']; ?>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($skill['Msg'])): ?>
                                    Message: "<?php echo $skill['Msg']; ?>"
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>

<?php
// Include footer template
include_once '../../includes/templates/footer.php';
?>