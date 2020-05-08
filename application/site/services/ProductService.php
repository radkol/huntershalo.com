<?php

/**
 * Description of ProductService
 *
 * @author raddy
 */
class ProductService extends SingletonClass {
    
    private $productType;
    public function __construct() {
        parent::__construct();
        $this->productType = TypeService::instance()->getType("Product");
    }

    public function getProduct($productId, $columnSet = []) {
        if(empty($columnSet)) {
            return $this->productType->search()->getRecord(array("id" => $productId));
        } else {
            return $this->productType->search()->getRecordColumnSet($columnSet, array("id" => $productId));
        }
    }
}
