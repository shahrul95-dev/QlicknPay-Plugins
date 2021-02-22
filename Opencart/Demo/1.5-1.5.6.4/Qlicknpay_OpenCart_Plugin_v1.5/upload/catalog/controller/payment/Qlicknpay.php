<?php
class ControllerPaymentQlicknpay extends Controller
{
    public function index()
    {
        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->data['continue'] = $this->url->link('checkout/success');

        # Prepare the data to send to Qlicknpay
        $this->data['Qlicknpay_url'] = 'https://www.demo.Qlicknpay.com/merchant/api/v1/opencart_receiver';
        $this->data['Qlicknpay_merchantid'] = $this->config->get('Qlicknpay_merchant_id');
        $this->data['Qlicknpay_order_id'] = $this->session->data['order_id'];
        $this->data['Qlicknpay_detail'] = "Payment_for_order_".$this->session->data['order_id'];
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $this->data['Qlicknpay_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
        # need to strip the $ and comma
        $this->data['Qlicknpay_amount'] = str_replace('$', '', $this->data['Qlicknpay_amount']);
        $this->data['Qlicknpay_amount'] = str_replace(',', '', $this->data['Qlicknpay_amount']);
        $this->data['Qlicknpay_hash'] = md5($this->config->get('Qlicknpay_merchant_id').$this->config->get('Qlicknpay_API_key').$this->data['Qlicknpay_detail'].$this->data['Qlicknpay_amount'].$this->session->data['order_id']);

        if(isset($this->session->data['guest']))
        {
            $this->data['Qlicknpay_name'] = $this->session->data['guest']['firstname'].' '.$this->session->data['guest']['lastname'];
            $this->data['Qlicknpay_email'] = $this->session->data['guest']['email'];
            $this->data['Qlicknpay_phone'] = $this->session->data['guest']['telephone'];
        } else {
            $customerId = $this->session->data['customer_id'];
            $this->data['Qlicknpay_name'] = $this->customer->getFirstName().' '.$this->customer->getLastName();
            $this->data['Qlicknpay_email'] = $this->customer->getEmail();
            $this->data['Qlicknpay_phone'] = $this->customer->getTelephone();
        }

        $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$this->session->data['order_id'] . "'");
        $i = 0;
        foreach ($order_product_query->rows as $order_product)
          {
            $i++;
            $this->data['Qlicknpay_product_name'][$i] =$order_product['name'];
            $this->data['Qlicknpay_product_price'][$i] =$order_product['price'];
            $this->data['Qlicknpay_product_quantity'][$i] =$order_product['quantity'];

            $product_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $order_product['product_id'] . "'");
            foreach ($product_data->rows as $product)
            {
              $this->data['Qlicknpay_product_img'][$i] =$product['image'];
            }


          }

        $this->data['Qlicknpay_vers'] = '1.5';

        //var_dump( 'Qlicknpay_name: '.$this->data['Qlicknpay_name'].' Qlicknpay_email: '.$this->data['Qlicknpay_email'].' Qlicknpay_phone '.$this->data['Qlicknpay_phone']  );
        //echo '<pre>';
        //var_dump( $this->customer  );
        //echo '</pre>';

