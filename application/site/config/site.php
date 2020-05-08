<?php
/**
 * Custom configuration properties for the website.
 */
$config['customsites'] = array(
    "E-commerce" => array(
        "datatypes" => array(
            "title" => "Data Types",
            "icon" => "glyphicon-link",
            "items" => array(
                "Order",
                "OrderEntry",
                "Product",
                "Category",
                "Collection",
                "ShippingDetail",
                "Customer",
                "Wishlist",
                "Address",
                
            )
        )
    ),
    "artadorned.com" => array(
            "childtypes" => array(
                "title" => "Child Data Types",
                "icon" => "glyphicon-link",
                "items" => array(
                    "HomeBannerItem"
                )
            ),
            "datatypes" => array(
                "title" => "Data Types",
                "icon" => "glyphicon-link",
                "items" => array(
                    "Content",
                    "CollectionContent"
                )
            ),
            "modules" => array(
                "title" => "Content Modules",
                "icon" => "glyphicon-link",
                "items" => array(
                    "ContentModule",
                    "HomeBanner",
                    "Newsletter",
                    "HomeFeaturedCollections",
                    "HomeFeaturedProducts",
                    "ProductListing",
                    "CollectionListing",
                    "Basket",
                    "Checkout",
                    "Account",
                    "Contact",
                    "SiteSearch"
                )
            )
    )
);

/**
 * Templates that are publicly available.
 * This doesn't include admin template
 */
$config["templates"] = array(
    "homepage" => array(
        "main" => array(
            "title" => "Home Page Center Zone",
            "modules" => array(
                "HomeBanner",
                "Newsletter",
                "HomeFeaturedCollections",
                "HomeFeaturedProducts",
            )
        )
    ),
    "onecolumn" => array(
        "main" => array(
            "title" => "Main content zone",
            "modules" => array(
                "ContentModule",
                "Contact", 
                "ProductListing",
                "CollectionListing",
                "Newsletter",
                "Basket",
                "Checkout",
                "SiteSearch"
            )
        )
    ),
    "account" => array(
        "main" => array(
            "title" => "Main content zone",
            "modules" => array(
                "Account",
            )
        )
    ),
);

$config["maxpagelevel"] = 1;
$config['enable_db_core_generation'] = FALSE;
$config['enable_db_site_generation'] = TRUE;

/**
 * #SearchableItems# All Data Types in the system that are searchable, meaning they will be displayed on listing pages.
 */
$config['navigationTypes'] = array('Product', 'Category', 'Collection');

/**
 * All Modules in the system that can render #SearchableItems#.
 */
$config['navigationModules'] = array(
    'ProductListing' => array(
        "Product" => "category/%d/product/%d/%s",
        'Category' => 'category/%d/%s'
    ),
    'CollectionListing' => array(
        'Collection' => "%d/%s",
        //"Product" => "collection/%d/product/%d/%s",
    )
);
      
$config["refinements"] = array(
    "metalType" => "Metal Type Refinement",
    "style" => "Style Refinement",
    "era" => "Era Refinement",
    "aesthetic" => "Aesthetic Refinement",
    "color" => "Color Refinement",
);

$config["orderStatuses"] = [
    "awaitingPayment" => "Awaiting Payment",
    "paid" => "Paid",
    "inprogress" => "In Progress",
    "shipped" => "Shipped",  
    "returned" => "Returned"
];

/**
 * email subjects
 */
$config["emailsubjects"] = array(
    "accountcreated" => "Revolution Jewels - Account has been created",
    "ordercreated" => "Revolution Jewels - Order has been created",
    "forgottenpassword" => "Revolution Jewels - Request for password reset",
    "contactform" => "Revolution Jewels - Contact Form Submission"
);

/**
 * email virtual sender account
 */
$config["emailsender"] = array(
    'email' => 'no-reply@revolutionjewels.com',
    'desc' => 'Virtual Revolution Jewels Account'
);

/**
 * Internal Email that will receive contact form emails.
 */
$config["internalemail"] = array(
    "break.rado@gmail.com"
);