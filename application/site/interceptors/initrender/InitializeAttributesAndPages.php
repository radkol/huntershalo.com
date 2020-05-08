<?php

/**
 * Set all required data in the request
 */
class InitializeAttributesAndPages implements CmsInterceptor {

    public function priority() {
        return 1;
    }

    public function run() {

        $requestService = RequestService::instance();
        $resourceService = ResourceService::instance();
        $customerService = CustomerService::instance();
        
        //add pages to the request so we can use it everywhere !.
        $journeyPages = StructureService::instance()->getWebPagesForModuleTypes(array("SiteSearch","Contact", "Basket", "Checkout", "Account", "HomeBanner"));
        
        $requestService->setAttribute("cartPage", $journeyPages["Basket"][0]);
        $requestService->setAttribute("checkoutPage", $journeyPages["Checkout"][0]);
        $requestService->setAttribute("accountPage", $journeyPages["Account"][0]);
        $requestService->setAttribute("homePage", $journeyPages["HomeBanner"][0]);
        $requestService->setAttribute("contactPage", $journeyPages["Contact"][0]);
        $requestService->setAttribute("searchPage", $journeyPages["SiteSearch"][0]);
        $requestService->setAttribute("currentCustomer", $customerService->getCurrentCustomer());
        
        $requestService->setAttribute("maxItemCount", $resourceService->getConfig("basket.product.maxcount"));
        

    }

}
