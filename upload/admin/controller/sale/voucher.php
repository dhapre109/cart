<?php
namespace Opencart\Admin\Controller\Sale;
class Voucher extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		$this->getList();
	}

	public function add(): void {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		$this->model_sale_voucher->addVoucher($this->request->post);

		$this->getForm();
	}

	public function edit(): void {
		$this->load->language('sale/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/voucher');

		$this->model_sale_voucher->editVoucher($this->request->get['voucher_id'], $this->request->post);

		$this->getForm();
	}

	protected function getList(): void {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'v.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('sale/voucher|add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/voucher|delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['vouchers'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$voucher_total = $this->model_sale_voucher->getTotalVouchers();

		$results = $this->model_sale_voucher->getVouchers($filter_data);

		foreach ($results as $result) {
			if ($result['order_id']) {	
				$order_href = $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url);
			} else {
				$order_href = '';
			}
			
			$data['vouchers'][] = [
				'voucher_id' => $result['voucher_id'],
				'code'       => $result['code'],
				'from'       => $result['from_name'],
				'to'         => $result['to_name'],
				'theme'      => $result['theme'],
				'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('sale/voucher|edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $result['voucher_id'] . $url),
				'order'      => $order_href
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_code'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.code' . $url);
		$data['sort_from'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.from_name' . $url);
		$data['sort_to'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.to_name' . $url);
		$data['sort_theme'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=theme' . $url);
		$data['sort_amount'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.amount' . $url);
		$data['sort_status'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.status' . $url);
		$data['sort_date_added'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . '&sort=v.date_added' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $voucher_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($voucher_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($voucher_total - $this->config->get('config_pagination_admin'))) ? $voucher_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $voucher_total, ceil($voucher_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_list', $data));
	}

	public function form(): void {
		$data['text_form'] = !isset($this->request->get['voucher_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->request->get['voucher_id'])) {
			$data['voucher_id'] = (int)$this->request->get['voucher_id'];
		} else {
			$data['voucher_id'] = 0;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['voucher_id'])) {
			$data['action'] = $this->url->link('sale/voucher|add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('sale/voucher|edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . (int)$this->request->get['voucher_id'] . $url);
		}

		$data['cancel'] = $this->url->link('sale/voucher', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['voucher_id']) && (!$this->request->server['REQUEST_METHOD'] != 'POST')) {
			$voucher_info = $this->model_sale_voucher->getVoucher($this->request->get['voucher_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($voucher_info)) {
			$data['code'] = $voucher_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['from_name'])) {
			$data['from_name'] = $this->request->post['from_name'];
		} elseif (!empty($voucher_info)) {
			$data['from_name'] = $voucher_info['from_name'];
		} else {
			$data['from_name'] = '';
		}

		if (isset($this->request->post['from_email'])) {
			$data['from_email'] = $this->request->post['from_email'];
		} elseif (!empty($voucher_info)) {
			$data['from_email'] = $voucher_info['from_email'];
		} else {
			$data['from_email'] = '';
		}

		if (isset($this->request->post['to_name'])) {
			$data['to_name'] = $this->request->post['to_name'];
		} elseif (!empty($voucher_info)) {
			$data['to_name'] = $voucher_info['to_name'];
		} else {
			$data['to_name'] = '';
		}

		if (isset($this->request->post['to_email'])) {
			$data['to_email'] = $this->request->post['to_email'];
		} elseif (!empty($voucher_info)) {
			$data['to_email'] = $voucher_info['to_email'];
		} else {
			$data['to_email'] = '';
		}

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		if (isset($this->request->post['voucher_theme_id'])) {
			$data['voucher_theme_id'] = (int)$this->request->post['voucher_theme_id'];
		} elseif (!empty($voucher_info)) {
			$data['voucher_theme_id'] = $voucher_info['voucher_theme_id'];
		} else {
			$data['voucher_theme_id'] = '';
		}

		if (isset($this->request->post['message'])) {
			$data['message'] = $this->request->post['message'];
		} elseif (!empty($voucher_info)) {
			$data['message'] = $voucher_info['message'];
		} else {
			$data['message'] = '';
		}

		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} elseif (!empty($voucher_info)) {
			$data['amount'] = $voucher_info['amount'];
		} else {
			$data['amount'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = (int)$this->request->post['status'];
		} elseif (!empty($voucher_info)) {
			$data['status'] = $voucher_info['status'];
		} else {
			$data['status'] = 1;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/voucher_form', $data));
	}

	public function save(): void {
		$this->load->language('sale/voucher');
		
		$json = [];
		
		if (!$this->user->hasPermission('modify', 'sale/voucher')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 10)) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		$voucher_info = $this->model_sale_voucher->getVoucherByCode($this->request->post['code']);

		if ($voucher_info) {
			if (!isset($this->request->get['voucher_id'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			} elseif ($voucher_info['voucher_id'] != (int)$this->request->get['voucher_id'])  {
				$json['error']['warning'] = $this->language->get('error_exists');
			}
		}

		if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
			$json['error']['to_name'] = $this->language->get('error_to_name');
		}

		if ((utf8_strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['to_email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
			$json['error']['from_name'] = $this->language->get('error_from_name');
		}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['from_email'] = $this->language->get('error_email');
		}

		if ($this->request->post['amount'] < 1) {
			$json['error']['amount'] = $this->language->get('error_amount');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('sale/voucher');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'sale/voucher')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/order');

		foreach ($selected as $voucher_id) {
			$order_voucher_info = $this->model_sale_order->getVoucherByVoucherId($voucher_id);

			if ($order_voucher_info) {
				$json['error'] = sprintf($this->language->get('error_order'), $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_voucher_info['order_id']));

				break;
			}
		}

		if (!$json) {
			$this->load->model('sale/voucher');

			foreach ($selected as $voucher_id) {
				$this->model_sale_voucher->deleteVoucher($voucher_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history(): void {
		$this->load->language('sale/voucher');

		$this->load->model('sale/voucher');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = [];

		$results = $this->model_sale_voucher->getHistories($this->request->get['voucher_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = [
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$history_total = $this->model_sale_voucher->getTotalHistories($this->request->get['voucher_id']);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $history_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('sale/voucher|history', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $this->request->get['voucher_id'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/voucher_history', $data));
	}

	public function send(): void {
		$this->load->language('mail/voucher');

		$json = [];

		if (!$this->user->hasPermission('modify', 'sale/voucher')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('sale/voucher');

			$vouchers = [];

			if (isset($this->request->post['selected'])) {
				$vouchers = (array)$this->request->post['selected'];
			} elseif (isset($this->request->post['voucher_id'])) {
				$vouchers[] = (int)$this->request->post['voucher_id'];
			}

			if ($vouchers) {
				foreach ($vouchers as $voucher_id) {
					$this->load->controller('mail/voucher', $voucher_id);
				}

				$json['success'] = $this->language->get('text_sent');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
