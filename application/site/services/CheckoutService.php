<?php

/**
 * Description of CheckoutService
 *
 * @author raddy
 */
class CheckoutService extends SingletonClass {

    const ORDERSTATUS_AWAITINGPAYMENT = "awaitingPayment";
    const ORDERSTATUS_PAID = "paid";

    private $sessionService;
    private $cartService;
    private $customerService;
    private $requestService;
    
    public function __construct() {
        parent::__construct();
        $this->sessionService = SessionService::instance();
        $this->cartService = CartService::instance();
        $this->customerService = CustomerService::instance();
        $this->requestService = RequestService::instance();
    }

    /**
     * Check if we are in valid payment state
     */
    public function validPaymentState() {

        if ($this->cartService->getItemsCount() < 1 || $this->cartService->getTotal() <= 0) {
            return FALSE;
        }

        if (!$this->cartService->getCurrentShippingDetail()) {
            return FALSE;
        }

        if (!$this->sessionService->getAttribute("selectedBillingAddress")) {
            return FALSE;
        }

        if (!$this->sessionService->getAttribute("selectedShippingAddress")) {
            return FALSE;
        }

        return TRUE;
    }

    public function createIncompleteOrder() {

        $typeService = TypeService::instance();
        $fieldService = FieldService::instance();

        $orderType = $typeService->getType("Order");
        $ordFields = $orderType->getDefinition()->getFields();
        $orderEntryType = $typeService->getType("OrderEntry");
        $addressType = $typeService->getType("Address");

        $payment = $this->sessionService->getAttribute("payment");
        $currentBilling = $this->sessionService->getAttribute("selectedBillingAddress");
        $currentShipping = $this->sessionService->getAttribute("selectedShippingAddress");
        $currentShippingDetail = $this->cartService->getCurrentShippingDetail();

        $fields = ["uid", "customer", "paymentId", "status", "orderTotal",
            'shippingDetail', "billingAddress", "shippingAddress", "entries", "dateCreated", "dateModified"];

        TransactionService::instance()->beginTransaction();

        $entries = [];
        $count = 1;
        foreach ($this->cartService->getCart() as $item) {

            $f = [
                "productId", "productName", "productPrice", "quantity", "subtotal", "uid"
            ];

            $v = [
                $item["id"], $item["name"], $item["price"], $item["qty"], $item["subtotal"], $payment["orderId"] . '-' . $count
            ];

            $orderEntryFields = FieldService::instance()->fillFieldsWithValues($orderEntryType, $f, $v);
            $entries[] = $orderEntryType->create($orderEntryFields);
            $count++;
        }

        $values = [
            $payment["orderId"],
            $this->customerService->getCurrentCustomer()->id,
            $payment["paymentId"],
            self::ORDERSTATUS_AWAITINGPAYMENT,
            $this->cartService->getTotal(),
            $currentShippingDetail->id,
            $addressType->getAddressForOrder($currentBilling),
            $addressType->getAddressForOrder($currentShipping),
            $entries,
            createDateTime(),
            createDateTime()
        ];

        $orderFields = FieldService::instance()->fillFieldsWithValues($orderType, $fields, $values);

        $orderType->create($orderFields);
        TransactionService::instance()->endTransaction();
    }

    /**
     * Now that the user paid for the order, update the state of the order
     * to paid. also mark the products as used and clear everything from the session
     */
    public function updateSessionOrderConfirmationState() {
        $payment = $this->sessionService->getAttribute("payment");
        $orderUid = $payment["orderId"];
        if ($orderUid) {
            $orderType = TypeService::instance()->getType("Order");
            $order = $orderType->search()->getRecord(array("uid" => $orderUid));
            $customer = $this->customerService->getCurrentCustomer();
            
            if (!$order) {
                return;
            }

            $fields = FieldService::instance()->fillFieldsWithValues($orderType, ["id", "status"], [$order->id, self::ORDERSTATUS_PAID]);
            $orderType->edit($fields);

            // mark products as purchased !.
            $productType = TypeService::instance()->getType("Product");
            $orderEntries = $orderType->search()->getRelations("entries", $order->id);

            foreach (UtilityService::instance()->getPropertyArray($orderEntries, "productId") as $id) {
                $updateFields = FieldService::instance()->fillFieldsWithValues($productType, ["id", "available"], [$id, FALSE]);
                $productType->edit($updateFields);
            }

            //set order in the session
            $this->sessionService->setAttribute("order", $order);

            // send order confirmation email.

            $senderConfig = getEmailSenderConfig();
            $emailSubjects = getEmailSubjects();

            $emailContext = array(
                "orderUid" => $order->uid,
                "orderHistoryUrl" => NavigationService::instance()->getWebPageUrl($this->requestService->getAttribute("accountPage")). "/OrderHistory"
            );

            $messageConfig = array(
                'subject' => $emailSubjects['ordercreated'],
                'from' => $senderConfig['email'],
                'to' => $customer->email
            );

            EmailService::instance()->send("ordercreated", $emailContext, $messageConfig);
        }
    }

    /**
     * Delete the INCOMPLETE ORDER that we created when we send the paypal request.
     * The user cancelled the order hence we are removing it.
     */
    public function updateSessionOrderCancellationState() {
        $payment = $this->sessionService->getAttribute("payment");
        $orderUid = $payment["orderId"];
        if ($orderUid) {
            $orderType = TypeService::instance()->getType("Order");
            $order = $orderType->search()->getRecord(array("uid" => $orderUid));
            if ($order) {
                $fields = FieldService::instance()->fillFieldsWithValues($orderType, ["id"], [$order->id]);
                $orderType->delete($fields);
            }
        }
    }

    public function resetCheckoutState() {
        $this->cartService->clear();
        $this->sessionService->removeAttribute("selectedBillingAddress");
        $this->sessionService->removeAttribute("selectedShippingAddress");
        $this->sessionService->removeAttribute("shippingDetail");
        $this->sessionService->removeAttribute("payment");
        $this->sessionService->setAttribute("currentStep", 1);
    }

}
