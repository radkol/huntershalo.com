<address>
    <?php echo $addr->firstName.' '.$addr->lastName; ?><br>
    <?php echo $addr->addressLine1; ?><br>
    <?php if ($addr->addressLine2) : ?>
        <?php echo $addr->addressLine2; ?><br>
    <?php endif; ?>
    <?php echo $addr->city; ?><br>
    <?php if(isset($addr->state) && $addr->state) : ?>
         <?php echo $addr->state; ?><br>
    <?php endif; ?>
    <?php echo $addr->postcode; ?><br>
    <?php echo $addr->country; ?>
</address>