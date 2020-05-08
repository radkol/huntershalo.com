<?php
/**
 * Description of ProcessCartOperations
 *
 * @author raddy
 */
class ProcessCartOperations implements CmsInterceptor {

    public function priority() {
        return 2;
    }

    public function run() {

        $requestService = RequestService::instance();
        $cartService = CartService::instance();
        $customerService = CustomerService::instance();
        
        // -- ADD / EDIT / REMOVE FROM CART --
        $cartAction = $requestService->getParam("action");
        $id = $requestService->getParam("id");

        if ($cartAction && $id) {
            $qty = $requestService->getParam("qty");
            switch ($cartAction) {
                case "add" :
                    if ($qty > 0) {
                        $cartService->add($id, 1);
                    }
                    break;
                case "update" :
                    if ($qty > 0) {
                        $cartService->update($id, 1);
                    }
                    break;
                case "remove" :
                    $cartService->remove($id);
                    break;
            }
            return;
        }
        
        // -- ADD / EDIT / REMOVE FROM WISHLIST --
        $wsAction = $requestService->getParam("wlaction");
        if($wsAction && $id) {
            $cc = $customerService->getCurrentCustomer();
            if(!$cc) {
                return;
            }
            
            $wishlistService = WishlistService::instance();
            switch ($wsAction) {
                case "add" :
                    $wishlistService->add($id);
                    break;
                case "remove" :
                    $wishlistService->remove($id);
                    break;
                case "movetocart" :
                    $wishlistService->moveToCart($id);
                    break;
            }
            return;
        }
    }

//put your code here
}
