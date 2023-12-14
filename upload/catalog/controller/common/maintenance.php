<?php
namespace Opencart\Catalog\Controller\Common;
use Melbahja\Seo\MetaTags;

/**
 * Class Maintenance
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Maintenance extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/maintenance');

		$metadata = new MetaTags();
		$metadata
			->title($this->language->get('heading_title'));
		$this->document->setSeo($metadata);

		if ($this->request->server['SERVER_PROTOCOL'] == 'HTTP/1.1') {
			$this->response->addHeader('HTTP/1.1 503 Service Unavailable');
		} else {
			$this->response->addHeader('HTTP/1.0 503 Service Unavailable');
		}

		$this->response->addHeader('Retry-After: 3600');

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_maintenance'),
			'href' => $this->url->link('common/maintenance', 'language=' . $this->config->get('config_language'))
		];

		$data['message'] = $this->language->get('text_message');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/maintenance', $data));
	}
}
