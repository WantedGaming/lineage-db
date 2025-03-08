<?php
class Skill {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all skills with pagination
    public function getSkills($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT * FROM skills";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['name']) && !empty($filters['name'])) {
                $name = $this->db->escape($filters['name']);
                $sql .= " AND (name LIKE '%$name%' OR desc_en LIKE '%$name%')";
            }
            
            if (isset($filters['classType']) && !empty($filters['classType']) && $filters['classType'] != 'none') {
                $classType = $this->db->escape($filters['classType']);
                $sql .= " AND classType = '$classType'";
            }
            
            if (isset($filters['type']) && !empty($filters['type']) && $filters['type'] != 'NONE') {
                $type = $this->db->escape($filters['type']);
                $sql .= " AND type = '$type'";
            }
            
            if (isset($filters['grade']) && !empty($filters['grade'])) {
                $grade = $this->db->escape($filters['grade']);
                $sql .= " AND grade = '$grade'";
            }
        }
        
        $sql .= " ORDER BY skill_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get single skill by ID
    public function getSkillById($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM skills WHERE skill_id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get passive skills
    public function getPassiveSkills($page = 1, $perPage = ITEMS_PER_PAGE, $filters = []) {
        $sql = "SELECT * FROM skills_passive";
        
        // Apply filters if any
        if (!empty($filters)) {
            $sql .= " WHERE 1=1";
            
            if (isset($filters['name']) && !empty($filters['name'])) {
                $name = $this->db->escape($filters['name']);
                $sql .= " AND (name LIKE '%$name%' OR desc_en LIKE '%$name%')";
            }
            
            if (isset($filters['class_type']) && !empty($filters['class_type']) && $filters['class_type'] != 'none') {
                $classType = $this->db->escape($filters['class_type']);
                $sql .= " AND class_type = '$classType'";
            }
            
            if (isset($filters['grade']) && !empty($filters['grade'])) {
                $grade = $this->db->escape($filters['grade']);
                $sql .= " AND grade = '$grade'";
            }
        }
        
        $sql .= " ORDER BY passive_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get single passive skill by ID
    public function getPassiveSkillById($id) {
        $id = (int) $id;
        $sql = "SELECT * FROM skills_passive WHERE passive_id = $id";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Get skill information including icon/UI data
    public function getSkillInfo($skillId) {
        $skillId = (int) $skillId;
        $sql = "SELECT * FROM skills_info WHERE skillId = $skillId";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Search skills
    public function searchSkills($term, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $term = $this->db->escape($term);
        
        // Search active skills
        $sqlActive = "SELECT skill_id as id, name, desc_en, 'active' as skill_type, classType, grade 
                      FROM skills 
                      WHERE name LIKE '%$term%' OR desc_en LIKE '%$term%' OR desc_kr LIKE '%$term%'";
                      
        // Search passive skills
        $sqlPassive = "SELECT passive_id as id, name, desc_en, 'passive' as skill_type, class_type as classType, grade 
                      FROM skills_passive 
                      WHERE name LIKE '%$term%' OR desc_en LIKE '%$term%' OR desc_kr LIKE '%$term%'";
                      
        // Combine results
        $sql = "($sqlActive) UNION ($sqlPassive) ORDER BY name";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get class-specific skills
    public function getClassSkills($classType, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $classType = $this->db->escape($classType);
        
        // Get active skills for class
        $sql = "SELECT * FROM skills 
                WHERE classType = '$classType' OR classType = 'normal' 
                ORDER BY skill_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get class-specific passive skills
    public function getClassPassiveSkills($classType, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $classType = $this->db->escape($classType);
        
        // Get passive skills for class
        $sql = "SELECT * FROM skills_passive 
                WHERE class_type = '$classType' OR class_type = 'normal' 
                ORDER BY passive_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
    
    // Get skill count by class
    public function getSkillCountByClass() {
        $sql = "SELECT classType, COUNT(*) as count FROM skills 
                WHERE classType != 'none' 
                GROUP BY classType ORDER BY count DESC";
                
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['classType']] = $row['count'];
            }
        }
        
        return $counts;
    }
    
    // Get passive skill count by class
    public function getPassiveSkillCountByClass() {
        $sql = "SELECT class_type, COUNT(*) as count FROM skills_passive 
                WHERE class_type != 'none' 
                GROUP BY class_type ORDER BY count DESC";
                
        $result = $this->db->query($sql);
        
        $counts = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $counts[$row['class_type']] = $row['count'];
            }
        }
        
        return $counts;
    }
    
    // Get skills by type
    public function getSkillsByType($type, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $type = $this->db->escape($type);
        
        $sql = "SELECT * FROM skills WHERE type = '$type' ORDER BY skill_id ASC";
        
        return $this->db->paginate($sql, $page, $perPage);
    }
}