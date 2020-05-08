<?php

/**
 * Description of TransactionService
 *
 * @author raddy
 */
class TransactionService extends SingletonClass {
    
    public function beginTransaction() {
        $this->ci->db->trans_start();
    }
    
    public function endTransaction() {
        $this->ci->db->trans_complete();
    }
    
}
