<!DOCTYPE html>
<html lang="<?php echo $currentLanguage->code; ?>" xml:lang="<?php $currentLanguage->code; ?>">
    <head>

        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" >
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php $this->load->view("common/header/headerseo"); ?>

        <?php if ($page->homePage) : ?>
            <meta name="google-site-verification" content="<?php echo $resourceService->getConfig("googleTrackingCode"); ?>" />
        <?php endif; ?>

        <!-- Google fonts -->
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700|Kameron:400,700|Scheherazade:400,700|Playfair+Display:400,700' rel='stylesheet' type='text/css'>

        <link href="<?php echo siteResourcePath("style.css", "fonticons"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo siteResourcePath("app.css"); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo siteResourcePath("revolutionjewels.css"); ?>" rel="stylesheet" type="text/css">
    </head>
<body>

    <!-- LOAD GOOGLE ANALYTICS -->
    <?php $this->load->view("lib/googleanalytics"); ?>

    <section id="layout_container">
        <header>
            <section id="header_container">
                <section class="content_container clearfix">

                    <a href="#" id="menu_btn">
                        <span class="lines_container">
                            <span class="line"></span>
                            <span class="line"></span>
                            <span class="line"></span>
                            <span class="line"></span>
                        </span>
                    </a>

                    <a href="<?php echo $navigationService->getStaticUrl(); ?>" id="logo">
                        <img src="<?php echo siteResourcePath("logo.svg", "images"); ?>" />
                    </a>
                    <section id="headline">
                        <?php echo $resourceService->getLabel("header.text.shipping"); ?>
                    </section>

                    <section id="header-actions">
                        <?php $this->load->view("common/header/search"); ?>
                        <?php /* if(($page->id != $requestService->getAttribute("cartPage")->id) &&($page->id != $requestService->getAttribute("checkoutPage")->id)) : */ ?>
                            <?php $this->load->view("common/header/minibasket"); ?>
                        <?php /* endif; */ ?>
                    </section>

                    <nav>
                        <section id="user_navigation">
                            <?php $customerService = CustomerService::instance(); ?>
                            <?php $accountPage = $requestService->getAttribute("accountPage"); ?>
                            <?php $customer = $customerService->getCurrentCustomer(); ?>

                            <!-- CUSTOMER NOT LOGGED IN SHOW LOGIN / REGISTER LINKS -->
                            <?php if(!$customerService->hasCurrentCustomer()) : ?>
                                <a href="<?php echo $navigationService->getWebPageUrl($accountPage); ?>">
                                    <?php echo $resourceService->getLabel("header.link.login"); ?>
                                </a>
                                <span class="text_separator"><?php echo $resourceService->getLabel("header.text.or"); ?></span>
                                <a href="<?php echo $navigationService->getStaticUrl($accountPage->url).'/Register'; ?>">
                                    <?php echo $resourceService->getLabel("header.link.register"); ?>
                                </a>
                            <!-- CUSTOMER LOGGED IN SHOW MY ACCOUNT  / LOGIN LINK -->
                            <?php else: ?>
                                <span class="welcome_back"><?php echo $resourceService->getLabel("header.label.welcomeback"); ?></span>
                                <a href="<?php echo $navigationService->getWebPageUrl($accountPage); ?>">
                                    <?php echo $customer->firstName . ' ' . $customer->lastName; ?>
                                </a>
                                <a href="<?php echo $navigationService->getWebPageUrl($accountPage). WebsiteConstants::WISHLIST_URI; ?>"><?php echo $resourceService->getLabel("header.link.wishlist"); ?></a>
                                <a href="<?php echo $navigationService->getWebPageUrl($accountPage); ?>?logout=true"><?php echo $resourceService->getLabel("header.link.logout"); ?></a>
                            <?php endif; ?>
                        </section>

                        <ul id="main_navigation">
                            <?php  $currentUri = $requestService->getUri(); ?>
                            <?php foreach($navigationService->getMenu('main') as $headerMenuItem) : ?>
                                <li>
                                    <?php $hrefValue = $navigationService->getMenuNavItemUrl($headerMenuItem); ?>
                                    <a <?php echo $currentUri && strpos($hrefValue, $currentUri) !== FALSE ? "class='selected'" : ""; ?> href="<?php echo $hrefValue; ?>">
                                        <?php echo localizedValue($headerMenuItem, "name"); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <aside>
                            <ul>
                                <?php foreach($navigationService->getMenu('mainHeader') as $headerMenuItem) : ?>
                                    <li><a href="<?php echo $navigationService->getMenuNavItemUrl($headerMenuItem); ?>"><?php echo localizedValue($headerMenuItem, "name"); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </aside>
                    </nav>
                </section>
            </section>
        </header>
        <section id="page_container">