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

// Get item ID from query string
$itemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$itemType = isset($_GET['type']) ? $_GET['type'] : '';

// Set default type to weapon if not specified
if (empty($itemType)) {
    $itemType = 'weapon';
}

// Get item data based on type
$item = null;
$itemDrops = [];
$itemEnchantEffects = [];

if ($itemType == 'weapon') {
    // Get weapon data
    $item = $itemModel->getWeaponById($itemId);
    
    // Get weapon enchant effects if any
    if ($item) {
        $itemEnchantEffects = $itemModel->getWeaponEnchantEffects($itemId);
    }
} elseif ($itemType == 'armor') {
    // Get armor data
    $item = $itemModel->getArmorById($itemId);
} elseif ($itemType == 'etcitem') {
    // Get etc item data
    $item = $itemModel->getEtcItemById($itemId);
}

// Get item drops if item exists
if ($item) {
    $itemDrops = $itemModel->getItemDrops($itemId);
}

// Page title
$pageTitle = $item ? $item['desc_en'] : 'Item Not Found';

// Include header template
include_once '../../includes/templates/header.php';
?>

<?php if (!$item): ?>
<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">Item Not Found</h4>
    <p>The requested item could not be found in the database.</p>
    <hr>
    <p class="mb-0">Please check the item ID and try again, or browse our item categories below.</p>
    
    <div class="mt-3">
        <a href="<?php echo SITE_URL; ?>/pages/items/weapon.php" class="btn btn-outline-primary">Browse Weapons</a>
        <a href="<?php echo SITE_URL; ?>/pages/items/armor.php" class="btn btn-outline-primary">Browse Armor</a>
        <a href="<?php echo SITE_URL; ?>/pages/items/etc.php" class="btn btn-outline-primary">Browse ETC Items</a>
    </div>
</div>
<?php else: ?>

