<?php
class ModelPaymentQlicknpay extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('payment/Qlicknpay');

        $method_data = array();
        $status = true;

        if($status)
        {
            $method_data = array(
                'code'       => 'Qlicknpay',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('Qlicknpay_sort_order')
            );
        }

        return $method_data;
    }
}
