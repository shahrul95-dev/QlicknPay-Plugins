<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>
    <div class="container">
      <h1>Failed Payment!</h1>
          <p>There was a problem processing your payment and the order did not complete.</p>

          <p>Possible reasons are:</p>
          <ul>
          <li>Insufficient funds</li>
          <li>Verification failed</li>
          </ul>

          <p>Please try again using a different payment method or check with your merchant</p>

          <p>If the problem persists please <a href="mailto:shahrul@Qlicknpay.com">contact us</a> with the details of the order you are trying to place.</p>
    </div>
    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
