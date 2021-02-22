<?php
class ModelExtensionPaymentQlicknpay extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/Qlicknpay');

        $method_data = array();
        $status = true;

        if($status)
        {
            $method_data = array(
                'code'       => 'Qlicknpay',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('payment_Qlicknpay_sort_order')
            );
        }

        return $method_data;
    }
}
