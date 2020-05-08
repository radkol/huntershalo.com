<?php

class CartService extends SingletonClass {
    
    private $cart;
    private $productType;
    private $resourceService;
    private $sessionService;
    private $typeService;
    private $productService;
    private $shippingDetails;
    
    public function __construct() {
        parent::__construct();
        $this->ci->load->library('cart');
        $this->cart = $this->ci->cart;
        $this->typeService = TypeService::instance();
        $this->productService = ProductService::instance();
        $this->productType = TypeService::instance()->getType("Product");
        $this->sessionService = SessionService::instance();
        $this->shippingDetails = $this->typeService->getSearch("ShippingDetail")->getRecords();
        if(!$this->sessionService->getAttribute("shippingDetail")) {
            $this->sessionService->setAttribute("shippingDetail", $this->getDefaultShippingDetail());
        }
        
        $this->resourceService = ResourceService::instance();
    }
    
    
    public function getDefaultShippingDetail() {
        foreach($this->shippingDetails as $d) {
            if($d->defaultShipping) {
                return $d;
            }
        }
        return NULL;
    }
    
    public function getShippingDetail($Id) {
        foreach($this->shippingDetails as $d) {
            if($d->id == $Id) {
                return $d;
            }
        }
        return NULL;
    }
    
    public function getAllShippingDetails() {
        return $this->typeService->getSearch("ShippingDetail")->getRecords();
    }
    
    public function getCurrentShippingDetail() {
        return $this->sessionService->getAttribute("shippingDetail");
    }
    
    public function add($productId, $quantity) {
        
        if($this->productInCart($productId)) {
            return;
        }
        
        $product = $this->productService->getProduct($productId,["id", "price", "name", "availableToRent", "category", "collection"]);
        if(!$product) {
            return;
        }
        
        // get product image
        $images = $this->productType->search()->getFileUploadsForObject($product, "images", 1);
        
        //create 
        $data = array(
               'id'      => $product->id,
               'qty'     => $quantity,
               'price'   => $product->price,
               'name'    => $product->name,
               'forRent' => $product->availableToRent,
               'options' => array("category" => $product->category, "collection" => $product->collection, 'thumb' => (count($images) > 0 ? $this->resourceService->getAssetUrl($images[0], 400, 600) : NULL))
            );

        $this->cart->insert($data);
    }
    
    public function productInCart($productId, $cartType = 'PURCHASE') {

        $products = $cartType == 'RENTAL' ? $this->getRentalProducts() : $this->getPurchaseProducts();

        foreach($products as $item) {
            if($item["id"] == $productId) {
                return true;
            }
        }

        return false;
    }
    
    public function update($rowId, $quantity) {
        $data = ["rowid" => $rowId, "qty" => $quantity];
        $this->cart->update($data);
    }
    
    public function remove($rowId) {
        $data = ["rowid" => $rowId, "qty" => 0];
        $this->cart->update($data);
    }
    
    public function getTotal() {
        return $this->cart->total();
    }

    public function getRentalProducts() {
        $rentals = [];
        foreach($this->getCart() as $item) {
            if($item['forRent']) {
                $rentals[] = $item;
            }
        }
        return $rentals;
    }

    public function getPurchaseProducts() {
        $purchase = [];
        foreach($this->getCart() as $item) {
            if(!$item['forRent']) {
                $purchase[] = $item;
            }
        }
        return $purchase;
    }

    public function getItemsCount() {
        return $this->cart->total_items();
    }
    
    public function getCart() {
        return $this->cart->contents();
    }
    
    public function clear() {
        $this->cart->destroy();
    }
    
}

