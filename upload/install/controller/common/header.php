<?php
namespace Opencart\Application\Controller\Common;
class Header extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('common/header');
		
		$data['title'] = $this->document->getTitle();
		$data['base'] = HTTP_SERVER;
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		return $this->load->view('common/header', $data);
	}
}
