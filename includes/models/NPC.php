<?php
class NPC {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all NPCs with pagination
    public function getNPCs($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT * FROM npc";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['name']) && !empty($filters['name'])) {
                $name = $this->db->escape($filters['name']);
                $sql .= " AND desc_en LIKE '%$name%'";
            }
            
            if (isset($filters['level_min']) && is_numeric($filters['level_min'])) {
                $levelMin = (int) $filters['level_min'];
                $sql .= " AND lvl >= $levelMin";
            }
            
            if (isset($filters['level_max']) && is_numeric($filters['level_max'])) {
                $levelMax = (int) $filters['level_max'];
                $sql .= " AND lvl <= $levelMax";
            }
            
            if (isset($filters['is_boss']) && $filters['is_boss'] == 'true') {
                $sql .= " AND is_bossmonster = 'true'";
            }
        }
        
        $sql .= " ORDER BY lvl ASC, npcid ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get single NPC by ID
    public function getNPCById($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM npc WHERE npcid = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get boss NPCs
    public function getBossNPCs($page = 1, $perPage = ITEMS_PER_PAGE) {
        $sql = "SELECT * FROM npc WHERE is_bossmonster = 'true' ORDER BY lvl DESC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get NPC drops
    public function getNPCDrops($npcId) {
        $npcId = (int) $npcId;
        $sql = "SELECT d.*, 
                CASE 
                    WHEN w.desc_en IS NOT NULL THEN w.desc_en
                    WHEN a.desc_en IS NOT NULL THEN a.desc_en
                    WHEN e.desc_en IS NOT NULL THEN e.desc_en
                    ELSE 'Unknown Item'
                END as item_name,
                CASE 
                    WHEN w.item_id IS NOT NULL THEN 'weapon'
                    WHEN a.item_id IS NOT NULL THEN 'armor'
                    WHEN e.item_id IS NOT NULL THEN 'etcitem'
                    ELSE 'unknown'
                END as item_type
                FROM droplist d 
                LEFT JOIN weapon w ON d.itemId = w.item_id 
                LEFT JOIN armor a ON d.itemId = a.item_id 
                LEFT JOIN etcitem e ON d.itemId = e.item_id 
                WHERE d.mobId = $npcId 
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
    
    // Get NPC spawn locations
    public function getNPCSpawns($npcId) {
        $npcId = (int) $npcId;
        $sql = "SELECT s.*, m.locationname as map_name 
                FROM spawnlist s 
                LEFT JOIN mapids m ON s.mapid = m.mapid 
                WHERE s.npc_templateid = $npcId 
                ORDER BY s.mapid ASC";
                
        $result = $this->db->query($sql);
        
        $spawns = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $spawns[] = $row;
            }
        }
        
        return $spawns;
    }
    
    // Get NPC skills
    public function getNPCSkills($npcId) {
        $npcId = (int) $npcId;
        $sql = "SELECT * FROM mobskill WHERE mobid = $npcId ORDER BY actNo ASC";
        
        $result = $this->db->query($sql);
        
        $skills = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $skills[] = $row;
            }
        }
        
        return $skills;
    }
    
    // Search NPCs
    public function searchNPCs($term, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $term = $this->db->escape($term);
        
        $sql = "SELECT * FROM npc 
                WHERE desc_en LIKE '%$term%' 
                OR desc_kr LIKE '%$term%' 
                OR note LIKE '%$term%' 
                ORDER BY lvl ASC, npcid ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get NPC count by level range
    public function getNPCCountByLevelRange() {
        $sql = "SELECT 
                CASE 
                    WHEN lvl BETWEEN 1 AND 10 THEN '1-10'
                    WHEN lvl BETWEEN 11 AND 20 THEN '11-20'
                    WHEN lvl BETWEEN 21 AND 30 THEN '21-30'
                    WHEN lvl BETWEEN 31 AND 40 THEN '31-40'
                    WHEN lvl BETWEEN 41 AND 50 THEN '41-50'
                    WHEN lvl BETWEEN 51 AND 60 THEN '51-60'
                    WHEN lvl BETWEEN 61 AND 70 THEN '61-70'
                    WHEN lvl BETWEEN 71 AND 80 THEN '71-80'
                    WHEN lvl BETWEEN 81 AND 90 THEN '81-90'
                    WHEN lvl > 90 THEN '91+'
                END as level_range,
                COUNT(*) as count
                FROM npc
                GROUP BY level_range
                ORDER BY MIN(lvl)";
                
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['level_range']] = $row['count'];
            }
        }
        
        return $counts;
    }
    
    // Get boss spawn times
    public function getBossSpawnTimes() {
        $sql = "SELECT b.*, n.desc_en as boss_name, n.lvl as level 
                FROM spawnlist_boss b 
                JOIN npc n ON b.npcid = n.npcid 
                WHERE b.isYN = 'true' 
                ORDER BY n.lvl DESC";
                
        $result = $this->db->query($sql);
        
        $bossSpawns = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $bossSpawns[] = $row;
            }
        }
        
        return $bossSpawns;
    }
}
?>
