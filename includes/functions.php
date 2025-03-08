<?php
// Format number with commas
function formatNumber($number) {
    return number_format($number);
}

// Format percentage
function formatPercent($number, $decimals = 2) {
    return number_format($number, $decimals) . '%';
}

// Convert chance value (1-10000) to percentage
function chanceToPercent($chance) {
    return ($chance / 100) . '%';
}

// Format item grade for display
function formatItemGrade($grade) {
    switch ($grade) {
        case 'ONLY':
            return '<span class="grade-only">Unique</span>';
        case 'MYTH':
            return '<span class="grade-myth">Mythical</span>';
        case 'LEGEND':
            return '<span class="grade-legend">Legendary</span>';
        case 'HERO':
            return '<span class="grade-hero">Hero</span>';
        case 'RARE':
            return '<span class="grade-rare">Rare</span>';
        case 'ADVANC':
            return '<span class="grade-advanced">Advanced</span>';
        case 'NORMAL':
            return '<span class="grade-normal">Normal</span>';
        default:
            return $grade;
    }
}

// Format item type for display
function formatItemType($type) {
    // Replace underscores with spaces and capitalize
    return ucwords(str_replace('_', ' ', $type));
}

// Format class type for display
function formatClassType($classType) {
    switch ($classType) {
        case 'crown':
            return 'Prince/Princess';
        case 'knight':
            return 'Knight';
        case 'elf':
            return 'Elf';
        case 'wizard':
            return 'Wizard';
        case 'darkelf':
            return 'Dark Elf';
        case 'dragonknight':
            return 'Dragon Knight';
        case 'illusionist':
            return 'Illusionist';
        case 'warrior':
            return 'Warrior';
        case 'fencer':
            return 'Fencer';
        case 'lancer':
            return 'Lancer';
        default:
            return ucfirst($classType);
    }
}

// Get class icon
function getClassIcon($classType) {
    switch ($classType) {
        case 'crown':
            return 'crown.png';
        case 'knight':
            return 'knight.png';
        case 'elf':
            return 'elf.png';
        case 'wizard':
            return 'wizard.png';
        case 'darkelf':
            return 'darkelf.png';
        case 'dragonknight':
            return 'dragonknight.png';
        case 'illusionist':
            return 'illusionist.png';
        case 'warrior':
            return 'warrior.png';
        case 'fencer':
            return 'fencer.png';
        case 'lancer':
            return 'lancer.png';
        default:
            return 'default.png';
    }
}

// Format yes/no values from database
function formatBoolean($value) {
    if ($value === 'true' || $value === '1' || $value === true) {
        return '<span class="text-success">Yes</span>';
    } else {
        return '<span class="text-danger">No</span>';
    }
}

// Generate pagination controls
function generatePagination($totalPages, $currentPage, $urlPattern) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Page navigation" class="mt-4">
              <ul class="pagination justify-content-center">';
    
    // Previous button
    if ($currentPage > 1) {
        $html .= '<li class="page-item">
                    <a class="page-link" href="' . sprintf($urlPattern, $currentPage - 1) . '" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>';
    } else {
        $html .= '<li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>';
    }
    
    // Page numbers
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $startPage + 4);
    
    if ($endPage - $startPage < 4) {
        $startPage = max(1, $endPage - 4);
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item">
                    <a class="page-link" href="' . sprintf($urlPattern, $currentPage + 1) . '" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>';
    } else {
        $html .= '<li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

// Get item icon URL - This would need to be customized based on your icon storage
function getItemIconUrl($iconId) {
    return SITE_URL . '/public/images/items/' . $iconId . '.png';
}

// Get skill icon URL - This would need to be customized based on your icon storage
function getSkillIconUrl($iconId) {
    return SITE_URL . '/public/images/skills/' . $iconId . '.png';
}

// Format spawn times for boss monsters
function formatSpawnTime($spawnTime) {
    if (empty($spawnTime)) {
        return 'Unknown';
    }
    
    // This is a placeholder - actual implementation depends on how spawn times are stored
    return $spawnTime;
}

// Sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if current page is active for navigation
function isActiveNavItem($currentPage, $checkPage) {
    return ($currentPage == $checkPage) ? 'active' : '';
}

// Format datetime
function formatDateTime($dateTime) {
    if (empty($dateTime) || $dateTime == '0000-00-00 00:00:00') {
        return 'N/A';
    }
    
    return date('Y-m-d H:i', strtotime($dateTime));
}

// Get current page name from URL
function getCurrentPage() {
    $page = basename($_SERVER['PHP_SELF']);
    return str_replace('.php', '', $page);
}

// Format map coordinates for display
function formatCoordinates($x, $y) {
    return "($x, $y)";
}

// Get attribute icon
function getAttributeIcon($attr) {
    switch (strtoupper($attr)) {
        case 'FIRE':
            return '<i class="fas fa-fire text-danger" title="Fire"></i>';
        case 'WATER':
            return '<i class="fas fa-water text-primary" title="Water"></i>';
        case 'WIND':
            return '<i class="fas fa-wind text-info" title="Wind"></i>';
        case 'EARTH':
            return '<i class="fas fa-mountain text-success" title="Earth"></i>';
        default:
            return '';
    }
}

// Format alignment value to text
function formatAlignment($alignment) {
    if ($alignment < -10000) {
        return '<span class="alignment-chaotic">Chaotic</span>';
    } elseif ($alignment < 0) {
        return '<span class="alignment-evil">Evil</span>';
    } elseif ($alignment == 0) {
        return '<span class="alignment-neutral">Neutral</span>';
    } elseif ($alignment < 10000) {
        return '<span class="alignment-good">Good</span>';
    } else {
        return '<span class="alignment-lawful">Lawful</span>';
    }
}

// Get drop rate color based on chance
function getDropRateColor($chance) {
    if ($chance < 100) { // Less than 0.1%
        return 'drop-rate-very-rare';
    } elseif ($chance < 1000) { // Less than 1%
        return 'drop-rate-rare';
    } elseif ($chance < 5000) { // Less than 5%
        return 'drop-rate-uncommon';
    } else {
        return 'drop-rate-common';
    }
}
?>