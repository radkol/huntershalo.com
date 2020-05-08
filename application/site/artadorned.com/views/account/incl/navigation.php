<a href="#" class="btn toggle_btn">Account</a>
<div class="account_navigation toggle_section">
    <ul>
        <?php foreach ($navigationMap as $titleKey => $uriPart) : ?>
            <li>
                <?php $href = $navigationService->getWebPageUrl($page) . $uriPart;
                      $selected = $page->url.$uriPart == $currentUri; ?>
                <a <?php echo $selected ? "class='selected'" : ""; ?> href="<?php echo $href; ?>" title="<?php echo $module->$titleKey; ?>">
                    <?php echo $module->$titleKey; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>