<!-- Item Details -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Item Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="<?php echo getItemIconUrl($item['iconId']); ?>" alt="<?php echo $item['desc_en']; ?>" class="item-icon img-fluid">
                    <h4 class="mt-2"><?php echo $item['desc_en']; ?></h4>
                    <div>
                        <?php echo formatItemGrade($item['itemGrade']); ?>
                        <?php if (isset($item['type'])): ?>
                        <span class="badge bg-secondary"><?php echo formatItemType($item['type']); ?></span>
                        <?php endif; ?>
            
            <!-- Stats Bonuses (for all item types) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Stat Bonuses</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm">
                            <?php
                            // Function to check and display stat bonus
                            function displayStatBonus($statName, $statValue) {
                                if ($statValue != 0) {
                                    echo '<tr>';
                                    echo '<th>' . $statName . ':</th>';
                                    echo '<td>' . ($statValue > 0 ? '+' : '') . $statValue . '</td>';
                                    echo '</tr>';
                                    return true;
                                }
                                return false;
                            }
                            
                            $hasStats = false;
                            $hasStats |= displayStatBonus('STR', $item['add_str'] ?? 0);
                            $hasStats |= displayStatBonus('DEX', $item['add_dex'] ?? 0);
                            $hasStats |= displayStatBonus('CON', $item['add_con'] ?? 0);
                            $hasStats |= displayStatBonus('INT', $item['add_int'] ?? 0);
                            $hasStats |= displayStatBonus('WIS', $item['add_wis'] ?? 0);
                            $hasStats |= displayStatBonus('CHA', $item['add_cha'] ?? 0);
                            $hasStats |= displayStatBonus('HP', $item['add_hp'] ?? 0);
                            $hasStats |= displayStatBonus('MP', $item['add_mp'] ?? 0);
                            $hasStats |= displayStatBonus('HP Regen', $item['add_hpr'] ?? 0);
                            $hasStats |= displayStatBonus('MP Regen', $item['add_mpr'] ?? 0);
                            $hasStats |= displayStatBonus('SP', $item['add_sp'] ?? 0);
                            $hasStats |= displayStatBonus('DG', $item['addDg'] ?? 0);
                            $hasStats |= displayStatBonus('ER', $item['addEr'] ?? 0);
                            $hasStats |= displayStatBonus('ME', $item['addMe'] ?? 0);
                            $hasStats |= displayStatBonus('EXP Bonus', $item['expBonus'] ?? 0);
                            
                            if (!$hasStats) {
                                echo '<tr><td colspan="2" class="text-center">No stat bonuses</td></tr>';
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
                        <?php if (isset($item['item_type'])): ?>
                        <span class="badge bg-secondary"><?php echo formatItemType($item['item_type']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <table class="table table-striped table-sm">
                    <tr>
                        <th>Item ID:</th>
                        <td><?php echo $item['item_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Name ID:</th>
                        <td><?php echo $item['item_name_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Korean Name:</th>
                        <td><?php echo $item['desc_kr']; ?></td>
                    </tr>
                    <tr>
                        <th>Weight:</th>
                        <td><?php echo $item['weight']; ?></td>
                    </tr>
                    <tr>
                        <th>Grade:</th>
                        <td><?php echo $item['itemGrade']; ?></td>
                    </tr>
                    <tr>
                        <th>Material:</th>
                        <td><?php echo $item['material']; ?></td>
                    </tr>
                    <?php if ($itemType == 'weapon' || $itemType == 'armor'): ?>
                    <tr>
                        <th>Safe Enchant:</th>
                        <td><?php echo $item['safenchant']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Level Required:</th>
                        <td>
                            <?php if ($item['min_lvl'] > 0): ?>
                            <?php echo $item['min_lvl']; ?> - <?php echo $item['max_lvl']; ?>
                            <?php else: ?>
                            None
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tradable:</th>
                        <td><?php echo formatBoolean($item['trade'] == 0); ?></td>
                    </tr>
                    <tr>
                        <th>Sellable:</th>
                        <td><?php echo formatBoolean($item['cant_sell'] == 0); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Item Description</h5>
            </div>
            <div class="card-body">
                <p><?php echo $item['note']; ?></p>
            </div>
        </div>
        
        <div class="row">
            <?php if ($itemType == 'weapon'): ?>
            <!-- Weapon Specific Info -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Weapon Stats</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm">
                            <tr>
                                <th>Damage (S/L):</th>
                                <td><?php echo $item['dmg_small']; ?> / <?php echo $item['dmg_large']; ?></td>
                            </tr>
                            <tr>
                                <th>Hit Modifier:</th>
                                <td><?php echo ($item['hitmodifier'] > 0 ? '+' : '') . $item['hitmodifier']; ?></td>
                            </tr>
                            <tr>
                                <th>Damage Modifier:</th>
                                <td><?php echo ($item['dmgmodifier'] > 0 ? '+' : '') . $item['dmgmodifier']; ?></td>
                            </tr>
                            <?php if ($item['double_dmg_chance'] > 0): ?>
                            <tr>
                                <th>Double Damage:</th>
                                <td><?php echo $item['double_dmg_chance']; ?>%</td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['magicdmgmodifier'] > 0): ?>
                            <tr>
                                <th>Magic Damage Modifier:</th>
                                <td>+<?php echo $item['magicdmgmodifier']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['shortCritical'] > 0): ?>
                            <tr>
                                <th>Critical Rate:</th>
                                <td>+<?php echo $item['shortCritical']; ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php elseif ($itemType == 'armor'): ?>
            <!-- Armor Specific Info -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Armor Stats</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-sm">
                            <tr>
                                <th>AC:</th>
                                <td><?php echo $item['ac']; ?></td>
                            </tr>
                            <tr>
                                <th>Magic Defense:</th>
                                <td><?php echo $item['m_def']; ?></td>
                            </tr>
                            <?php if ($item['damage_reduction'] > 0): ?>
                            <tr>
                                <th>Damage Reduction:</th>
                                <td><?php echo $item['damage_reduction']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['MagicDamageReduction'] > 0): ?>
                            <tr>
                                <th>Magic Damage Reduction:</th>
                                <td><?php echo $item['MagicDamageReduction']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['defense_water'] > 0 || $item['defense_wind'] > 0 || $item['defense_fire'] > 0 || $item['defense_earth'] > 0): ?>
                            <tr>
                                <th>Elemental Resistance:</th>
                                <td>
                                    <?php if ($item['defense_water'] > 0): ?>
                                    <span class="element water">Water: <?php echo $item['defense_water']; ?></span>
                                    <?php endif; ?>
                                    <?php if ($item['defense_wind'] > 0): ?>
                                    <span class="element wind">Wind: <?php echo $item['defense_wind']; ?></span>
                                    <?php endif; ?>
                                    <?php if ($item['defense_fire'] > 0): ?>
                                    <span class="element fire">Fire: <?php echo $item['defense_fire']; ?></span>
                                    <?php endif; ?>
                                    <?php if ($item['defense_earth'] > 0): ?>
                                    <span class="element earth">Earth: <?php echo $item['defense_earth']; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>