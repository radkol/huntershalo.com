<?php

/**
 * Description of WishlistService
 *
 * @author raddy
 */
class WishlistService extends SingletonClass {

    private $cartService;
    private $sessionService;
    private $requestService;
    private $productService;
    private $productType;
    private $customerWishlist;
    private $wishlistProducts = [];
    private $customerService;
    private $wishlistType;

    public function __construct() {
        parent::__construct();
        $this->customerService = CustomerService::instance();
        $this->cartService = CartService::instance();
        $this->productService = productService::instance();
        $this->wishlistType = TypeService::instance()->getType("Wishlist");
        $this->productType = TypeService::instance()->getType("Product");
        $this->sessionService = SessionService::instance();
        $this->requestService = RequestService::instance();
        $this->setCustomerWishlist();
    }
    
    public function getCustomerWishlist() {
        return $this->customerWishlist;
    }
    
    private function setCustomerWishlist() {

        $cc = $this->customerService->getCurrentCustomer();

        if (!$cc) {
            return;
        }

        if ($this->customerWishlist) {
            return;
        }

        $this->customerWishlist = $this->wishlistType->search()->getRecord(["customer" => $cc->id]);
        
        if (!$this->customerWishlist) {
            $this->createWishlist($cc);
        } else {
            $this->wishlistProducts = $this->wishlistType->search()->getRelations("products", $this->customerWishlist);
        }
        
    }
    
    private function createWishlist($customer) {
        $fs = FieldService::instance();
        $fnames = ["customer"];
        $fvalues = [$customer->id];
        $fields = $fs->fillFieldsWithValues($this->wishlistType, $fnames, $fvalues);
        $wishlistId = $this->wishlistType->create($fields);
        $this->customerWishlist = $this->wishlistType->search()->getRecord(["id" => $wishlistId]);
    }
    
    public function productInWishlist($productId) {
        // already in wishlist
        foreach($this->wishlistProducts as $prd) {
            if($prd->id == $productId) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    public function add($productId) {
        
        if($this->productInWishlist($productId)) {
            return;
        }
        $product = $this->productService->getProduct($productId,["id", "price", "name", "category", "collection"]);
        $this->wishlistProducts[] = $product;
        $pIds = UtilityService::instance()->getPropertyArray($this->wishlistProducts, "id");
        //debug($this->wishlistProducts);
        $fields = FieldService::instance()->fillFieldsWithValues($this->wishlistType, ["id","products"], [$this->customerWishlist->id, $pIds]);
        //debug($fields);
        $this->wishlistType->edit($fields);
        
    }
    
    public function remove($productId) {
        if(!$this->productInWishlist($productId)) {
            return;
        }
        $newItems = [];
        foreach($this->getWishlistItems() as $item) {
            if($item->id != $productId) {
                $newItems[] = $item;
            }
        }
        $this->wishlistProducts = null;
        $pIds = UtilityService::instance()->getPropertyArray($newItems, "id");
        $fields = FieldService::instance()->fillFieldsWithValues($this->wishlistType, ["id","products"], [$this->customerWishlist->id, $pIds]);
        $this->wishlistType->edit($fields);
        $this->wishlistProducts = $newItems;
    }
    
    public function moveToCart($productId) {
        $this->cartService->add($productId, WebsiteConstants::PRODUCT_QTY);
        $this->remove($productId);
    }
    
    public function getWishlistItems() {
        return $this->wishlistProducts;
    }

}
