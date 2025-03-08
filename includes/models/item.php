<?php
class Item {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all weapons with pagination
    public function getWeapons($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT * FROM weapon";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['name']) && !empty($filters['name'])) {
                $name = $this->db->escape($filters['name']);
                $sql .= " AND desc_en LIKE '%$name%'";
            }
            
            if (isset($filters['grade']) && !empty($filters['grade'])) {
                $grade = $this->db->escape($filters['grade']);
                $sql .= " AND itemGrade = '$grade'";
            }
            
            if (isset($filters['type']) && !empty($filters['type'])) {
                $type = $this->db->escape($filters['type']);
                $sql .= " AND type = '$type'";
            }
        }
        
        $sql .= " ORDER BY item_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get single weapon by ID
    public function getWeaponById($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM weapon WHERE item_id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get all armors with pagination
    public function getArmors($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT * FROM armor";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['name']) && !empty($filters['name'])) {
                $name = $this->db->escape($filters['name']);
                $sql .= " AND desc_en LIKE '%$name%'";
            }
            
            if (isset($filters['grade']) && !empty($filters['grade'])) {
                $grade = $this->db->escape($filters['grade']);
                $sql .= " AND itemGrade = '$grade'";
            }
            
            if (isset($filters['type']) && !empty($filters['type'])) {
                $type = $this->db->escape($filters['type']);
                $sql .= " AND type = '$type'";
            }
        }
        
        $sql .= " ORDER BY item_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get single armor by ID
    public function getArmorById($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM armor WHERE item_id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get all etc items with pagination
    public function getEtcItems($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT * FROM etcitem";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['name']) && !empty($filters['name'])) {
                $name = $this->db->escape($filters['name']);
                $sql .= " AND desc_en LIKE '%$name%'";
            }
            
            if (isset($filters['item_type']) && !empty($filters['item_type'])) {
                $itemType = $this->db->escape($filters['item_type']);
                $sql .= " AND item_type = '$itemType'";
            }
        }
        
        $sql .= " ORDER BY item_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get single etc item by ID
    public function getEtcItemById($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM etcitem WHERE item_id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get item drop locations
    public function getItemDrops($itemId) {
        $itemId = (int) $itemId;
        $sql = "SELECT d.*, n.desc_en AS npc_name, n.lvl 
                FROM droplist d 
                JOIN npc n ON d.mobId = n.npcid 
                WHERE d.itemId = $itemId 
                ORDER BY d.chance DESC";
                
        $result = $this->db->query($sql);
        
        $drops = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $drops[] = $row;
            }
        }
        
        return $drops;
    }
    
    // Search all items
    public function searchItems($term, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $term = $this->db->escape($term);
        
        // Search in weapons
        $sqlWeapons = "SELECT item_id, desc_en, 'weapon' as item_type, itemGrade, type 
                      FROM weapon 
                      WHERE desc_en LIKE '%$term%' OR desc_kr LIKE '%$term%'";
                      
        // Search in armors
        $sqlArmors = "SELECT item_id, desc_en, 'armor' as item_type, itemGrade, type 
                     FROM armor 
                     WHERE desc_en LIKE '%$term%' OR desc_kr LIKE '%$term%'";
                     
        // Search in etc items
        $sqlEtc = "SELECT item_id, desc_en, 'etcitem' as item_type, itemGrade, item_type as type 
                  FROM etcitem 
                  WHERE desc_en LIKE '%$term%' OR desc_kr LIKE '%$term%'";
                  
        // Combine results
        $sql = "($sqlWeapons) UNION ($sqlArmors) UNION ($sqlEtc) ORDER BY desc_en";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get possible weapon enchant effects
    public function getWeaponEnchantEffects($weaponId) {
        $weaponId = (int) $weaponId;
        $sql = "SELECT * FROM weapon_skill WHERE weapon_id = $weaponId";
        
        $result = $this->db->query($sql);
        
        $effects = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $effects[] = $row;
            }
        }
        
        return $effects;
    }
    
    // Get weapon type counts for statistics
    public function getWeaponTypeCounts() {
        $sql = "SELECT type, COUNT(*) as count FROM weapon GROUP BY type ORDER BY count DESC";
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['type']] = $row['count'];
            }
        }
        
        return $counts;
    }
    
    // Get armor type counts for statistics
    public function getArmorTypeCounts() {
        $sql = "SELECT type, COUNT(*) as count FROM armor GROUP BY type ORDER BY count DESC";
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['type']] = $row['count'];
            }
        }
        
        return $counts;
    }
    
    // Get etc item type counts for statistics
    public function getEtcItemTypeCounts() {
        $sql = "SELECT item_type, COUNT(*) as count FROM etcitem GROUP BY item_type ORDER BY count DESC";
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['item_type']] = $row['count'];
            }
        }
        
        return $counts;
    }
}
?>
