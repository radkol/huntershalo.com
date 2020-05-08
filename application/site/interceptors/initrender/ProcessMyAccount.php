<?php

/**
 * Description of ProcessMyAccount
 *
 * @author raddy
 */
class ProcessMyAccount implements CmsInterceptor {

    public function priority() {
        return 4;
    }

    public function run() {

        $requestService = RequestService::instance();
        $controller = &get_instance();
        $accountPage = $requestService->getAttribute("accountPage");
        $currentPage = $controller->getPageItem("page");
        $navigationService = NavigationService::instance();

        if ($accountPage->id != $currentPage->id) {
            return;
        }

        $customerType = TypeService::instance()->getType("Customer");
        $customerService = CustomerService::instance();
        $sessionService = SessionService::instance();

        // --- LOGOUT ACTIVITY ---
        if ($requestService->getParam("logout")) {
            $customerService->logout();
            redirect($requestService->getAttribute("homePage")->url);
            return;
        }

        // --- LOGIN ACTIVITY ---
        // check for login activity
        if ($requestService->getParam("loginform") && $requestService->getParam("loginsubmit")) {
            $this->setLoginValidation();
            if ($controller->form_validation->run() !== FALSE) {
                $login = $requestService->getParam("login");
                $password = $requestService->getParam("password");
                $customer = $customerService->findCustomer($login, $password);
                if (!$customer) {
                    $requestService->setAttribute("customError", "Invalid login / password combination");
                } else {
                    // customer found then login
                    $customerService->setCurrentCustomer($customer);

                    // do we have to redirect to somewhere ?, if not, display default account page.
                    $redirectUri = $sessionService->getAttribute("redirectUri");

                    if ($redirectUri) {
                        $sessionService->removeAttribute("redirectUri");
                        redirect($redirectUri);
                    } else {
                        redirect($accountPage->url);
                    }
                }
            }
            return;
        }

        // --- END LOGIN ACTIVITY ---

        
        // --- REGISTER ACTIVITY ---
        // check for register activity
        if ($requestService->getParam("registerform") && $requestService->getParam("registersubmit")) {
            $this->setRegisterValidation($customerType);
            if ($controller->form_validation->run() !== FALSE) {

                // register customer
                $customerService->createCustomer();

                // get created customer
                $customer = $customerService->findCustomer($requestService->getParam("email"));
                $customerService->setCurrentCustomer($customer);

                // do we have to redirect to somewhere ?, if not, display default account page.
                $redirectUri = $sessionService->getAttribute("redirectUri");
                if ($redirectUri) {
                    $sessionService->removeAttribute("redirectUri");
                    redirect($redirectUri);
                } else {
                    redirect($accountPage->url);
                }
            }
            return;
        }
        // --- END REGISTER ACTIVITY ---

        // -- SET NEW PASSWORD ACTIVITY -- 
        $currentUri = $requestService->getUri();
        $setNewPasswordUri = $accountPage->url . WebsiteConstants::SETNEWPASSWORD_URI;
        if ($setNewPasswordUri == $currentUri) {
            
            // Page is not available if we have logged in customer
            if($customerService->hasCurrentCustomer()) {
                redirect($accountPage->url);
            }
            
            // user clicks on the link from the email
            if ($requestService->getParam("resetToken") && $requestService->getParam("email")) {
                $email = $requestService->getParam("email");
                $token = $requestService->getParam("resetToken");
                $customer = TypeService::instance()->getType("Customer")->search()->getRecord(array("email" => $email, "resetToken" => $token));
                if (!$customer) {
                    redirect($accountPage->url);
                }
                $sessionService->setAttribute("resetCustomer", $customer);
            // user submits setnewpassword
            } else if (isFormSubmitted("setnewpassword")) {
                $resCustomer = $sessionService->getAttribute("resetCustomer");
                if(!$resCustomer) {
                    redirect($accountPage->url);
                }
                $this->setSetNewPasswordValidation();
                if ($controller->form_validation->run() !== FALSE) {
                    $password = $requestService->getParam("password");
                    $customerService->setNewPasswordForCustomer($resCustomer, $password);
                    $sessionService->removeAttribute("resetCustomer");
                    $sessionService->setFlashAttribute("setPassword", TRUE);
                    redirect($setNewPasswordUri);
                }                
            } else {
               $redirectAfterSuccess = $sessionService->getFlashAttribute("setPassword");
               if($redirectAfterSuccess) {
                   $requestService->setAttribute("setPassword", TRUE);
               } else {
                   redirect($accountPage->url);
               }
            }
        }
        
        // -- END SET NEW PASSWORD ACTIVITY -- 
         
        // -- FORGOTTEN PASSWORD ACTIVITY --
        $forgotPasswordUri = $accountPage->url . WebsiteConstants::FORGOTTENPASSWORD_URI;
        if ($forgotPasswordUri == $currentUri) {
            if($customerService->hasCurrentCustomer()) {
                redirect($accountPage->url);
            } else if (isFormSubmitted("forgottenpassword")) {
                $this->setForgottenPasswordValidation();
                if ($controller->form_validation->run() !== FALSE) {
                    $customerService->resetCustomerPassword($requestService->getParam("email"));
                    $sessionService->setFlashAttribute("sendPassword", TRUE);
                    redirect($forgotPasswordUri);
                }
            } else {
                $redirectAfterSuccess = $sessionService->getFlashAttribute("sendPassword");
                if($redirectAfterSuccess) {
                   $requestService->setAttribute("sendPassword", TRUE);
               }
            }
        }
        // -- END FORGOTTEN PASSWORD ACTIVITY

        // -- ACTIVITIES DEPENDANT ON LOGGED USER --
        $currentCustomer = $customerService->getCurrentCustomer();
        if (!$currentCustomer) {
            return;
        }

        // --- END REGISTER ACTIVITY ---
        // -- REMOVE ADDRESS ACTIVITY --
        if (isFormSubmitted("removebillingaddress") || isFormSubmitted("removeshippingaddress")) {

            $addressType = $requestService->getParam("addressType");
            $addressId = $requestService->getParam("selected" . ucfirst($addressType));
            if ($addressId && $addressType) {
                AddressService::instance()->deleteAddress($addressId, $addressType);
            }
        }

        // -- ENDREMOVE ADDRESS ACTIVITY --
        // --- MAKE ADDRESS PRIMARY ACTIVITY ---
        if (isFormSubmitted("primaryshippingaddress") || isFormSubmitted("primarybillingaddress")) {

            $addressType = $requestService->getParam("addressType");
            $addressId = $requestService->getParam("selected" . ucfirst($addressType));
            if ($addressId && $addressType) {
                AddressService::instance()->setPrimaryAddress($addressId, $addressType);
            }
        }
        // --- END MAKE ADDRESS PRIMARY ACTIVITY ---
        // --- ADD NEW ADDRESS ACTIVITY ---
        if (isFormSubmitted("newbillingaddress") || isFormSubmitted("newshippingaddress")) {
            $addressType = $requestService->getParam("addressType");
            setAddressValidation();
            if ($controller->form_validation->run() !== FALSE) {
                // create Address
                $customerService->createAddress($currentCustomer->id, "{$addressType}Address");
                $requestService->setAttribute("fillValue", FALSE);
            } else {
                $requestService->setAttribute("fillValue", TRUE);
            }
            return;
        }
        // --- END ADD NEW ADDRESS ACTIVITY ---
    }

