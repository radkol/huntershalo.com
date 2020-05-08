<?php

$requestService = RequestService::instance();
$pageSize = $pagination->pageSize;
$totalCount = $pagination->totalCount;
$currentPage = $pagination->currentPage;
$currentPageCount = $pagination->recordSetCount;
$pageCount = $pagination->pageCount;
$recordSetCount = $pagination->recordSetCount;
$offset = 2;
//show three previous pages and three forward pages to the current
$totalShow = $currentPage + $offset;

if(!isset($currentUri)) {
    $currentUri = $requestService->getUri();
}
?>

<?php if($pageCount > 1) : ?>
    <!-- Pagination  -->
    <section id="pagination">
        <ul>

            <li>Page</li>

            <?php if($currentPage != 1) : ?>
                <li><a href="<?php echo $requestService->addParameter($currentUri, "page", 1); ?>">&lt;&lt;</a></li>
                <li><a href="<?php echo $requestService->addParameter($currentUri, "page", $currentPage-1); ?>">&lt;</a></li>
            <?php endif; ?>

            <?php for($i= $currentPage - $offset ; $i <= $totalShow; $i++) : ?>
                <?php if($i > 0 && $pageCount-$i >= 0): ?>
                    <li <?php echo $currentPage == $i ? "class=active" : ""; ?>><a href="<?php echo $requestService->addParameter($currentUri, "page", $i); ?>"><?php echo $i; ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if($currentPage != $pageCount) : ?>
                <li><a href="<?php echo $requestService->addParameter($currentUri,"page", $currentPage + 1); ?>">&gt;</a></li>
                <li><a href="<?php echo $requestService->addParameter($currentUri,"page", $pageCount); ?>">&gt;&gt;</a></li>
            <?php endif; ?>
        </ul>
    </section>

<?php endif; ?>