<?php echo $header; ?>
  <div id="content">

    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?>
          <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
          </a>
          <?php } ?>
    </div>

    <?php if ($error_warning) { ?>
          <div class="warning">
            <?php echo $error_warning; ?>
          </div>
          <?php } ?>

          <div class="box">
                  <div class="heading">
                    <h1>
                      <img src="view/image/payment.png" alt="" />
                      <?php echo $heading_title; ?>
                      &nbsp;

                    </h1>
                    <div class="buttons">
                      <a onclick="$('#form').submit();" class="button">
                        <?php echo $button_save; ?>
                      </a>
                      <a onclick="location = '<?php echo $cancel; ?>';" class="button">
                        <?php echo $button_cancel; ?>
                      </a>
                    </div>
                  </div>


                          <div class="content">
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                              <table class="form">
                                <tr>
                                  <td>Payment Gateway By:</td>
                                  <td>
                                    <img src="https://Qlicknpay.com/merchant/assets/images/pdtlogonew.png" height="50px" halt="" />
                                  </td>
                                </tr>

				<!-- <tr>

				<td>Return URL</td>

				<td><input type="text" size="50" value="<?php echo $Qlicknpay_return_uri; ?>" /> &nbsp; <?php echo $help_copy_return_url; ?></td>
                </tr>
                <tr>
				<td>Callback URL</td>
				<td><input type="text" size="50" value="<?php echo $Qlicknpay_callback_uri; ?>" /> &nbsp; Copy this and paste it inside your "Callback URL" field inside your Qlicknpay account</td>
				</tr> -->
                <!-- <tr>
				<td>Return URL Parameters</td>
				<td><input type="text" size="100" value="&status_id=[TXN_STATUS]&order_id=[ORDER_ID]&transaction_id=[TXN_REF]&msg=[MSG]&hash=[HASH]" /> &nbsp; Copy and paste this inside your "Return URL Parameters" inside your Qlicknpay account</td>
                </tr> -->
                                <tr>
                                  <td>
                                    <?php echo $entry_merchant_id; ?>
                                  </td>
                                  <td>
                                    <input type="text" name="Qlicknpay_merchant_id" value="<?php echo $Qlicknpay_merchant_id; ?>" />
                                    <span><?php echo $help_merchant_id; ?></span>
                                  </td>
                                </tr>

                                <tr>
                                  <td>
                                    <?php echo $entry_API_key; ?>
                                  </td>
                                  <td>
                                    <input type="text" name="Qlicknpay_API_key" value="<?php echo $Qlicknpay_API_key; ?>" />
                                    <span><?php echo $help_API_key; ?></span>
                                  </td>
                                </tr>

                                <tr>
                                  <td><?php echo $entry_order_status; ?></td>
                                  <td>
                                    <select name="Qlicknpay_order_status_id" id="input-order-status" class="form-control">
                                      <?php foreach ($order_statuses as $order_status) { ?>
                                        <?php if ($order_status['order_status_id'] == $Qlicknpay_order_status_id) { ?>
                                          <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                          <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                          </select>
                                          <span><?php echo $help_order_status; ?></span>
                                  </td>
                                </tr>

                                <tr>
                                  <td><?php echo $entry_order_fail_status; ?></td>
                                  <td>
                                    <select name="Qlicknpay_order_fail_status_id" id="input-order-fail-status" class="form-control">
                                      <?php foreach ($order_statuses as $order_status) { ?>
                                        <?php if ($order_status['order_status_id'] == $Qlicknpay_order_fail_status_id) { ?>
                                          <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                          <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                          </select>
                                          <span><?php echo $help_order_fail_status; ?></span>
                                  </td>
                                </tr>

                                <tr>
                                  <td><?php echo $entry_status; ?></td>
                                  <td>
                                    <select name="Qlicknpay_status" id="input-status" class="form-control">
                                      <?php if ($Qlicknpay_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                          <option value="1"><?php echo $text_enabled; ?></option>
                                          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                          <?php } ?>
                                        </select>
                                  </td>
                                </tr>

                                <tr>
                                  <td><?php echo $entry_sort_order; ?></td>
                                  <td><input type="text" name="Qlicknpay_sort_order" value="<?php echo $Qlicknpay_sort_order; ?>" size="1" /></td>
                                </tr>


                              </table>
                            </form>
                          </div>
                        </div>
                  </div>
                  <?php echo $footer; ?>
