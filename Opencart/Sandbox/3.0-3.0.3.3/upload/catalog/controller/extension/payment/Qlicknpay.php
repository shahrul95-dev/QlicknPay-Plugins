<?php
class ControllerExtensionPaymentQlicknpay extends Controller
{
    public function index()
    {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');

        if (!empty($this->session->data['payment_address']['postcode'])) {
            $data['Qlicknpay_address_1'] = htmlentities($this->session->data['payment_address']['address_1'], ENT_QUOTES, 'UTF-8');

            $data['Qlicknpay_address_2'] = htmlentities($this->session->data['payment_address']['address_2'], ENT_QUOTES, 'UTF-8');

            $data['Qlicknpay_city'] = htmlentities($this->session->data['payment_address']['city'], ENT_QUOTES, 'UTF-8');

            $data['Qlicknpay_zip'] = htmlentities($this->session->data['payment_address']['postcode'], ENT_QUOTES, 'UTF-8');

            $data['Qlicknpay_country'] = htmlentities($this->session->data['payment_address']['country'], ENT_QUOTES, 'UTF-8');
        } else {
            $data['Qlicknpay_address_1'] = '';

            $data['Qlicknpay_address_2'] = '';

            $data['Qlicknpay_city'] = '';

            $data['Qlicknpay_zip'] = '';

            $data['Qlicknpay_country'] = '';
        }

        $data['Qlicknpay_url'] = 'https://www.demo.Qlicknpay.com/merchant/api/v1/opencart_receiver';

        $data['Qlicknpay_order_id'] = $this->session->data['order_id'];
        $data['payment_Qlicknpay_merchant_id'] = $this->config->get('payment_Qlicknpay_merchant_id');
        $data['Qlicknpay_detail'] = "Payment_for_order_".$this->session->data['order_id'];

        $this->load->model('checkout/order');
        
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $data['Qlicknpay_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

        $data['Qlicknpay_amount'] = str_replace('$', '', $data['Qlicknpay_amount']);
        $data['Qlicknpay_amount'] = str_replace(',', '', $data['Qlicknpay_amount']);
        $data['Qlicknpay_hash'] = md5($data['payment_Qlicknpay_merchant_id'].$this->config->get('payment_Qlicknpay_API').$data['Qlicknpay_detail'].$data['Qlicknpay_amount'].$this->session->data['order_id']);

        $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$this->session->data['order_id'] . "'");
        $i = 0;
        foreach ($order_product_query->rows as $order_product)
          {
            $i++;
            $data['Qlicknpay_product_name'][$i] =$order_product['name'];
            $data['Qlicknpay_product_price'][$i] =$order_product['price'];
            $data['Qlicknpay_product_quantity'][$i] =$order_product['quantity'];

            $product_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $order_product['product_id'] . "'");
            foreach ($product_data->rows as $product)
            {
            $data['Qlicknpay_product_img'][$i] =$product['image'];
            }


          }
          $data['counter'] = $i;


        if ($this->customer->isLogged()) {
            $name = $this->customer->getFirstName().' '.$this->customer->getLastName();
            $email = $this->customer->getEmail();
            $phone = $this->customer->getTelephone();
        } elseif (isset($this->session->data['guest'])) {
            $name = $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'];
            $email = $this->session->data['guest']['email'];
            $phone = $this->session->data['guest']['telephone'];
        } else {
            $name = '';
            $email = '';
            $phone = '';
        }

        $data['Qlicknpay_customer_name'] = $name;
        $data['Qlicknpay_email'] = $email;
        $data['Qlicknpay_phone'] = $phone;

        return $this->load->view('extension/payment/Qlicknpay', $data);
    }

    public function callback_success()
    {
        $this->load->language('extension/payment/Qlicknpay');
        $this->document->setTitle($this->language->get('text_payment_title'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        if($this->cart->hasProducts())
        {
          $this->cart->clear();
        }

        $this->response->redirect($this->url->link('checkout/success', '', true));


    }

    public function callback_failed()
    {
        $this->load->language('extension/payment/Qlicknpay');
        $this->document->setTitle($this->language->get('text_payment_title'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/payment/Qlicknpay_failed', $data));

    }

    public function callback_response()
    {
        if((isset($this->request->post['fpx_fpxTxnId']) || isset($this->request->post['paypal_trx_id']) || isset($this->request->post['mastercard_trx_id']) || isset($this->request->post['others_trx_id'])) && isset($this->request->post['invoice']) && isset($this->request->post['msg']) && isset($this->request->post['hash']))
        {

            $invoice = urldecode($this->request->post['invoice']);
            $msg = urldecode($this->request->post['msg']);
            $pay_method = urldecode($this->request->post['pay_method']);
            $hash = urldecode($this->request->post['hash']);

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
                if($order_info['order_status_id'] != $this->config->get('payment_Qlicknpay_order_status_id'))
                {
                    $hash_value = md5($this->config->get('payment_Qlicknpay_API').$trx_id.$invoice.$msg);

                    if($hash_value === $hash)
                    {
                      echo "OK";

                        if($msg == 'Transaction Approved' || $msg == 'Success')
                        {
                            $this->model_checkout_order->addOrderHistory($invoice, $this->config->get('payment_Qlicknpay_order_status_id'), 'Payment was made using Qlicknpay through '.$method.'. Qlicknpay transaction id is '.$trx_id, false);
                            if($this->cart->hasProducts())
                                $this->cart->clear();
                        }
                    }
                }
            }
        }
    }
}
