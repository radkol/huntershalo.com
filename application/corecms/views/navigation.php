<div class="col-md-3">
    <div class="sidebar content-box" style="display: block;">
        <div class="alert alert-success text-center"><strong>CMS COMPONENTS</strong></div>
        <ul class="nav">
            <?php foreach (getAdminNavigation() as $navId => $navContent): ?>
                <?php if (isset($navContent["items"])) : ?>
                    <?php if(count($navContent["items"]) > 0) : ?>
                    <li class="submenu">
                        <a href="#">
                             <i class="glyphicon <?php echo $navContent["icon"]; ?>"></i> <?php echo $navContent["title"]; ?>
                             <span class="caret pull-right"></span>
                        </a>
                        <ul style="display: none;">
                            <?php foreach($navContent["items"] as $type) : ?>
                                <?php $typeData = TypeService::instance()->getType($type); ?>
                                <li><a href="<?php echo base_url(getListActionForType($type)); ?>"><?php echo $typeData->typeAsString(); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if(empty($navContent["item"])) : ?>
                        <?php $link  = base_url("admin"); ?>
                    <?php else:  ?>
                        <?php $link  = base_url(getListActionForType($navContent["item"])) ; ?>
                    <?php endif; ?>
                    <li><a href="<?php echo $link ; ?>"><i class="glyphicon <?php echo $navContent["icon"]; ?>"></i><?php echo "&nbsp;&nbsp;".$navContent["title"]; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php foreach(getCustomSitesNavigation() as $siteName => $siteData) : ?>
        <div class="sidebar content-box" style="display: block;">
            <div class="alert alert-success text-center"><strong><?php echo strtoupper($siteName); ?> COMPONENTS</strong></div>
            <ul class="nav">
                <?php foreach($siteData as $dataType => $dataContent) : ?>
                    <li class="submenu">
                        <a href="#">
                             <i class="glyphicon <?php echo $dataContent["icon"]; ?>"></i> <?php echo $dataContent["title"]; ?>
                             <span class="caret pull-right"></span>
                        </a>
                        <ul style="display: none;">
                            <?php foreach($dataContent["items"] as $type) : ?>
                                <?php $typeData = TypeService::instance()->getType($type); ?>
                                <li><a href="<?php echo base_url(getListActionForType($type)); ?>"><?php echo $typeData->typeAsString(); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>