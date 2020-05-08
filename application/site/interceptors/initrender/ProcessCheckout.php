<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProcessCartOperations
 *
 * @author raddy
 */
class ProcessCheckout implements CmsInterceptor {

    private $requestService;
    private $sessionService;
    private $customerService;
    private $checkoutService;
    private $navigationService;
    
    public function __construct() {
        $this->requestService = RequestService::instance();
        $this->sessionService = SessionService::instance();
        $this->customerService = CustomerService::instance();
        $this->checkoutService = CheckoutService::instance();
        $this->navigationService = NavigationService::instance();
    }
    
    public function priority() {
        return 3;
    }

    public function run() {

        $cartService = CartService::instance();
        
        $controller = &get_instance();

        $currentPage = $controller->getPageItem("page");
        $checkoutPage = $this->requestService->getAttribute("checkoutPage");
        $cartPage = $this->requestService->getAttribute("cartPage");
        $accountPage = $this->requestService->getAttribute("accountPage");
        $currentUri = $this->requestService->getUri();
        
        // if we are on the checkout page check if we have logged in user.
        // if we don't redirect to account login page. and set flag that we need to return to checkout.
        if ($currentPage->id == $checkoutPage->id && !$this->customerService->hasCurrentCustomer()) {
            $this->sessionService->setAttribute("redirectUri", $currentUri);
            redirect($accountPage->url);
            return;
        }

        // no items in the basket
        if ($currentPage->id == $checkoutPage->id && $cartService->getItemsCount() < 1) {
            // there is an option to get the order confirmation page.
            if($currentUri == $currentPage->url . "/OrderConfirmation" && $this->sessionService->getAttribute("order")) {
                return;
            }
            else {
                redirect($cartPage->url);
                return;
            }
        }
        
        // HANDLE PAY PAL PAYMENT CALL
        if($this->requestService->getParam("paypalpayment") && $this->requestService->getParam("key")) {
            // process paypal payment if valid state
            if($this->checkoutService->validPaymentState()) {
                $payPalService = PayPalService::instance();
                $payPalService->processPayment();
            } else {
                redirect($this->navigationService->getWebPageUrl($checkoutPage)."/OrderError?error=state");
            }
        }
        
        //handle paypal return URL (success) - ORDER CONFIRMATION
        
        if($currentUri == $currentPage->url . "/OrderConfirmation") {
            $paymentId = $this->requestService->getParam("paymentId");
            $paymentData = $this->sessionService->getAttribute("payment");
            
            //valid return call so update order state from 'incomplete' to 'created'.
            if($paymentData && isset($paymentData["paymentId"]) && $paymentData["paymentId"] == $paymentId) {
                $this->checkoutService->updateSessionOrderConfirmationState();
            }
            $this->checkoutService->resetCheckoutState();
            return;
        }
        
        // handle paypal return URL (cancel) ORDER CANCELLATION
        if($currentUri == $currentPage->url . "/OrderError") {
            $error = $this->requestService->getParam("error");
            $token = $this->requestService->getParam("token");
            
            $paymentData = $this->sessionService->getAttribute("payment");
            
            //valid return call so update order state from 'incomplete' to 'created'.
            if($error == "cancel" && $token && $paymentData && isset($paymentData["paymentId"])) {
                $this->checkoutService->updateSessionOrderCancellationState();
            }
            $this->checkoutService->resetCheckoutState();
        }
        
    }
    
}
