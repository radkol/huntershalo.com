<section id="collection-heading" style="background-image:url('<?php echo $resourceService->getAssetUrl($collectionLandingImage, 1200, 600); ?>')">

    <div class="collection_item_details ">
        <h1 class="collection_item_title"><?php echo $collection->name; ?></h1>
        <div class="collection_item_description"><?php echo getStringWithWordLimit($collection->shortDescription, 4, true); ?></div>
        <img src="<?php echo $resourceService->getAssetUrl($collectionLandingImage); ?>" alt="<?php echo $collection->name; ?>" />
    </div>

</section>
