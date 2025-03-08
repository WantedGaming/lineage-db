<?php 
$currentPage = getCurrentPage();
?>
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item">
        <a class="nav-link <?php echo isActiveNavItem($currentPage, 'index'); ?>" href="<?php echo SITE_URL; ?>">Home</a>
    </li>
    
    <!-- Items Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo (strpos($currentPage, 'item') !== false) ? 'active' : ''; ?>" href="#" id="itemsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Items
        </a>
        <ul class="dropdown-menu" aria-labelledby="itemsDropdown">
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/items/">All Items</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/items/weapon.php">Weapons</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/items/armor.php">Armor</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/items/etc.php">ETC Items</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/items/sets.php">Armor Sets</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/items/crafting.php">Crafting</a></li>
        </ul>
    </li>
    
    <!-- NPCs Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo (strpos($currentPage, 'npc') !== false) ? 'active' : ''; ?>" href="#" id="npcsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            NPCs
        </a>
        <ul class="dropdown-menu" aria-labelledby="npcsDropdown">
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/npcs/">All NPCs</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/npcs/monsters.php">Monsters</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/npcs/bosses.php">Bosses</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/npcs/shopkeepers.php">Shopkeepers</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/npcs/quest.php">Quest NPCs</a></li>
        </ul>
    </li>
    
    <!-- Skills Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo (strpos($currentPage, 'skill') !== false) ? 'active' : ''; ?>" href="#" id="skillsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Skills
        </a>
        <ul class="dropdown-menu" aria-labelledby="skillsDropdown">
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/">All Skills</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/active.php">Active Skills</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/passive.php">Passive Skills</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=crown">Prince/Princess</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=knight">Knight</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=elf">Elf</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=wizard">Wizard</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=darkelf">Dark Elf</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=dragonknight">Dragon Knight</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=illusionist">Illusionist</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=warrior">Warrior</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=fencer">Fencer</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/skills/class.php?type=lancer">Lancer</a></li>
        </ul>
    </li>
    
    <!-- Spawns Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo (strpos($currentPage, 'spawn') !== false) ? 'active' : ''; ?>" href="#" id="spawnsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Spawns
        </a>
        <ul class="dropdown-menu" aria-labelledby="spawnsDropdown">
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/spawns/">All Spawns</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/spawns/boss.php">Boss Spawns</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/spawns/maps.php">By Map</a></li>
        </ul>
    </li>
    
    <!-- Maps Link -->
    <li class="nav-item">
        <a class="nav-link <?php echo isActiveNavItem($currentPage, 'maps'); ?>" href="<?php echo SITE_URL; ?>/pages/maps.php">Maps</a>
    </li>
    
    <!-- Tools Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo (strpos($currentPage, 'tool') !== false) ? 'active' : ''; ?>" href="#" id="toolsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tools
        </a>
        <ul class="dropdown-menu" aria-labelledby="toolsDropdown">
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/tools/drop_calculator.php">Drop Calculator</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/tools/exp_calculator.php">EXP Calculator</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/tools/enchant_simulator.php">Enchant Simulator</a></li>
            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/tools/boss_timer.php">Boss Timer</a></li>
        </ul>
    </li>
</ul>