        /*if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/Qlicknpay.tpl'))
            return $this->load->view($this->config->get('config_template') . '/template/payment/Qlicknpay.tpl', $this->data);
        else
            return $this->load->view('default/template/payment/custom.tpl', $this->data);*/

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/Qlicknpay.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/Qlicknpay.tpl';
            } else {
                $this->template = 'default/template/payment/Qlicknpay.tpl';
            }

            $this->render();
    }


    public function processing()
    {

        $this->load->language('payment/Qlicknpay');
        $this->document->setTitle($this->language->get('text_payment_title'));
        //$this->data['heading_title'] = $this->language->get('text_payment_title');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
                'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_payment_title'),
                'href' => $this->url->link('checkout/checkout'),
                'separator' => $this->language->get('text_separator')
        );




        $transaction_status = false;

        $invoice = urldecode($this->request->post['invoice']);
        $msg = urldecode($this->request->post['msg']);
        $pay_method = urldecode($this->request->post['pay_method']);
        $hash = urldecode($this->request->post['hash']);

        if((isset($this->request->post['fpx_fpxTxnId']) || isset($this->request->post['paypal_trx_id']) || isset($this->request->post['mastercard_trx_id'])  || isset($this->request->post['others_trx_id'])) && isset($this->request->post['invoice']) && isset($this->request->post['msg']) && isset($this->request->post['hash']))

        {


            if($pay_method == 'paypal')
            {
              $trx_id = urldecode($this->request->post['paypal_trx_id']);
              $method = 'PayPal';
            }
            else if($pay_method == 'mastercard')
            {
              $trx_id = urldecode($this->request->post['mastercard_trx_id']);
              $method = 'Mastercard';
            }
            else if($pay_method == 'others')
            {
              $trx_id = urldecode($this->request->post['others_trx_id']);
              $method = urldecode($this->request->post['trx_txt']);
            }
            else
            {
              $trx_id = urldecode($this->request->post['fpx_fpxTxnId']);
              $method = 'Online Banking';
            }

            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($invoice);
            if($order_info)
            {
              $hash_value = md5($this->config->get('Qlicknpay_API_key').$trx_id.$invoice.$msg);
                //var_dump( $hash_value );
                if($hash_value == $hash)
                {

                    if($msg == 'Transaction Approved' || $msg == 'Success')
                    {
                        $transaction_status = true;

                        if (!$order_info['order_status_id']) {
                            $this->model_checkout_order->confirm($invoice, $this->config->get('Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay ('.$method.'). Qlicknpay transaction id is '.$trx_id);
                            $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay ('.$method.'). Qlicknpay transaction id is '.$trx_id);
                        } else {
                            $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay ('.$method.'). Qlicknpay transaction id is '.$trx_id);
                        }

                        if($this->cart->hasProducts())
                            $this->cart->clear();

                        $this->data['continue'] = $this->url->link('checkout/success');
                        $this->data['text_payment_status'] = $this->language->get('text_payment_successful');
                        $this->data['color'] = 'green';
                        $this->data['button_continue'] = $this->language->get('button_success_continue');

                    }
                }
            }
        }


        if(!$transaction_status)
        {
            if (!$order_info['order_status_id']) {
                $this->model_checkout_order->confirm($invoice, $this->config->get('Qlicknpay_order_fail_status_id'), 'Payment was made using Qlicknpay. Qlicknpay transaction id is '.$trx_id);
                $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_fail_status_id'), 'Payment was made using Qlicknpay. Qlicknpay transaction id is '.$trx_id);
            } else {
                $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_fail_status_id'), 'Payment was made using Qlicknpay. Qlicknpay transaction id is '.$trx_id);
            }

            $this->data['continue'] = $this->url->link('checkout/cart');

            $this->data['text_payment_status'] = "We're sorry. Your order has fail.";

            $this->data['color'] = 'red';
            $this->data['button_continue'] = $this->language->get('button_fail_continue');
        }

        $this->data['text_payment_title'] = $this->language->get('text_payment_title');

        //$this->response->setOutput($this->load->view('default/template/payment/Qlicknpay_status.tpl', $this->data));


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/Qlicknpay_status.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/Qlicknpay_status.tpl';
            } else {
                $this->template = 'default/template/payment/Qlicknpay_status.tpl';
            }
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());

    }


    /*--------------------------------------------------------------------------------------------------------------------/
     *
     *    @description   This will be a return callback by Qlicknpay. Qlicknpay will call this function through a callback url when there is order that was not processed correctly.
     */
    public function callback_response()
    {

        $transaction_status = false;

        $invoice = urldecode($this->request->post['invoice']);
        $msg = urldecode($this->request->post['msg']);
        $pay_method = urldecode($this->request->post['pay_method']);
        $hash = urldecode($this->request->post['hash']);

        if((isset($this->request->post['fpx_fpxTxnId']) || isset($this->request->post['paypal_trx_id']) || isset($this->request->post['mastercard_trx_id'])) && isset($this->request->post['invoice']) && isset($this->request->post['msg']) && isset($this->request->post['hash']))
        {


            if($pay_method == 'paypal')
            {
              $trx_id = urldecode($this->request->post['paypal_trx_id']);
              $method = 'PayPal';
            }
            else if($pay_method == 'mastercard')
              {
                $trx_id = urldecode($this->request->post['mastercard_trx_id']);
                $method = 'Mastercard';
              }
            else
            {
              $trx_id = urldecode($this->request->post['fpx_fpxTxnId']);
              $method = 'Online Banking';
            }

            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($invoice);

            if($order_info)
            {
              $hash_value = md5($this->config->get('Qlicknpay_API_key').$trx_id.$invoice.$msg);
                if($hash_value == $hash)
                {
                  echo "OK";

                  if($msg == 'Transaction Approved' || $msg == 'Success')
                    {
                        $transaction_status = true;

                        if (!$order_info['order_status_id']) {
                            $this->model_checkout_order->confirm($invoice, $this->config->get('Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay ('.$method.'). Qlicknpay transaction id is '.$trx_id);
                            $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay ('.$method.'). Qlicknpay transaction id is '.$trx_id);
                        } else {
                            $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay ('.$method.'). Qlicknpay transaction id is '.$trx_id);
                        }
                    }
                }
            }
        }

        if(!$transaction_status)
        {
            if (!$order_info['order_status_id']) {
                $this->model_checkout_order->confirm($invoice, $this->config->get('Qlicknpay_order_fail_status_id'), 'Payment was made using Qlicknpay. Qlicknpay transaction id is '.$trx_id);
                $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_fail_status_id'), 'Payment was made using Qlicknpay. Qlicknpay transaction id is '.$trx_id);
            } else {
                $this->model_checkout_order->update($invoice, $this->config->get('Qlicknpay_order_fail_status_id'), 'Payment was made using Qlicknpay. Qlicknpay transaction id is '.$trx_id);
            }
        }

        //var_dump("ok");
        // echo "OK";

    }
}
