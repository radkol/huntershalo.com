<?php

$pageSize = $pagination->pageSize;
$totalCount = $pagination->totalCount;
$currentPage = $pagination->currentPage;
$currentPageCount = $pagination->recordSetCount;
$pageCount = $pagination->pageCount;
$recordSetCount = $pagination->recordSetCount;

//show three previous pages and three forward pages to the current
$totalShow = $currentPage + 3;

$url = base_url(getListActionForType($objectData->typeName)).$pagination->queryString.$pagination->pageParamString;
?>
<?php if($pageCount > 1) : ?>
    <div class="row">
        <ul class="pagination">
            <?php if($currentPage != 1) : ?>
                <li><a href="<?php echo $url.'1'; ?>">&lt;&lt;</a></li>
                <li><a href="<?php echo $url.($currentPage-1); ?>">&lt;</a></li>
            <?php endif; ?>
            
            <?php for($i= $currentPage-3 ; $i <= $totalShow; $i++) : ?>
                <?php if($pageCount-$i >= 0 && $i > 0): ?>
                    <li <?php echo $currentPage == $i ? "class=active" : ""; ?>><a href="<?php echo $url.$i; ?>"><?php echo $i; ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
                    
            <?php if($currentPage != $pageCount) : ?>
                <li><a href="<?php echo $url.($currentPage+1); ?>">&gt;</a></li>
                <li><a href="<?php echo $url.$pageCount; ?>">&gt;&gt;</a></li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>