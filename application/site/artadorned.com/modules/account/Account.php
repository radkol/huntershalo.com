<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */

/*
 * Default Controller for Account.
 */

class Account extends CmsModuleType {

    const REGISTER_URI = "/Register";
    const ORDERHISTORY_URI = "/OrderHistory";
    const SHIPPINGADDRESSES_URI = "/ShippingAddresses";
    const BILLINGADDRESSES_URI = "/BillingAddresses";
    
    public function typeAsString() {
        return "Account Module";
    }

    public function process($moduleInstance) {
        $data = parent::process($moduleInstance);
        $this->ci->load->helper("form");

        $customerService = CustomerService::instance();
        $addressService = AddressService::instance();
        $requestService = RequestService::instance();
        
        if (!$customerService->hasCurrentCustomer()) {

            //if we don't have an account but we want to register.
            if ($this->checkPage(WebsiteConstants::REGISTER_URI)) {
                $data["uriPart"] = WebsiteConstants::REGISTER_URI;
                $this->view("registerform", $data);
                
            } else if ($this->checkPage(WebsiteConstants::FORGOTTENPASSWORD_URI)) {
                $data["uriPart"] = WebsiteConstants::FORGOTTENPASSWORD_URI;
                $this->view("forgottenpassword", $data);
                
            } else if ($this->checkPage(WebsiteConstants::SETNEWPASSWORD_URI)) {
                $data["uriPart"] = WebsiteConstants::SETNEWPASSWORD_URI;
                $this->view("setnewpassword", $data);
                
            } else {
                $data["uriPart"] = "";
                $data["forgotPasswordUri"] = WebsiteConstants::FORGOTTENPASSWORD_URI;
                $this->view("loginform", $data);
                
            }
            return;
        }

        $linkTitleToUriMap = [
            "ordersLink" => WebsiteConstants::ORDERHISTORY_URI,
            "billingAddressLink" => WebsiteConstants::BILLINGADDRESSES_URI,
            "shippingAddressLink" => WebsiteConstants::SHIPPINGADDRESSES_URI,
            "wishlistLink" => WebsiteConstants::WISHLIST_URI,
            "accountDetailsLink" => ""
        ];

        $data["navigationMap"] = $linkTitleToUriMap;
        $data["currentUri"] = RequestService::instance()->getUri();

        $viewName = "default";
        
        // customer is logged in so open requested page.
        
        if ($this->checkPage(WebsiteConstants::ORDERHISTORY_URI)) {
            $orderType = TypeService::instance()->getType("Order");
            $prodType = TypeService::instance()->getType("Product");
            if($requestService->getParam("orderuid")) {
                $data["order"] = $orderType->search()->getRecord(["uid" => $requestService->getParam("orderuid")]);
                if($data["order"]) {
                  $data["orderEntries"] = $orderType->search()->getRelations("entries", $data["order"]);
                  $utilityService = UtilityService::instance();
                  $prodIds = $utilityService->getPropertyArray($data["orderEntries"], "productId");
                  $data["productImages"] = $prodType->search()->getFileUploadsForObjects($prodIds, "images", 1);
                  $data["deliveryMethod"] = TypeService::instance()->getSearch("ShippingDetail")->getRecord(["id" => $data["order"]->shippingDetail]);
                  $viewName = "orderdetail";
                }
            } else {
                // get orders
                $data["orders"] = $orderType->search()->getWhereRecords(["customer" => $customerService->getCurrentCustomer()->id], "dateCreated", "desc");
                $viewName = "orderhistory";
            }
        } else if ($this->checkPage(WebsiteConstants::SHIPPINGADDRESSES_URI)) {
            $data["uriString"] = WebsiteConstants::SHIPPINGADDRESSES_URI;
            $customerAddresses = $addressService->getAllCustomerAddresses($customerService->getCurrentCustomer());
            $data["shippingAddresses"] = $addressService->getFilteredAddresses($customerAddresses, "shipping");
            $data["defaultAddress"] = $addressService->getDefaultAddress($data["shippingAddresses"], "shipping");
            $viewName = "shippingaddresses";
        } else if ($this->checkPage(WebsiteConstants::BILLINGADDRESSES_URI)) {
            $data["uriString"] = WebsiteConstants::BILLINGADDRESSES_URI;
            $customerAddresses = $addressService->getAllCustomerAddresses($customerService->getCurrentCustomer());
            $data["billingAddresses"] = $addressService->getFilteredAddresses($customerAddresses, "billing");
            $data["defaultAddress"] = $addressService->getDefaultAddress($data["billingAddresses"], "billing");
            $viewName = "billingaddresses";
        } else if ($this->checkPage(WebsiteConstants::WISHLIST_URI)) {
            $wishlistService = WishlistService::instance();
            $productType = TypeService::instance()->getType("Product");
            $data["uriString"] = WebsiteConstants::WISHLIST_URI;
            $data["wishlist"] = $wishlistService->getCustomerWishlist();
            $data["wishlistItems"] = $wishlistService->getWishlistItems();
            $data["wishlistItemsImages"] = $productType->search()->getFileUploadsForObjects($data["wishlistItems"], "images", 1);
            $viewName = "wishlist";
        } else if ($this->checkPage(WebsiteConstants::FORGOTTENPASSWORD_URI)) {
            $wishlistService = WishlistService::instance();
            $productType = TypeService::instance()->getType("Product");
            $data["uriString"] = WebsiteConstants::WISHLIST_URI;
            $data["wishlist"] = $wishlistService->getCustomerWishlist();
            $data["wishlistItems"] = $wishlistService->getWishlistItems();
            $data["wishlistItemsImages"] = $productType->search()->getFileUploadsForObjects($data["wishlistItems"], "images", 1);
            $viewName = "wishlist";
        } else {
            //check if user has submitted the form.
            if (isFormSubmitted("updatedetailssubmit")) {
                $data["formSubmitted"] = true;
                $this->setUpdateDetailsValidation();
                if ($this->ci->form_validation->run() !== FALSE) {
                    $customerService->updateCurrentCustomer();
                }
            }
            $data["formSubmitted"] = false;
            
        }
        
        //retrieve current customer
        $data["customer"] = $customerService->getCurrentCustomer();
        
        $this->view($viewName, $data);
    }



//        // check for register activity
//        $registerForm = $requestService->getParam("registerform");
//        $registerSubmit = $requestService->getParam("registersubmit");
//        if($registerForm && $registerSubmit) {
//            $this->setRegisterValidation($customerType);
//            if ($controller->form_validation->run() !== FALSE) {
//                
//                //register customer
//                $customerService->createCustomer();
//                
//                // get created customer
//                $customer = $customerService->findCustomer($requestService->getParam("email"));
//                $customerService->setCurrentCustomer($customer);
//                
//                // do we have to redirect to somewhere ?, if not, display default account page.
//                $redirectPage = $sessionService->getAttribute("redirectPage");
//                if($redirectPage) {
//                    $sessionService->removeAttribute("redirectPage");
//                    redirect($redirectPage->url);
//                } else {
//                    redirect($accountPage->url);
//                }
//            }
//            return;
//        }

    private function checkPage($uri) {
        $requestService = RequestService::instance();
        $currentUri = $requestService->getUri();

        if ($currentUri == $this->getItem("page")->url . $uri) {
            return true;
        }

        return false;
    }
    
    
    private function setForgottenPasswordValidation() {
        $this->ci->load->library('form_validation');
        $this->ci->form_validation->set_rules("firstName", "First Name", "required|min_length[3]");
    }
    
    private function setUpdateDetailsValidation() {
        $this->ci->load->library('form_validation');
        $this->ci->form_validation->set_rules("password", "Password", "min_length[6]|matches[repeatpassword]");
        $this->ci->form_validation->set_rules("repeatpassword", "Password Repeat");
        $this->ci->form_validation->set_rules("firstName", "First Name", "required|min_length[3]");
        $this->ci->form_validation->set_rules("lastName", "Last Name", "required|min_length[3]");
    }

}
