<?php /*

  <section id="homebanner" style="background: url(<?php echo getAssetObjectPath($bgImage); ?>)">
  <?php $navigationMenuType = TypeService::instance()->getType("NavigationMenuItem"); ?>

  <h3><?php echo getLocalizedValueForField($module, "title"); ?></h3>
  <p><?php echo getLocalizedValueForField($module, "subtitle"); ?></p>

  <a href="<?php echo base_url($navigationMenuType->getUrl($linkTo)); ?>"><?php echo getLocalizedValueForField($linkTo, "name"); ?></a>
  </section>

 */ ?>

<?php //debug($moduleNavigations); ?>
<section class="flexslider">
    <ul class="slides">
        <?php foreach ($slides as $slide) : ?>
            <li style="background-image:url('<?php echo $resourceService->getAssetUrl($images[$slide->backgroundImage]); ?>')">
                <section class="content_container">
                    <section class="slide_content">
                        <h3 class="slide_title"><?php echo $slide->title; ?></h3>
                        <section class="slide_description"><?php echo $slide->description; ?></section>
                        <a href="<?php echo $slideNavigationsUrls[$slideNavigations[$slide->id]->id]; ?>" class="slide_btn"><?php echo localizedValue($slideNavigations[$slide->id], "name"); ?></a>
                    </section>
                </section>
                <!-- <img src="<?php echo $resourceService->getAssetUrl($images[$slide->backgroundImage]); ?>" /> -->
            </li>
        <?php endforeach; ?>
    </ul>

</section>

<?php
    $rightLink = isset($moduleNavigations[0]) ? $moduleNavigations[0] : NULL;
    $count = count($moduleNavigations);
?>

<section class="page_section">
    <section class="content_container home_page_welcome_section">
        <h1 class="title"><?php echo $module->title; ?></h1>
        <div class="home_page_welcome_section_content clearfix">
            <div class="description"><?php echo $module->description; ?></div>

            <?php if($rightLink) : ?>

            <a href='<?php echo $moduleNavigationsUrls[$rightLink->id]; ?>' class="button_more">
                <?php echo localizedValue($rightLink, "name"); ?> <i class="icon-chevron-thin-right"></i>
            </a>

            <?php endif; ?>
        </div>

        <div class="buttons clearfix">
            <?php for($i = 1 ; $i < $count ; $i ++) : ?>
                <a href='<?php echo $moduleNavigationsUrls[$moduleNavigations[$i]->id]; ?>'><span><?php echo localizedValue($moduleNavigations[$i], "name"); ?></span></a>
            <?php endfor; ?>
        </div>
    </section>
</section>
