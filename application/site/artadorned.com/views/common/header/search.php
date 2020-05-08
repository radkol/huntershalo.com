<form action="<?php echo $navigationService->getWebPageUrl($requestService->getAttribute("searchPage")); ?>" method="get" id="search_form">
    <input type="text" name="q" placeholder="<?php echo $resourceService->getLabel('header.input.search'); ?>" />
    <button type="submit" name="submit" ><i class="icon-search"></i></button>
</form>