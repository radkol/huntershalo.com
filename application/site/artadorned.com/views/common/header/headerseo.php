<!--  SHOW SEO WHEN WE ARE ON COLLECTION PAGE -->
<?php if ($requestService->getAttribute("collection")) : ?>

    <?php $col = $requestService->getAttribute("collection"); ?>
    <title><?php echo $resourceService->getLabel("head.seo.title"); ?> - <?php echo $col->name; ?> - <?php echo localizedValue($page, "title"); ?></title>
    <meta name="keywords" content="<?php echo $col->shortDescription; ?>" />
    <meta name="description" content="<?php echo $col->longDescription; ?>" />
    
<!--  SHOW SEO WHEN WE ARE ON CATEGORY PAGE -->
<?php elseif ($requestService->getAttribute("category")) : ?>

    <?php $cat = $requestService->getAttribute("category"); ?>
    <title><?php echo $resourceService->getLabel("head.seo.title"); ?> - <?php echo $cat->name; ?> - <?php echo localizedValue($page, "title"); ?></title>
    <meta name="keywords" content="<?php echo $cat->name; ?>" />
    <meta name="description" content="<?php echo $cat->description; ?>" />
    
<!--  SHOW SEO WHEN WE ARE ON PDP PAGE -->
<?php elseif ($requestService->getAttribute("product")) : ?>

    <?php $prod = $requestService->getAttribute("product");
        $prodImages = $requestService->getAttribute("productImages");
        $metaProdImg = $prodImages && count($prodImages) > 0 ? $prodImages[0] : NULL;
    ?>
    <title><?php echo $resourceService->getLabel("head.seo.title"); ?> - <?php echo $prod->name; ?></title>
    <meta name="keywords" content="<?php echo $prod->shortDescription; ?>" />
    <meta name="description" content="<?php echo $prod->longDescription; ?>" />
    
    <meta property="og:url" content="<?php echo $requestService->getUri(); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $prod->name; ?>" />
    <meta property="og:description" content="<?php echo $prod->shortDescription; ?>" />
    
    <?php if($metaProdImg) : ?>
        <meta property="og:image" content="<?php echo $resourceService->getAssetUrl($metaProdImg, 400 , 600); ?>" />
    <?php endif; ?>

<?php else: ?>
    
    <title><?php echo localizedValue($page, "title"); ?></title>
    <meta name="keywords" content="<?php echo localizedValue($page, "keywords"); ?>" />
    <meta name="description" content="<?php echo localizedValue($page, "description"); ?>" />
    
<?php endif; ?>