    private function setForgottenPasswordValidation() {
        $ci = &get_instance();
        $ci->load->library('form_validation');
        $ci->form_validation->set_rules("email", "Email", "required|valid_email");
    }

    private function setSetNewPasswordValidation() {
        $ci = &get_instance();
        $ci->load->library('form_validation');
        $ci->form_validation->set_rules("password", "Password", "required|min_length[6]|matches[repeatpassword]");
        $ci->form_validation->set_rules("repeatpassword", "Password Repeat", "required");
    }

    private function setLoginValidation() {
        $ci = &get_instance();
        $ci->load->library('form_validation');
        $ci->form_validation->set_rules("login", "Email", "required|valid_email");
        $ci->form_validation->set_rules("password", "Password", "required|min_length[6]");
    }

    private function setRegisterValidation($customerType) {
        $ci = &get_instance();
        $ci->load->library('form_validation');
        $ci->form_validation->set_rules("email", "Email", "required|valid_email|is_unique[{$customerType->tableName}.login]");
        $ci->form_validation->set_rules("password", "Password", "required|min_length[6]|matches[repeatpassword]");
        $ci->form_validation->set_rules("repeatpassword", "Password Repeat", "required");
        $ci->form_validation->set_rules("firstName", "First Name", "required|min_length[3]");
        $ci->form_validation->set_rules("lastName", "Last Name", "required|min_length[3]");
        $ci->form_validation->set_rules("addressLine1", "Address Line 1", "required|min_length[3]");
        $ci->form_validation->set_rules("addressLine2", "Address Line 2", "");
        $ci->form_validation->set_rules("country", "Country", "required|min_length[3]");
        $ci->form_validation->set_rules("city", "City", "required");
        $ci->form_validation->set_rules("postcode", "Postcode", "required");
        $ci->form_validation->set_rules("state", "State", "");
        $ci->form_validation->set_rules("phone", "Phone", "required");
    }

}
