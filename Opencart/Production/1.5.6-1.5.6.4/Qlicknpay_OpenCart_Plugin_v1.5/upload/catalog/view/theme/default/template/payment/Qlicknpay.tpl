<form action="<?php echo $Qlicknpay_url; ?>" method="post">
  <input type="hidden" name="merchant_id" value="<?php echo $Qlicknpay_merchantid; ?>" />
  <input type="hidden" name="detail" value="<?php echo $Qlicknpay_detail; ?>" />
  <input type="hidden" name="amount" value="<?php echo $Qlicknpay_amount; ?>" />
  <input type="hidden" name="order_id" value="<?php echo $Qlicknpay_order_id; ?>" />
  <input type="hidden" name="hash" value="<?php echo $Qlicknpay_hash; ?>" />
  <input type="hidden" name="name" value="<?php echo $Qlicknpay_name; ?>" />
  <input type="hidden" name="email" value="<?php echo $Qlicknpay_email; ?>" />
  <input type="hidden" name="phone" value="<?php echo $Qlicknpay_phone; ?>" />

  <?php

  $i = 0;
  foreach($Qlicknpay_product_name as $product)
  {
    $i++;
    ?>
    <input type="hidden" name="Qlicknpay_product_name<?= $i ?>" value="<?php echo $product; ?>" />
    <input type="hidden" name="Qlicknpay_product_price<?= $i ?>" value="<?php echo $Qlicknpay_product_price[$i]; ?>" />
    <input type="hidden" name="Qlicknpay_product_quantity<?= $i ?>" value="<?php echo $Qlicknpay_product_quantity[$i]; ?>" />
    <input type="hidden" name="Qlicknpay_product_img<?= $i ?>" value="<?php echo $Qlicknpay_product_img[$i]; ?>" />
  <?php
  }?>

  <input type="hidden" name="vers" value="<?= $Qlicknpay_vers; ?>" />

  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
