<!-- OTHER REFINEMENTS FILTER -->
<section class="refinement_filters">
<h3 class="filters_title"><?php echo $resourceService->getLabel("listing.refinements"); ?></h3>

    <?php foreach ($refinements as $refinementType => $refinementValues) : ?>
        <?php if ($refinementType == "category") : continue; endif; ?>

        <!-- FOR EVERY REFINEMENT, IF IT HAS OPTIONS -->
        <?php if (count($refinementValues) > 0) : ?>
            <section class="toggle-container active opened">
                <section class="toggle-handle">
                    <?php echo $resourceService->getLabel("listing.refinements.{$refinementType}"); ?>

                    <!-- clear button--- for refinement type -->
                    <?php if ($requestParams[$refinementType]) : ?>
                        <a href="<?php echo $requestService->removeParameter($currentUri, $refinementType . '[]'); ?>" class="clear_filters">Clear</a>
                    <?php endif; ?>
                </section>

                <section class="toggle-content">
                    <ul>
                        <!-- FOR EVERY REFINEMENT VALUE -->
                        <?php foreach ($refinementValues as $refinementValue) : ?>
                            <?php $selectedClass = ($requestParams[$refinementType] && in_array($refinementValue, $requestParams[$refinementType])) ? "class='selected'" : ''; ?>
                            <?php $currentUri = $requestService->removeParameter($currentUri, "page"); ?>
                            <?php if ($selectedClass) : ?>
                                <li><a <?php echo $selectedClass; ?> href="<?php echo $requestService->removeParameter($currentUri, "{$refinementType}[]", $refinementValue); ?>"><?php echo $refinementValue; ?></a></li>
                            <?php else : ?>
                                <li><a href="<?php echo $requestService->addParameter($currentUri, "{$refinementType}[]", $refinementValue); ?>"><?php echo $refinementValue; ?></a></li>
                            <?php endif; ?>
                         <?php endforeach; ?>
                    </ul>
                </section>
            </section>
        <?php endif; ?>

<?php endforeach; ?>

</section>