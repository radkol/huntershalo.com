<?php $parts = explode(", ", $orderAddress); $count = count($parts); $index = 1; ?>
<address>
    <?php foreach($parts as $part) : ?>
        <?php if($part) : ?>
            <?php echo $part; ?>
            <?php if($index != $count) : ?>
                <br>
            <?php endif; ?> 
        <?php endif; $index++; ?>
    <?php endforeach; ?>
</address>