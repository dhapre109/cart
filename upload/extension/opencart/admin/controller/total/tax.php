<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Total;
/**
 * Class Tax
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Total
 */
class Tax extends \Opencart\System\Engine\Controller {
	/**
	 * @var array
	 */
	private array $error = [];

	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/total/tax');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/total/tax')
		];

		$data['save'] = $this->url->link('extension/opencart/total/tax.save');
		$data['back'] = $this->url->link('marketplace/extension', 'type=total');

		$data['total_tax_status'] = $this->config->get('total_tax_status');
		$data['total_tax_sort_order'] = $this->config->get('total_tax_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/total/tax', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/total/tax');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/total/tax')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('total_tax', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
