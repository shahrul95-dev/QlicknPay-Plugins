<form action="<?php echo $Qlicknpay_url; ?>" method="post">
  <?php
  //$arr = get_defined_vars();
  //print_r($arr);
  $i = 0;
  foreach($Qlicknpay_product_name as $product) {
    $i++;
    ?>
    <input type="hidden" name="Qlicknpay_product_name<?= $i ?>" value="<?php echo $product; ?>" />
    <input type="hidden" name="Qlicknpay_product_price<?= $i ?>" value="<?php echo $Qlicknpay_product_price[$i]; ?>" />
    <input type="hidden" name="Qlicknpay_product_quantity<?= $i ?>" value="<?php echo $Qlicknpay_product_quantity[$i]; ?>" />
    <input type="hidden" name="Qlicknpay_product_img<?= $i ?>" value="<?php echo $Qlicknpay_product_img[$i]; ?>" />
  <?php }?>
  <input type="hidden" name="address_1" value="<?php echo $Qlicknpay_address_1; ?>" />
  <input type="hidden" name="address_2" value="<?php echo $Qlicknpay_address_2; ?>" />
  <input type="hidden" name="city" value="<?php echo $Qlicknpay_city; ?>" />
      <input type="hidden" name="zip" value="<?php echo $Qlicknpay_zip; ?>" />
  <input type="hidden" name="country" value="<?php echo $Qlicknpay_country; ?>" />
  <input type="hidden" name="detail" value="<?php echo $Qlicknpay_detail; ?>" />
  <input type="hidden" name="amount" value="<?php echo $Qlicknpay_amount; ?>" />
  <input type="hidden" name="order_id" value="<?php echo $Qlicknpay_order_id; ?>" />
  <input type="hidden" name="hash" value="<?php echo $Qlicknpay_hash; ?>" />
  <input type="hidden" name="name" value="<?php echo $Qlicknpay_customer_name; ?>" />
  <input type="hidden" name="email" value="<?php echo $Qlicknpay_email; ?>" />
  <input type="hidden" name="phone" value="<?php echo $Qlicknpay_phone; ?>" />
  <input type="hidden" name="merchant_id" value="<?php echo $Qlicknpay_merchant_id; ?>" />


  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
