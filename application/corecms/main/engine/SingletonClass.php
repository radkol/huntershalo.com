<?php

/*
 * @author Radko Lyutskanov
 */
class SingletonClass {
    
    protected $ci = NULL;
    private static $definitons = array();
    
    public function __construct() {
        $this->ci = &get_instance();
    }

    public static function instance() {

        $calledClass = get_called_class();
        
        if (isset(self::$definitons[$calledClass])) {
            return self::$definitons[$calledClass];
        }
        
        self::$definitons[$calledClass] = new $calledClass();
        return self::$definitons[$calledClass];
    }
    
    
}
