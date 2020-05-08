<?php

class CollectionService extends SingletonClass {
    
    private $collections = [];
    
    public function __construct() {
        parent::__construct();
        $collectionType = TypeService::instance()->getType("Collection");
        $records = $collectionType->search()->getRecords();
        foreach($records as $collection) {
            $this->collections[$collection->id] = $collection;
        }
    }
    
    public function getCollection($id) {
        if(isset($this->collections[$id])) {
            return $this->collections[$id];
        }
        return null;
    }
    
    public function getCollections() {
        return $this->collections;
    }
    
}

