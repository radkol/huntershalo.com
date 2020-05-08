<?php

/**
 * Description of PayPalService
 *
 * @author raddy
 */
class PayPalService extends SingletonClass {
    
    const SESSION_CART_KEY = "cart_contents";
    const SESSION_SHIPPING_DETAIL_KEY = "shippingDetail";
    const SESSION_CUSTOMER_KEY = "customer";
    const SESSION_BILLING_ADDRESS_KEY = "selectedBillingAddress";
    const SESSION_SHIPPING_ADDRESS_KEY = "selectedShippingAddress";
    
    private $config = [
        "http.CURLOPT_CONNECTTIMEOUT" => 30,
        'mode' => 'sandbox',
        'log.LogEnabled' => true,
        'log.LogLevel' => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
        'cache.enabled' => false,
    ];
    
    private $apiContext = [];
    private $resourceService;
    private $requestService;
    private $sessionService;
    private $navigationService;
    
    public function __construct() {
        parent::__construct();
        
        require getCmsFolderPath()."services/payment/paypal/config/autoload.php";
        
        $this->resourceService = ResourceService::instance();
        $this->requestService = RequestService::instance();
        $this->sessionService = SessionService::instance();
        $this->navigationService = NavigationService::instance();
        
        $this->setConfig();
        $this->setApiContext();
        
    }
    
    private function setApiContext() {
        $appId = $this->resourceService->getConfig("paypal.account.id");
        $appSecret = $this->resourceService->getConfig("paypal.account.secret");
        
        if(!$appId || !$appSecret) {
            throw new Exception("Please configure Pay Pal Service. set Site Configrations 'paypal.account.id' and 'paypal.account.secret' ");
        }
        
        $apiContext = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential($appId, $appSecret));
        $apiContext->setConfig($this->config);
        
        $this->apiContext = $apiContext;
    }
    
    private function setConfig() {
        $new = [];
        if($this->resourceService->getConfig("paypal.mode")) {
            $new["mode"] = $this->resourceService->getConfig("paypal.mode");
        }
        
        if($this->resourceService->getConfig("paypal.timeout")) {
            $new["http.CURLOPT_CONNECTTIMEOUT"] = $this->resourceService->getConfig("paypal.timeout");
        }
        
        if($this->resourceService->getConfig("paypal.log.enable")) {
            $new["log.LogEnabled"] = $this->resourceService->getConfig("paypal.log.enable");
        }
        
        if($this->resourceService->getConfig("paypal.log.level")) {
            $new["log.LogLevel"] = $this->resourceService->getConfig("paypal.log.level");
        }
        $this->config['log.FileName'] = getCmsFolderPath()."services/payment/paypal/log/PayPal.log";
        $this->config = array_merge($this->config, $new);
        
    }
    
    
    /**
     * Process paypal payment
     */
    public function processPayment() {
        
        $cartContent = $this->sessionService->getAttribute(self::SESSION_CART_KEY);
        $shippingDetail = $this->sessionService->getAttribute(self::SESSION_SHIPPING_DETAIL_KEY);
        
        // set URLs
        $checkoutPage = $this->requestService->getAttribute("checkoutPage");
        $confirmUrl = $this->navigationService->getWebPageUrl($checkoutPage)."/OrderConfirmation";
        $cancelUrl = $this->navigationService->getWebPageUrl($checkoutPage)."/OrderError?error=cancel";
        $errorUrl = $this->navigationService->getWebPageUrl($checkoutPage)."/OrderError?error=payment";
        
        $total = $cartContent["cart_total"];
        
        $count = $cartContent["total_items"];
        
        if($count < 1) {
            throw new Exception("Cannot purchase {$count} items. Invalid Items data.");
        }
        
        if(!$shippingDetail) {
            throw new Exception("Invalid Session Shipping Detail Object");
        }
        
        // create payer
        $payer = new PayPal\Api\Payer();
        $payer->setPaymentMethod("paypal");
        
        $itemsList = new PayPal\Api\ItemList();
        foreach($cartContent as $item) {
            if(is_array($item)) {
                $paypalItem = new PayPal\Api\Item();
                $paypalItem->setName($item["name"]);
                $paypalItem->setPrice($item["price"]);
                $paypalItem->setQuantity($item["qty"]);
                $paypalItem->setCurrency($shippingDetail->currencyCode);
                $paypalItem->setSku($item["id"]);
                $itemsList->addItem($paypalItem);
            }
        }
        
        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new \PayPal\Api\Details();
        $details->setShipping($shippingDetail->shippingCost)
            ->setTax($shippingDetail->shippingTax)
            ->setSubtotal($total);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency($shippingDetail->currencyCode)
            ->setTotal($total + $shippingDetail->shippingCost + $shippingDetail->shippingTax)
            ->setDetails($details);
        
        $invoiceNumber = uniqid();
        $orderNumber = "hhorder-" . $invoiceNumber;
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemsList)
            ->setDescription("Purchase {$count} items from our online store")
            ->setInvoiceNumber($invoiceNumber);
        
        // ### Redirect urls
        // Set the urls that the buyer must be redirected to after 
        // payment approval/ cancellation.
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        
        $redirectUrls
            ->setReturnUrl($confirmUrl)
            ->setCancelUrl($cancelUrl);

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new PayPal\Api\Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        
        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            file_put_contents($this->config['log.FileName'], $ex->getMessage());
            redirect($errorUrl);
        }
        
        $paymentData = [
            "paymentId" => $payment->getId(),
            //"paymentProcessed" => TRUE,
            "orderId" => $orderNumber,
            //"payerId" => "paypalPayer"
        ];
        
        $this->sessionService->setAttribute("payment", $paymentData);
        
        $checkoutService = CheckoutService::instance();
        $checkoutService->createIncompleteOrder();
        
        redirect($payment->getApprovalLink());
    }
    
}
