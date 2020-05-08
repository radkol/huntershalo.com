<?php

/*
 * @author Radko Lyutskanov
 */

class CmsInterceptorProcessor extends SingletonClass {
    
    public function process($interceptorsFolderName) {
        
        $controller = &get_instance();
        $controller->load->helper("file");
        $interceptorFolder = getInterceptorsFolder($interceptorsFolderName);
        $interceptorFilenames = get_filenames($interceptorFolder);
        
        $interceptors = array();
        if($interceptorFilenames) {
            foreach ($interceptorFilenames as $interceptorName) {
                //load interceptor class
                require $interceptorFolder . $interceptorName;
                $className = substr($interceptorName,0,  strpos($interceptorName, "."));
                $interceptors[] = new $className();
            }
        }
        // sort them by priority
        usort($interceptors, array("CmsInterceptorProcessor","interceptorComparator"));
        
        foreach($interceptors as $i) {
            $i->run();
        }
    }
    
    
    public static function interceptorComparator($i1, $i2) {
        if($i1->priority() > $i2->priority()) {
            return 1;
        }
        return -1;
    }
    
}