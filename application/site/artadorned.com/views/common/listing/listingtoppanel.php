
<!-- Top view options for the grid -->
<div id="listing-options" class="clearfix">

    <!-- Left Top view options for the grid -->
    <div id="options-pagesize">
        <span><?php echo $resourceService->getLabel("listing.view"); ?></span>
        <?php foreach (getPageSizeOptions() as $option) : ?>
            <?php $selectedClass = $requestParams["pageSize"] == $option ? "class='selected'" : ''; ?>
            <a <?php echo $selectedClass; ?> href="<?php echo $requestService->removeParameter($requestService->addParameter($currentUri, "pageSize", $option) ,"page"); ?>"><?php echo $option; ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Right Top view options for the grid -->
    <div id="options-sort">
        <?php foreach (getSortingOptions() as $optionLabel => $optionData) : ?>
            <?php $selectedClass = ($requestParams["sortby"] == $optionData["sortby"] && $requestParams["sortorder"] == $optionData["sortorder"]) ? "class='selected'" : ''; ?>
            <?php $sortingParamUri = $requestService->addParameter($currentUri, "sortby", $optionData["sortby"]); ?>
            <?php $sortingParamUri = $requestService->removeParameter($requestService->addParameter($sortingParamUri, "sortorder", $optionData["sortorder"]), "page"); ?>
            <a <?php echo $selectedClass; ?> href="<?php echo $sortingParamUri; ?>"><?php echo $resourceService->getLabel('listing.sort.' . $optionLabel); ?></a>
        <?php endforeach; ?>
    </div>
</div>
