<?php

class AddressService extends SingletonClass {
    
    const KEY_SHIPPINGADDRESS = "selectedShippingAddress";
    const KEY_BILLINGADDRESS = "selectedBillingAddress";
    
    const ADDR_SHIPPING_PROPERTY = "shippingAddress";
    const ADDR_BILLING_PROPERTY = "billingAddress";
    
    private $sessionService;
    private $addressType;
    private $customerService;
            
    public function __construct() {
        parent::__construct();
        $this->sessionService = SessionService::instance();
        $this->addressType = TypeService::instance()->getType("Address");
        $this->customerService = CustomerService::instance();
    }
    
    public function setBillingAddressAsShipping($address) {
        
        $address->shippingAddress = 1;
        // update both objects
        
        $this->setCurrentBillingAddress($address);
        $this->setCurrentShippingAddress($address);
        
        // persist the change.
        $addressType = TypeService::instance()->getType("Address");
        $fields = FieldService::instance()->fillFieldsWithValues($addressType,["id","shippingAddress"],[$address->id, 1]);
        $addressType->edit($fields);
    }
    
    public function setCurrentShippingAddress($addr) {
        $this->sessionService->setAttribute(self::KEY_SHIPPINGADDRESS, $addr);
    }
    
    public function setCurrentBillingAddress($addr) {
        $this->sessionService->setAttribute(self::KEY_BILLINGADDRESS, $addr);
    }
    
    public function getCurrentShippingAddress() {
        return $this->sessionService->getAttribute(self::KEY_SHIPPINGADDRESS);
    }
    
    public function getCurrentBillingAddress() {
        return $this->sessionService->getAttribute(self::KEY_BILLINGADDRESS);
    }
    
    
    /**
     * Find Address By Id
     * @param type $addressId
     * @return type
     */
    public function findAddressById($addressId) {
        return $this->addressType->search()->getRecord(array("id" => $addressId));
    }
    
    public function findAddresses($filter) {
        return $this->addressType->search()->getWhereRecords($filter);
    }
    
    /**
     * Try to delete address of specific type.
     * If the address serve as both billing and shipping, just set the relevant part
     * to 0.
     * If it is not, delete it.
     * @param type $addrId
     * @param type $type
     */
    public function deleteAddress($addrId, $type) {
        
        $addr = $this->findAddressById($addrId);
        $addrProperty = $type."Address";
        $addrFunc = "getCurrent".ucfirst($type)."Address";
        $addrSetFunc = "setCurrent".ucfirst($type)."Address";
        
        if($addr && $addr->shippingAddress && $addr->billingAddress) {
            $fields = FieldService::instance()->fillFieldsWithValues($this->addressType,["id", $addrProperty ], [$addrId, 0]);
            $this->addressType->edit($fields);
            if($addr && $addr->id == $addrId) {
                $addr->$addrProperty = 0;
                $this->$addrSetFunc($addr);
            }
        } else {
            
            $fields = FieldService::instance()->fillFieldsWithValues($this->addressType,["id", $addrProperty], [$addrId, 1]);
            $this->addressType->delete($fields);
            if($addr && $addr->id == $addrId) {
                $this->$addrSetFunc(NULL);
            }
        }
        
    }
    
    public function setPrimaryAddress($addrId, $type) {
        
        $toUpper = ucfirst($type);
        $addrDefaultProperty = "default" . $toUpper . "Address";
        $addrTypeProperty = $type."Address";
        
        TransactionService::instance()->beginTransaction();
        
        // reset all previous primary addresses of that type.
        $customer = $this->customerService->getCurrentCustomer();
        $filter = ["customer" => $customer->id, $addrTypeProperty => 1];
        $allTypeAddreses = $this->findAddresses($filter);
        
        foreach($allTypeAddreses as $resetAddress) {
            $fields = FieldService::instance()->fillFieldsWithValues($this->addressType,["id", $addrDefaultProperty ], [$resetAddress->id, 0]);
            $this->addressType->edit($fields);
        }
        
        //$typeAddresses = $this->
        
        $fields = FieldService::instance()->fillFieldsWithValues($this->addressType,["id", $addrDefaultProperty ], [$addrId, 1]);
        $this->addressType->edit($fields);
        
        $addrFunc = "getCurrent".$toUpper."Address";
        $addrSetFunc = "setCurrent".$toUpper."Address";
        
        $addr = $this->$addrFunc();
        
        if($addr && $addr->id == $addrId) {
            $addr->$addrDefaultProperty = 1;
            $this->$addrSetFunc($addr);
        }
        
        TransactionService::instance()->endTransaction();
        
    }
    
   /**
    * Get subset of addresses based on their type.
   */
   public function getFilteredAddresses($all, $type) {
       return array_filter($all, create_function('$address', 'return $address->'.$type.'Address ; '));
   }
   
   public function getCurrentAddressWithDefault($type, $addresses) {
       $toUpper = ucfirst($type);
       $funcName = $addrFunc = "getCurrent".  $toUpper."Address";
       $addrDefaultProperty = "default" . $toUpper . "Address";
       
       $currentAddr = $this->$funcName();
       if($currentAddr) {
           return $currentAddr;
       }
       
       foreach($addresses as $addr) {
           if($addr->$addrDefaultProperty) {
               return $addr;
           }
       }
       
       return NULL;
   }
   
   
   /**
    * Find Default Address from the list of addresses.
    */
    public function getDefaultAddress($all, $type) {
        $res = array_filter($all, create_function('$address', 'return $address->'.$type.'Address && $address->default' . ucfirst($type) . 'Address ; '));
        foreach($res as $item) {
            return $item;
        }
        return NULL;
    }
   
    
    /**
     * Get all addresses assigned to customer
     */
    public function getAllCustomerAddresses($customer) {
        return $this->addressType->search()->getWhereRecords(array("customer" => $customer->id));
    }
}