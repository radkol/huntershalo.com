<?php

class DatabaseService extends SingletonClass {
    
    private function db() {
        return $this->ci->generic_model;
    }
    
    public function tableExists($tablename, $prefix = "cmscore") {
        $query  = $this->db()->query("SHOW TABLES LIKE '{$tablename}'");
        return $query->num_rows() == 1;
    }
    
    
    
}
