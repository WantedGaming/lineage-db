<?php
class Spawn {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all spawns with pagination
    public function getSpawns($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT s.*, n.desc_en as npc_name, n.lvl as npc_level, m.locationname as map_name 
                FROM spawnlist s 
                JOIN npc n ON s.npc_templateid = n.npcid 
                JOIN mapids m ON s.mapid = m.mapid";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['map_id']) && !empty($filters['map_id'])) {
                $mapId = (int) $filters['map_id'];
                $sql .= " AND s.mapid = $mapId";
            }
            
            if (isset($filters['npc_name']) && !empty($filters['npc_name'])) {
                $npcName = $this->db->escape($filters['npc_name']);
                $sql .= " AND n.desc_en LIKE '%$npcName%'";
            }
            
            if (isset($filters['min_level']) && is_numeric($filters['min_level'])) {
                $minLevel = (int) $filters['min_level'];
                $sql .= " AND n.lvl >= $minLevel";
            }
            
            if (isset($filters['max_level']) && is_numeric($filters['max_level'])) {
                $maxLevel = (int) $filters['max_level'];
                $sql .= " AND n.lvl <= $maxLevel";
            }
        }
        
        $sql .= " ORDER BY s.mapid ASC, n.lvl ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get boss spawns
    public function getBossSpawns($page = 1, $perPage = ITEMS_PER_PAGE) {
        $sql = "SELECT sb.*, n.desc_en as boss_name, n.lvl as boss_level, m.locationname as map_name 
                FROM spawnlist_boss sb 
                JOIN npc n ON sb.npcid = n.npcid 
                LEFT JOIN mapids m ON sb.spawnMapId = m.mapid 
                ORDER BY n.lvl DESC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get spawns by NPC ID
    public function getSpawnsByNpcId($npcId, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $npcId = (int) $npcId;
        
        $sql = "SELECT s.*, m.locationname as map_name 
                FROM spawnlist s 
                JOIN mapids m ON s.mapid = m.mapid 
                WHERE s.npc_templateid = $npcId 
                ORDER BY s.mapid ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get spawns by map ID
    public function getSpawnsByMapId($mapId, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $mapId = (int) $mapId;
        
        $sql = "SELECT s.*, n.desc_en as npc_name, n.lvl as npc_level 
                FROM spawnlist s 
                JOIN npc n ON s.npc_templateid = n.npcid 
                WHERE s.mapid = $mapId 
                ORDER BY n.lvl ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get maps with spawns
    public function getMapsWithSpawns() {
        $sql = "SELECT DISTINCT m.mapid, m.locationname 
                FROM spawnlist s 
                JOIN mapids m ON s.mapid = m.mapid 
                ORDER BY m.locationname ASC";
        
        $result = $this->db->query($sql);
        
        $maps = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $maps[] = $row;
            }
        }
        
        return $maps;
    }
    
    // Get boss spawn details
    public function getBossSpawnById($id) {
        $id = (int) $id;
        
        $sql = "SELECT sb.*, n.desc_en as boss_name, n.lvl as boss_level, m.locationname as map_name 
                FROM spawnlist_boss sb 
                JOIN npc n ON sb.npcid = n.npcid 
                LEFT JOIN mapids m ON sb.spawnMapId = m.mapid 
                WHERE sb.id = $id";
        
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get spawn count by map
    public function getSpawnCountByMap() {
        $sql = "SELECT m.locationname, COUNT(*) as count 
                FROM spawnlist s 
                JOIN mapids m ON s.mapid = m.mapid 
                GROUP BY s.mapid 
                ORDER BY count DESC";
        
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['locationname']] = $row['count'];
            }
        }
        
        return $counts;
    }
    
    // Get NPC count by map
    public function getNpcCountByMap() {
        $sql = "SELECT m.locationname, SUM(s.count) as total_npcs 
                FROM spawnlist s 
                JOIN mapids m ON s.mapid = m.mapid 
                GROUP BY s.mapid 
                ORDER BY total_npcs DESC";
        
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['locationname']] = $row['total_npcs'];
            }
        }
        
        return $counts;
    }
    
    // Get map information
    public function getMapInfo($mapId) {
        $mapId = (int) $mapId;
        
        $sql = "SELECT * FROM mapids WHERE mapid = $mapId";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get all maps
    public function getAllMaps() {
        $sql = "SELECT * FROM mapids ORDER BY locationname ASC";
        $result = $this->db->query($sql);
        
        $maps = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $maps[] = $row;
            }
        }
        
        return $maps;
    }
}
?>