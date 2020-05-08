<h3 class="filters_title"><?php echo $filterHeading; ?></h3>
<ul>
    <?php foreach($refinements["category"] as $cat) : ?>
        <?php $selectedClass = ($requestParams["category"] == $cat->id || ($category && $category->id == $cat->id)) ? "class='selected'" : '' ; ?>
        <?php if($selectedClass) : ?>
            <li><a <?php echo $selectedClass; ?> href="<?php echo $requestService->removeParameter($requestService->removeParameter($currentUri, "category", $cat->id), "page"); ?>"><?php echo $cat->name; ?></a></li>
        <?php else : ?>
            <li><a href="<?php echo $requestService->removeParameter($requestService->addParameter($currentUri, "category", $cat->id), "page"); ?>"><?php echo $cat->name; ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
