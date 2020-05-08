<?php

/**
 *  Admin Navigation Configuration
 */
$adminPanelPrefix = "admin/content/";

$config['adminNavigation'] = array(
    "dashboard" => array(
        "title" => "Dashboard",
        "icon" => "glyphicon-home",
        "item" => ""
    ),
    "webpages" => array(
        "title" => "Web Pages",
        "icon" => "glyphicon-link",
        "item" => "Webpage"
    ),
    "datatypes" => array(
        "title" => "System Items",
        "icon" => "glyphicon-wrench",
        "items" => array (
            "WebSite",
            "Language",
            "NavigationMenu",
            "NavigationMenuItem",
            "SiteConfig",
            "User",
            "Label",
            "Asset",
        )
    ),
    "modules" => array(
        "title" => "Content Modules",
        "icon" => "glyphicon-wrench",
        "items" => array (
            //"paragraph"
        )
    ),
    "ecommerce" => array(
        "title" => "E-commerce Types",
        "icon" => "glyphicon-wrench",
        "items" => array (
            //"paragraph"
        )
    ),
);

$config['navigationTypes'] = array();
