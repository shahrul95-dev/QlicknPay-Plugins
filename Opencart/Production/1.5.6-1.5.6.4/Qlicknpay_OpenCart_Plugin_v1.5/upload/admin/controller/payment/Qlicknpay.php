<?php
class ControllerPaymentQlicknpay extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/Qlicknpay');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if(($this->request->server['REQUEST_METHOD'] == 'POST'))
        {
            $this->model_setting_setting->editSetting('Qlicknpay', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_edit'] = $this->language->get('text_edit');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['entry_order_status'] = $this->language->get('entry_order_status');
        $this->data['entry_order_fail_status'] = $this->language->get('entry_order_fail_status');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        $this->data['entry_API_key'] = $this->language->get('entry_API_key');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['help_order_status'] = $this->language->get('help_order_status');
        $this->data['help_order_fail_status'] = $this->language->get('help_order_fail_status');
        $this->data['help_merchant_id'] = $this->language->get('help_merchant_id');
        $this->data['help_API_key'] = $this->language->get('help_API_key');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

	$this->data['text_return_parameter_uri'] = $this->language->get('text_return_parameter_uri');
	$this->data['help_copy_return_url'] = $this->language->get('help_copy_return_url');

        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/Qlicknpay', 'token='.$this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/Qlicknpay', 'token='.$this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');

        if (isset($this->error['warning'])) {
			       $this->data['error_warning'] = $this->error['warning'];
		    } else {
			       $this->data['error_warning'] = '';
		    }

        if (isset($this->request->post['Qlicknpay_merchant_id']))
            $this->data['Qlicknpay_merchant_id'] = $this->request->post['Qlicknpay_merchant_id'];
        else
            $this->data['Qlicknpay_merchant_id'] = $this->config->get('Qlicknpay_merchant_id');

        if (isset($this->request->post['Qlicknpay_API_key']))
            $this->data['Qlicknpay_API_key'] = $this->request->post['Qlicknpay_API_key'];
        else
            $this->data['Qlicknpay_API_key'] = $this->config->get('Qlicknpay_API_key');

        if(isset($this->request->post['Qlicknpay_order_status_id']))
            $this->data['Qlicknpay_order_status_id'] = $this->request->post['Qlicknpay_order_status_id'];
        else
            $this->data['Qlicknpay_order_status_id'] = $this->config->get('Qlicknpay_order_status_id');

        if(isset($this->request->post['Qlicknpay_order_fail_status_id']))
            $this->data['Qlicknpay_order_fail_status_id'] = $this->request->post['Qlicknpay_order_fail_status_id'];
        else
            $this->data['Qlicknpay_order_fail_status_id'] = $this->config->get('Qlicknpay_order_fail_status_id');

        //SETTING THE RETURN AND CALLBACK URL//
        $current_uri = 'http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'];
        $var = explode('/admin/', $current_uri);
        $beforeAdmin = $var[0];

	    $this->data['Qlicknpay_return_uri'] = $beforeAdmin.'/?route=payment/Qlicknpay/processing';

	    $this->data['Qlicknpay_callback_uri'] = $beforeAdmin.'/?route=payment/Qlicknpay/callback';

        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['Qlicknpay_status'])) {
                $this->data['Qlicknpay_status'] = $this->request->post['Qlicknpay_status'];
        } else {
                $this->data['Qlicknpay_status'] = $this->config->get('Qlicknpay_status');
        }

        if (isset($this->request->post['Qlicknpay_sort_order'])) {
          $this->data['Qlicknpay_sort_order'] = $this->request->post['Qlicknpay_sort_order'];
        } else {
          $this->data['Qlicknpay_sort_order'] = $this->config->get('Qlicknpay_sort_order');
        }

        $this->template = 'payment/Qlicknpay.tpl';
		    $this->children = array(
			          'common/header',
			          'common/footer'
		            );

		    $this->response->setOutput($this->render());

    }
}
