<?php

/**
 * Description of Customer Service
 *
 * @author raddy
 */
class CustomerService extends SingletonClass {

    private $sessionService;
    private $customerType;
    private $addressType;
    private $transactionService;
    private $requestService;
    private $fieldService;

    public function __construct() {
        parent::__construct();
        $this->sessionService = SessionService::instance();
        $this->transactionService = TransactionService::instance();
        $this->customerType = TypeService::instance()->getType("Customer");
        $this->addressType = TypeService::instance()->getType("Address");
        $this->requestService = RequestService::instance();
        $this->fieldService = FieldService::instance();
    }

    public function getCurrentCustomer() {
        return $this->sessionService->getAttribute("customer");
    }

    public function hasCurrentCustomer() {
        return $this->getCurrentCustomer() ? TRUE : FALSE;
    }

    public function setCurrentCustomer($customer) {
        $this->sessionService->setAttribute("customer", $customer);
    }

    public function login($customer) {
        $this->setCurrentCustomer($customer);
    }

    public function logout() {
        $this->sessionService->clearSession();
    }

    public function findCustomer($login, $password = NULL) {
        $search = array(
            "login" => $login
        );

        if ($password) {
            $search["password"] = md5($password);
        }

        return $this->customerType->search()->getRecord($search);
    }

    public function createCustomer() {

        $typeService = TypeService::instance();
        $fieldService = FieldService::instance();
        $requestService = RequestService::instance();
        
        $cType = $typeService->getType("Customer");

        $cFields = $cType->getDefinition()->getFields();

        $fieldService->populateFieldsFromRequest($cFields);

        $loginField = $fieldService->getFieldByName($cFields, "login");
        $emailField = $fieldService->getFieldByName($cFields, "email");
        $activeField = $fieldService->getFieldByName($cFields, "active");
        $loginField->value = $emailField->value;
        $activeField->value = 1;

        // begin object creations
        $this->transactionService->beginTransaction();

        // create customer
        $customerId = $cType->create($cFields);
        $this->createAddress($customerId);
        
        $this->transactionService->endTransaction();
        
        //send confirmation email
        
        $senderConfig = getEmailSenderConfig();
        $emailSubjects = getEmailSubjects();

        $emailContext = array(
            "accountLogin" => $loginField->value,
            "accountUrl" => NavigationService::instance()->getWebPageUrl($requestService->getAttribute("accountPage"))
        );

        $messageConfig = array(
            'subject' => $emailSubjects['accountcreated'],
            'from' => $senderConfig['email'],
            'to' => $emailField->value
        );
        
        EmailService::instance()->send("accountcreated", $emailContext, $messageConfig);
        
        return $customerId;
    }

    public function createAddress($customerId, $addressType = "billingAddress") {
        $typeService = TypeService::instance();
        $fieldService = FieldService::instance();
        
        $aType = $typeService->getType("Address");
        $aFields = $aType->getDefinition()->getFields();
        $fieldService->populateFieldsFromRequest($aFields);
        
        $custField = $fieldService->getFieldByName($aFields, "customer");
        $custField->value = $customerId;
        
        $typeField = $fieldService->getFieldByName($aFields, $addressType);
        $typeField->value = 1;
        
        //create address
        return $aType->create($aFields);
    }

    /**
     * Find Address By Uid
     * @param type $addressUid
     * @return type
     */
    public function findAddressByUid($addressUid) {
        return $this->addressType->search()->getRecord(array("uid" => $addressUid));
    }
    
    /**
     * Find Address By Id
     * @param type $addressId
     * @return type
     */
    public function findAddressById($addressId) {
        return $this->addressType->search()->getRecord(array("id" => $addressId));
    }
    
    /**
     * Update Current Customer.
     * This method could be called from my account page / my details
     */
    public function updateCurrentCustomer() {
        $fn = $this->requestService->getParam("firstName");
        $ln = $this->requestService->getParam("lastName");
        $phone = $this->requestService->getParam("phone");
        $password = $this->requestService->getParam("password");
        $currentCustomer = $this->getCurrentCustomer();
        $fieldnames = ["id","firstName","lastName","phone"];
        $values = [$currentCustomer->id, $fn, $ln, $phone];
        
        if($password) {
            $fieldnames[] = "password";
            $values[] = $password;
        }
        $customerType = TypeService::instance()->getType("Customer");
        $fields = $this->fieldService->fillFieldsWithValues($customerType, $fieldnames, $values);
        $customerType->edit($fields);
        
        $newCustomer = $customerType->search()->getRecord(array("id" => $currentCustomer->id));
        $this->setCurrentCustomer($newCustomer);
    }
    
    
    public function resetCustomerPassword($email) {
        $customer = $this->findCustomer($email); 
        if(!$customer) {
            return;
        }
        
        // send order confirmation email.

        $senderConfig = getEmailSenderConfig();
        $emailSubjects = getEmailSubjects();
        
        $resetUrl = NavigationService::instance()->getWebPageUrl($this->requestService->getAttribute("accountPage")). WebsiteConstants::SETNEWPASSWORD_URI;
        $resetToken = md5($customer->email. time());
        $resetUrl .= "?resetToken={$resetToken}&email={$email}";
        
        $fields = FieldService::instance()->fillFieldsWithValues($this->customerType, ["id","resetToken"], [$customer->id, $resetToken]);
        $this->customerType->edit($fields);
        
        // SEND EMAIL
        
        $emailContext = array(
            "email" => $customer->email,
            "resetPasswordUrl" => $resetUrl
        );

        $messageConfig = array(
            'subject' => $emailSubjects['forgottenpassword'],
            'from' => $senderConfig['email'],
            'to' => $customer->email
        );

        EmailService::instance()->send("forgottenpassword", $emailContext, $messageConfig);
        
    }
    
    public function setNewPasswordForCustomer($customer, $password) {
       $fields = FieldService::instance()->fillFieldsWithValues($this->customerType, ["id","password","resetToken"], [$customer->id, $password , ""]);
       $this->customerType->edit($fields);
    }
        
}
