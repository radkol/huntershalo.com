<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Radko Lyutskanov
 */
    
/*
 * Default Controller for Checkout.
 */

class Checkout extends CmsModuleType {
    
    const ERROR_PAGE_URI = "/OrderError";
    const CONFIRMATION_PAGE_URI = "/OrderConfirmation";
    
    private $requestService;
    private $sessionService;
    
    public function __construct() {
        parent::__construct();
        $this->requestService = RequestService::instance();
        $this->sessionService = SessionService::instance();
        $this->ci->load->helper("form");
    }
    public function typeAsString() {
        return "Checkout Module";
    }
    
    public function process($moduleInstance) {
        $data = parent::process($moduleInstance);
        
        $customerService = CustomerService::instance();
        $addressService = AddressService::instance();
        
        $errorPage = $this->errorPage();
        
        if($errorPage) {
           $data["errorContent"] = TypeService::instance()->getSearch("Content")->getRecord(array("name" => "checkouterror-".$this->requestService->getParam("error")));
           $this->view("error", $data);
           return;
        }
        
        $confirmationPage = $this->confirmationPage();
        if($confirmationPage) {
            $data["order"] = SessionService::instance()->getAttribute("order");
            $data["confirmationContent"] = TypeService::instance()->getSearch("Content")->getRecord(array("name" => "checkoutconfirmation"));
            $this->view("confirmation", $data);
            return;
        }
        
        // load default checkout view with steps
        
        // check what step to display.
        $this->setCheckoutState($data);
        
        $data["currentStep"] = $this->getCurrentStep();
        $data["customer"] = $customerService->getCurrentCustomer();
        $customerAddresses = $addressService->getAllCustomerAddresses($data["customer"]);
        
        $data["shippingAddresses"] = $addressService->getFilteredAddresses($customerAddresses, "shipping");
        $data["billingAddresses"] = $addressService->getFilteredAddresses($customerAddresses, "billing");
        
        $data["selectedBillingAddress"] = $addressService->getCurrentAddressWithDefault("billing", $data["billingAddresses"]);
        $data["selectedShippingAddress"] = $addressService->getCurrentAddressWithDefault("shipping", $data["shippingAddresses"]);
        
        $data["selectedShippingDetail"] = CartService::instance()->getCurrentShippingDetail();
        $data["shippingDetails"] = CartService::instance()->getAllShippingDetails();
        
        $this->view("default", $data);
        
    }
    
    private function getFilteredAddresses($all, $type) {
        return array_filter($all, create_function('$address', 'return $address->'.$type.'; '));
    }
    
    private function setCheckoutState(&$data) {
        $this->handleAddress($data, "billing", 2);
        $this->handleAddress($data, "shipping", 3);
        $this->handleShippingDetail($data);
    } 
    
    private function handleAddress(&$data, $type = "billing", $nextStep = 2) {
        
        $customerService = CustomerService::instance();
        $customer = $customerService->getCurrentCustomer();
        // user has clicked on $type section continue button, that means expand step 2 - shipping address.
        
        $upperCaseType = ucfirst($type);
        // user clicks on continue $type button
        if($this->requestService->getParam("continue{$type}address")) {
            
            // check if checkbox use same as billing is checked
            if($this->requestService->getParam("shippingAsBilling")) {
               
                // if it is get the billing address from the session and check if it needs update
                $selectedBilling = $this->sessionService->getAttribute("selectedBillingAddress");
                if($selectedBilling->shippingAddress) {
                    //set the shipping and do nothing else.
                    $this->sessionService->setAttribute("selectedShippingAddress", $selectedBilling);
                } else {
                    // we need to update the billing to become shipping too and reset the session data.
                    AddressService::instance()->setBillingAddressAsShipping($selectedBilling);
                }
                $this->sessionService->setAttribute("currentStep", $nextStep);
                return;
            }
           
            // check if we have valid billing address selected
            $addrId = $this->requestService->getParam("selected{$upperCaseType}");
            $addr = $customerService->findAddressByUid($addrId);
            
            if($addr) {
                $this->sessionService->setAttribute("selected{$upperCaseType}Address", $addr);
                $this->sessionService->setAttribute("currentStep", $nextStep);
            } else {
                $this->sessionService->setAttribute("currentStep", $nextStep - 1);
                $data["{$type}Error"] = true;
            }
            return;
        }
        
        // user clicks on add new {$type} button
        if($this->requestService->getParam("new{$type}address")) {
            $data["addressSection"] = $type;
            setAddressValidation();
            if ($this->ci->form_validation->run() !== FALSE) {
                $newAddressId = $customerService->createAddress($customer->id, "{$type}Address");
                $addr = $customerService->findAddressById($newAddressId);
                $this->sessionService->setAttribute("selected{$upperCaseType}Address", $addr);
                $this->sessionService->setAttribute("currentStep", $nextStep);
                $data["new{$upperCaseType}Added"] = TRUE;
            } else {
                $this->sessionService->setAttribute("currentStep", $nextStep - 1);
            }
        }
    }
    
    private function handleShippingDetail(&$data) {
        
        if($this->requestService->getParam("continueshippingdetail")) {
            $this->sessionService->setAttribute("shippingDetail", CartService::instance()->getShippingDetail($this->requestService->getParam("selectedShippingDetail")));
            $this->sessionService->setAttribute("currentStep", 4);
        }
            
    }
    
    
    private function getCurrentStep() {
        $sessionService = SessionService::instance();
        
        $currentStep = $sessionService->getAttribute("currentStep");
        if(!$currentStep) {
            $currentStep = 1;
        }
        return $currentStep;
    }
     
    private function errorPage() {
        $requestService = RequestService::instance();
        $currentUri = $requestService->getUri();
        
        if($currentUri == $this->getItem("page")->url.self::ERROR_PAGE_URI) {
            return true;
        }
        
        return false;
    }
    
    private function confirmationPage() {
        $requestService = RequestService::instance();
        $currentUri = $requestService->getUri();
        
        if($currentUri == $this->getItem("page")->url.self::CONFIRMATION_PAGE_URI) {
            return true;
        }
        
        return false;
    }
    
}
