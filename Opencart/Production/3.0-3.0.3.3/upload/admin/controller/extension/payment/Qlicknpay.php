<?php
class ControllerExtensionPaymentQlicknpay extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('extension/payment/Qlicknpay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
            $this->model_setting_setting->editSetting('payment_Qlicknpay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        $data['entry_API'] = $this->language->get('entry_API');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_order_status'] = $this->language->get('entry_order_status');

        // $data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        // $data['entry_secret_key'] = $this->language->get('entry_secret_key');

        $data['help_merchant_id'] = $this->language->get('help_merchant_id');
        $data['help_API'] = $this->language->get('help_API');
        $data['help_order_status'] = $this->language->get('help_order_status');

        // $data['help_secret_key'] = $this->language->get('help_secret_key');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/Qlicknpay', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/Qlicknpay', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


        if (isset($this->request->post['payment_Qlicknpay_merchant_id']))
            $data['payment_Qlicknpay_merchant_id'] = $this->request->post['payment_Qlicknpay_merchant_id'];
        else
            $data['payment_Qlicknpay_merchant_id'] = $this->config->get('payment_Qlicknpay_merchant_id');

        if (isset($this->request->post['payment_Qlicknpay_API']))
            $data['payment_Qlicknpay_API'] = $this->request->post['payment_Qlicknpay_API'];
        else
            $data['payment_Qlicknpay_API'] = $this->config->get('payment_Qlicknpay_API');

        if(isset($this->request->post['payment_Qlicknpay_order_status_id']))
            $data['payment_Qlicknpay_order_status_id'] = $this->request->post['payment_Qlicknpay_order_status_id'];
        else
            $data['payment_Qlicknpay_order_status_id'] = $this->config->get('payment_Qlicknpay_order_status_id');

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_Qlicknpay_sort_order'])) {
            $data['payment_Qlicknpay_sort_order'] = $this->request->post['payment_Qlicknpay_sort_order'];
        } else {
            $data['payment_Qlicknpay_sort_order'] = $this->config->get('payment_Qlicknpay_sort_order');
        }

        if (isset($this->request->post['payment_Qlicknpay_status'])) {
                $data['payment_Qlicknpay_status'] = $this->request->post['payment_Qlicknpay_status'];
        } else {
                $data['payment_Qlicknpay_status'] = $this->config->get('payment_Qlicknpay_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/Qlicknpay', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/Qlicknpay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
