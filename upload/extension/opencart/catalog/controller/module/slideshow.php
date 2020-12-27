<?php
namespace Opencart\Application\Controller\Extension\Opencart\Module;
class Slideshow extends \Opencart\System\Engine\Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->language('extension/opencart/module/slideshow');

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$data['banners'] = [];

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$data['banners'][] = [
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $setting['width'], $setting['height'])
				];
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/opencart/module/slideshow', $data);
	}
}