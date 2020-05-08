<?php

class CMS_Config extends CI_Config {
    
    public function __construct() {
        parent::__construct();
        $this->_config_paths = array(APPPATH,APPPATH.'site/');
    }
}

