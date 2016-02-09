<?php
class ControllerRecurringPPExpress extends Controller {
	public function index() {
		$this->load->language('recurring/pp_express');
		
		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}
		
		$this->load->model('account/recurring');

		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);
		
		if ($recurring_info) {
			$data['text_loading'] = $this->language->get('text_loading');
			
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_cancel'] = $this->language->get('button_cancel');
			
			$data['continue'] = $this->url->link('account/recurring', '', true);	
			
			//if ($recurring_info['status'] == 2 || $recurring_info['status'] == 3) {
				$data['order_recurring_id'] = $order_recurring_id;
			//} else {
			//	$data['order_recurring_id'] = '';
			//}

			return $this->load->view('recurring/pp_express', $data);
		}
	}
	
	public function cancel() {
		$json = array();
		
		$this->load->language('recurring/recurring');
		
		//cancel an active recurring
		$this->load->model('account/recurring');
		
		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}
		
		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);

		if ($recurring_info && $recurring_info['reference']) {
			if ($this->config->get('pp_express_test')) {
				$api_url = 'https://api-3t.sandbox.paypal.com/nvp';
				$api_user = $this->config->get('pp_express_sandbox_username');
				$api_password = $this->config->get('pp_express_sandbox_password');
				$api_signature = $this->config->get('pp_express_sandbox_signature');
			} else {
				$api_url = 'https://api-3t.paypal.com/nvp';
				$api_user = $this->config->get('pp_express_username');
				$api_password = $this->config->get('pp_express_password');
				$api_signature = $this->config->get('pp_express_signature');
			}
		
			$settings = array(
				'USER'         => $api_user,
				'PWD'          => $api_password,
				'SIGNATURE'    => $api_signature,
				'VERSION'      => '109.0',
				'BUTTONSOURCE' => 'OpenCart_2.0_EC'
			);
		
			
		
			$defaults = array(
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_URL => $api_url,
				CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT => 1,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_FORBID_REUSE => 1,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&")
			);
		$this->log($data, 'Call data');
			
		
			curl_setopt_array($ch, $defaults);
		
			if (!$result = curl_exec($ch)) {
				$this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
			}
		
			$this->log($result, 'Result');
		
			curl_close($ch);



			$curl = curl_init($api_url);

			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$ressponse = curl_exec($ch);

			$this->load->model('payment/pp_express');



			$result = $this->model_payment_pp_express->recurringCancel($recurring_info['reference']);


			if (isset($result['PROFILEID'])) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '5'");
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "' LIMIT 1");

				$json['success'] = $this->language->get('text_cancelled');
			} else {
				$json['error'] = sprintf($this->language->get('error_not_cancelled'), $result['L_LONGMESSAGE0']);
			}
		} else {
			$json['error'] = $this->language->get('error_not_found');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}