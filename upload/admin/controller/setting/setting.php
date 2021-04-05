<?php
namespace Opencart\Admin\Controller\Setting;
class Setting extends \Opencart\System\Engine\Controller {
	private array $error = [];

	public function index(): void {
		$this->load->language('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('config', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/store', 'user_token=' . $this->session->data['user_token']));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['owner'])) {
			$data['error_owner'] = $this->error['owner'];
		} else {
			$data['error_owner'] = '';
		}

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}

		// Options
		if (isset($this->error['product_description_length'])) {
			$data['error_product_description_length'] = $this->error['product_description_length'];
		} else {
			$data['error_product_description_length'] = '';
		}

		if (isset($this->error['pagination'])) {
			$data['error_pagination'] = $this->error['pagination'];
		} else {
			$data['error_pagination'] = '';
		}

		if (isset($this->error['pagination_admin'])) {
			$data['error_pagination_admin'] = $this->error['pagination_admin'];
		} else {
			$data['error_pagination_admin'] = '';
		}

		if (isset($this->error['customer_group_display'])) {
			$data['error_customer_group_display'] = $this->error['customer_group_display'];
		} else {
			$data['error_customer_group_display'] = '';
		}

		if (isset($this->error['login_attempts'])) {
			$data['error_login_attempts'] = $this->error['login_attempts'];
		} else {
			$data['error_login_attempts'] = '';
		}

		if (isset($this->error['voucher_min'])) {
			$data['error_voucher_min'] = $this->error['voucher_min'];
		} else {
			$data['error_voucher_min'] = '';
		}

		if (isset($this->error['voucher_max'])) {
			$data['error_voucher_max'] = $this->error['voucher_max'];
		} else {
			$data['error_voucher_max'] = '';
		}

		if (isset($this->error['processing_status'])) {
			$data['error_processing_status'] = $this->error['processing_status'];
		} else {
			$data['error_processing_status'] = '';
		}

		if (isset($this->error['complete_status'])) {
			$data['error_complete_status'] = $this->error['complete_status'];
		} else {
			$data['error_complete_status'] = '';
		}

		// Image
		if (isset($this->error['image_category'])) {
			$data['error_image_category'] = $this->error['image_category'];
		} else {
			$data['error_image_category'] = '';
		}

		if (isset($this->error['image_thumb'])) {
			$data['error_image_thumb'] = $this->error['image_thumb'];
		} else {
			$data['error_image_thumb'] = '';
		}

		if (isset($this->error['image_popup'])) {
			$data['error_image_popup'] = $this->error['image_popup'];
		} else {
			$data['error_image_popup'] = '';
		}

		if (isset($this->error['image_product'])) {
			$data['error_image_product'] = $this->error['image_product'];
		} else {
			$data['error_image_product'] = '';
		}

		if (isset($this->error['image_additional'])) {
			$data['error_image_additional'] = $this->error['image_additional'];
		} else {
			$data['error_image_additional'] = '';
		}

		if (isset($this->error['image_related'])) {
			$data['error_image_related'] = $this->error['image_related'];
		} else {
			$data['error_image_related'] = '';
		}

		if (isset($this->error['image_compare'])) {
			$data['error_image_compare'] = $this->error['image_compare'];
		} else {
			$data['error_image_compare'] = '';
		}

		if (isset($this->error['image_wishlist'])) {
			$data['error_image_wishlist'] = $this->error['image_wishlist'];
		} else {
			$data['error_image_wishlist'] = '';
		}

		if (isset($this->error['image_cart'])) {
			$data['error_image_cart'] = $this->error['image_cart'];
		} else {
			$data['error_image_cart'] = '';
		}

		if (isset($this->error['image_location'])) {
			$data['error_image_location'] = $this->error['image_location'];
		} else {
			$data['error_image_location'] = '';
		}

		if (isset($this->error['log'])) {
			$data['error_log'] = $this->error['log'];
		} else {
			$data['error_log'] = '';
		}

		if (isset($this->error['encryption'])) {
			$data['error_encryption'] = $this->error['encryption'];
		} else {
			$data['error_encryption'] = '';
		}

		if (isset($this->error['file_max_size'])) {
			$data['error_file_max_size'] = $this->error['file_max_size'];
		} else {
			$data['error_file_max_size'] = '';
		}

		if (isset($this->error['extension'])) {
			$data['error_extension'] = $this->error['extension'];
		} else {
			$data['error_extension'] = '';
		}

		if (isset($this->error['mime'])) {
			$data['error_mime'] = $this->error['mime'];
		} else {
			$data['error_mime'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/setting', 'user_token=' . $this->session->data['user_token'])
		];

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('setting/setting', 'user_token=' . $this->session->data['user_token']);
		$data['cancel'] = $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		// General
		if (isset($this->request->post['config_meta_title'])) {
			$data['config_meta_title'] = $this->request->post['config_meta_title'];
		} else {
			$data['config_meta_title'] = $this->config->get('config_meta_title');
		}

		if (isset($this->request->post['config_meta_description'])) {
			$data['config_meta_description'] = $this->request->post['config_meta_description'];
		} else {
			$data['config_meta_description'] = $this->config->get('config_meta_description');
		}

		if (isset($this->request->post['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
		} else {
			$data['config_meta_keyword'] = $this->config->get('config_meta_keyword');
		}

		if (isset($this->request->post['config_theme'])) {
			$data['config_theme'] = $this->request->post['config_theme'];
		} else {
			$data['config_theme'] = $this->config->get('config_theme');
		}

		$data['store_url'] = HTTP_CATALOG;

		$data['themes'] = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('theme');

		foreach ($extensions as $extension) {
			if ($this->config->get('theme_' . $extension['code'] . '_status')) {
				$this->load->language('extension/' . $extension['extension'] . '/theme/' . $extension['code'], 'extension');

				$data['themes'][] = [
					'text'  => $this->language->get('extension_heading_title'),
					'value' => $extension['code']
				];
			}
		}

		if (isset($this->request->post['config_layout_id'])) {
			$data['config_layout_id'] = (int)$this->request->post['config_layout_id'];
		} else {
			$data['config_layout_id'] = $this->config->get('config_layout_id');
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		// Store Details
		if (isset($this->request->post['config_name'])) {
			$data['config_name'] = $this->request->post['config_name'];
		} else {
			$data['config_name'] = $this->config->get('config_name');
		}

		if (isset($this->request->post['config_owner'])) {
			$data['config_owner'] = $this->request->post['config_owner'];
		} else {
			$data['config_owner'] = $this->config->get('config_owner');
		}

		if (isset($this->request->post['config_address'])) {
			$data['config_address'] = $this->request->post['config_address'];
		} else {
			$data['config_address'] = $this->config->get('config_address');
		}

		if (isset($this->request->post['config_geocode'])) {
			$data['config_geocode'] = $this->request->post['config_geocode'];
		} else {
			$data['config_geocode'] = $this->config->get('config_geocode');
		}

		if (isset($this->request->post['config_email'])) {
			$data['config_email'] = $this->request->post['config_email'];
		} else {
			$data['config_email'] = $this->config->get('config_email');
		}

		if (isset($this->request->post['config_telephone'])) {
			$data['config_telephone'] = $this->request->post['config_telephone'];
		} else {
			$data['config_telephone'] = $this->config->get('config_telephone');
		}

		if (isset($this->request->post['config_image'])) {
			$data['config_image'] = $this->request->post['config_image'];
		} else {
			$data['config_image'] = $this->config->get('config_image');
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['config_image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['config_image'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		if (isset($this->request->post['config_open'])) {
			$data['config_open'] = $this->request->post['config_open'];
		} else {
			$data['config_open'] = $this->config->get('config_open');
		}

		if (isset($this->request->post['config_comment'])) {
			$data['config_comment'] = $this->request->post['config_comment'];
		} else {
			$data['config_comment'] = $this->config->get('config_comment');
		}

		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		if (isset($this->request->post['config_location'])) {
			$data['config_location'] = (array)$this->request->post['config_location'];
		} elseif ($this->config->get('config_location')) {
			$data['config_location'] = $this->config->get('config_location');
		} else {
			$data['config_location'] = [];
		}

		// Localisation
		if (isset($this->request->post['config_country_id'])) {
			$data['config_country_id'] = (int)$this->request->post['config_country_id'];
		} else {
			$data['config_country_id'] = $this->config->get('config_country_id');
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$data['config_zone_id'] = (int)$this->request->post['config_zone_id'];
		} else {
			$data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_timezone'])) {
			$data['config_timezone'] = $this->request->post['config_timezone'];
		} elseif ($this->config->has('config_timezone')) {
			$data['config_timezone'] = $this->config->get('config_timezone');
		} else {
			$data['config_timezone'] = 'UTC';
		}

		$data['timezones'] = [];

		$timestamp = time();

		$timezones = timezone_identifiers_list();

		foreach ($timezones as $timezone) {
			date_default_timezone_set($timezone);

			$hour = ' (' . date('P', $timestamp) . ')';

			$data['timezones'][] = [
				'text'  => $timezone . $hour,
				'value' => $timezone
			];
		}

		date_default_timezone_set($this->config->get('config_timezone'));
/*
		$_config = new Config();
		$_config->load('default');

		$date_timezone = $_config->get('date_timezone');

		$config_timezone = array_replace((array)$date_timezone, (array)$this->config->get('config_timezone'));

		date_default_timezone_set($config_timezone);
*/

		if (isset($this->request->post['config_language'])) {
			$data['config_language'] = $this->request->post['config_language'];
		} else {
			$data['config_language'] = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_language_admin'])) {
			$data['config_language_admin'] = $this->request->post['config_language_admin'];
		} else {
			$data['config_language_admin'] = $this->config->get('config_language_admin');
		}

		if (isset($this->request->post['config_currency'])) {
			$data['config_currency'] = $this->request->post['config_currency'];
		} else {
			$data['config_currency'] = $this->config->get('config_currency');
		}

		$data['currency_engines'] = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('currency');

		foreach ($extensions as $extension) {
			if ($this->config->get('currency_' . $extension['code'] . '_status')) {
				$this->load->language('extension/' . $extension['extension'] . '/currency/' . $extension['code'], 'extension');

				$data['currency_engines'][] = [
					'text'  => $this->language->get('extension_heading_title'),
					'value' => $extension['code']
				];
			}
		}

		if (isset($this->request->post['config_currency_engine'])) {
			$data['config_currency_engine'] = $this->request->post['config_currency_engine'];
		} else {
			$data['config_currency_engine'] = $this->config->get('config_currency_engine');
		}

		if (isset($this->request->post['config_currency_auto'])) {
			$data['config_currency_auto'] = $this->request->post['config_currency_auto'];
		} else {
			$data['config_currency_auto'] = $this->config->get('config_currency_auto');
		}

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->request->post['config_length_class_id'])) {
			$data['config_length_class_id'] = (int)$this->request->post['config_length_class_id'];
		} else {
			$data['config_length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['config_weight_class_id'])) {
			$data['config_weight_class_id'] = (int)$this->request->post['config_weight_class_id'];
		} else {
			$data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		// Options
		if (isset($this->request->post['config_product_description_length'])) {
			$data['config_product_description_length'] = (int)$this->request->post['config_product_description_length'];
		} elseif ($this->config->get('config_product_description_length')) {
			$data['config_product_description_length'] = $this->config->get('config_product_description_length');
		} else {
			$data['config_product_description_length'] = 100;
		}

		if (isset($this->request->post['config_pagination'])) {
			$data['config_pagination'] = (int)$this->request->post['config_pagination'];
		} elseif ($this->config->get('config_pagination')) {
			$data['config_pagination'] = $this->config->get('config_pagination');
		} else {
			$data['config_pagination'] = 15;
		}

		if (isset($this->request->post['config_product_count'])) {
			$data['config_product_count'] = (int)$this->request->post['config_product_count'];
		} else {
			$data['config_product_count'] = $this->config->get('config_product_count');
		}

		if (isset($this->request->post['config_pagination_admin'])) {
			$data['config_pagination_admin'] = (int)$this->request->post['config_pagination_admin'];
		} elseif ($this->config->get('config_pagination_admin')) {
			$data['config_pagination_admin'] = $this->config->get('config_pagination_admin');
		} else {
			$data['config_pagination_admin'] = 10;
		}

		if (isset($this->request->post['config_review_status'])) {
			$data['config_review_status'] = $this->request->post['config_review_status'];
		} else {
			$data['config_review_status'] = $this->config->get('config_review_status');
		}

		if (isset($this->request->post['config_review_guest'])) {
			$data['config_review_guest'] = $this->request->post['config_review_guest'];
		} else {
			$data['config_review_guest'] = $this->config->get('config_review_guest');
		}

		if (isset($this->request->post['config_voucher_min'])) {
			$data['config_voucher_min'] = (int)$this->request->post['config_voucher_min'];
		} else {
			$data['config_voucher_min'] = $this->config->get('config_voucher_min');
		}

		if (isset($this->request->post['config_voucher_max'])) {
			$data['config_voucher_max'] = (int)$this->request->post['config_voucher_max'];
		} else {
			$data['config_voucher_max'] = $this->config->get('config_voucher_max');
		}

		if (isset($this->request->post['config_cookie_id'])) {
			$data['config_cookie_id'] = (int)$this->request->post['config_cookie_id'];
		} else {
			$data['config_cookie_id'] = $this->config->get('config_cookie_id');
		}

		if (isset($this->request->post['config_gdpr_id'])) {
			$data['config_gdpr_id'] = (int)$this->request->post['config_gdpr_id'];
		} else {
			$data['config_gdpr_id'] = $this->config->get('config_gdpr_id');
		}

		if (isset($this->request->post['config_gdpr_limit'])) {
			$data['config_gdpr_limit'] = (int)$this->request->post['config_gdpr_limit'];
		} else {
			$data['config_gdpr_limit'] = $this->config->get('config_gdpr_limit');
		}

		if (isset($this->request->post['config_tax'])) {
			$data['config_tax'] = $this->request->post['config_tax'];
		} else {
			$data['config_tax'] = $this->config->get('config_tax');
		}

		if (isset($this->request->post['config_tax_default'])) {
			$data['config_tax_default'] = $this->request->post['config_tax_default'];
		} else {
			$data['config_tax_default'] = $this->config->get('config_tax_default');
		}

		if (isset($this->request->post['config_tax_customer'])) {
			$data['config_tax_customer'] = $this->request->post['config_tax_customer'];
		} else {
			$data['config_tax_customer'] = $this->config->get('config_tax_customer');
		}

		if (isset($this->request->post['config_customer_online'])) {
			$data['config_customer_online'] = $this->request->post['config_customer_online'];
		} else {
			$data['config_customer_online'] = $this->config->get('config_customer_online');
		}

		if (isset($this->request->post['config_customer_activity'])) {
			$data['config_customer_activity'] = $this->request->post['config_customer_activity'];
		} else {
			$data['config_customer_activity'] = $this->config->get('config_customer_activity');
		}

		if (isset($this->request->post['config_customer_search'])) {
			$data['config_customer_search'] = $this->request->post['config_customer_search'];
		} else {
			$data['config_customer_search'] = $this->config->get('config_customer_search');
		}

		if (isset($this->request->post['config_customer_group_id'])) {
			$data['config_customer_group_id'] = (int)$this->request->post['config_customer_group_id'];
		} else {
			$data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->post['config_customer_group_display'])) {
			$data['config_customer_group_display'] = (array)$this->request->post['config_customer_group_display'];
		} elseif ($this->config->get('config_customer_group_display')) {
			$data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
		} else {
			$data['config_customer_group_display'] = [];
		}

		if (isset($this->request->post['config_customer_price'])) {
			$data['config_customer_price'] = $this->request->post['config_customer_price'];
		} else {
			$data['config_customer_price'] = $this->config->get('config_customer_price');
		}

		if (isset($this->request->post['config_login_attempts'])) {
			$data['config_login_attempts'] = (int)$this->request->post['config_login_attempts'];
		} elseif ($this->config->has('config_login_attempts')) {
			$data['config_login_attempts'] = $this->config->get('config_login_attempts');
		} else {
			$data['config_login_attempts'] = 5;
		}

		if (isset($this->request->post['config_account_id'])) {
			$data['config_account_id'] = (int)$this->request->post['config_account_id'];
		} else {
			$data['config_account_id'] = $this->config->get('config_account_id');
		}

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['config_cart_weight'])) {
			$data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		} else {
			$data['config_cart_weight'] = $this->config->get('config_cart_weight');
		}

		if (isset($this->request->post['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $this->request->post['config_checkout_guest'];
		} else {
			$data['config_checkout_guest'] = $this->config->get('config_checkout_guest');
		}

		if (isset($this->request->post['config_checkout_id'])) {
			$data['config_checkout_id'] = (int)$this->request->post['config_checkout_id'];
		} else {
			$data['config_checkout_id'] = $this->config->get('config_checkout_id');
		}

		if (isset($this->request->post['config_invoice_prefix'])) {
			$data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
		} elseif ($this->config->get('config_invoice_prefix')) {
			$data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
		} else {
			$data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
		}

		if (isset($this->request->post['config_order_status_id'])) {
			$data['config_order_status_id'] = (int)$this->request->post['config_order_status_id'];
		} else {
			$data['config_order_status_id'] = $this->config->get('config_order_status_id');
		}

		if (isset($this->request->post['config_processing_status'])) {
			$data['config_processing_status'] = (array)$this->request->post['config_processing_status'];
		} elseif ($this->config->get('config_processing_status')) {
			$data['config_processing_status'] = $this->config->get('config_processing_status');
		} else {
			$data['config_processing_status'] = [];
		}

		if (isset($this->request->post['config_complete_status'])) {
			$data['config_complete_status'] = (array)$this->request->post['config_complete_status'];
		} elseif ($this->config->get('config_complete_status')) {
			$data['config_complete_status'] = $this->config->get('config_complete_status');
		} else {
			$data['config_complete_status'] = [];
		}

		if (isset($this->request->post['config_fraud_status_id'])) {
			$data['config_fraud_status_id'] = (int)$this->request->post['config_fraud_status_id'];
		} else {
			$data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_api_id'])) {
			$data['config_api_id'] = (int)$this->request->post['config_api_id'];
		} else {
			$data['config_api_id'] = $this->config->get('config_api_id');
		}

		$this->load->model('user/api');

		$data['apis'] = $this->model_user_api->getApis();

		if (isset($this->request->post['config_stock_display'])) {
			$data['config_stock_display'] = $this->request->post['config_stock_display'];
		} else {
			$data['config_stock_display'] = $this->config->get('config_stock_display');
		}

		if (isset($this->request->post['config_stock_warning'])) {
			$data['config_stock_warning'] = $this->request->post['config_stock_warning'];
		} else {
			$data['config_stock_warning'] = $this->config->get('config_stock_warning');
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		} else {
			$data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
		}

		if (isset($this->request->post['config_affiliate_status'])) {
			$data['config_affiliate_status'] = (int)$this->request->post['config_affiliate_status'];
		} elseif ($this->config->has('config_affiliate_status')) {
			$data['config_affiliate_status'] = $this->config->get('config_affiliate_status');
		} else {
			$data['config_affiliate_status'] = 1;
		}

		if (isset($this->request->post['config_affiliate_group_id'])) {
			$data['config_affiliate_group_id'] = (int)$this->request->post['config_affiliate_group_id'];
		} else {
			$data['config_affiliate_group_id'] = $this->config->get('config_affiliate_group_id');
		}

		if (isset($this->request->post['config_affiliate_approval'])) {
			$data['config_affiliate_approval'] = $this->request->post['config_affiliate_approval'];
		} elseif ($this->config->has('config_affiliate_approval')) {
			$data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
		} else {
			$data['config_affiliate_approval'] = '';
		}

		if (isset($this->request->post['config_affiliate_auto'])) {
			$data['config_affiliate_auto'] = $this->request->post['config_affiliate_auto'];
		} elseif ($this->config->has('config_affiliate_auto')) {
			$data['config_affiliate_auto'] = $this->config->get('config_affiliate_auto');
		} else {
			$data['config_affiliate_auto'] = '';
		}

		if (isset($this->request->post['config_affiliate_commission'])) {
			$data['config_affiliate_commission'] = (float)$this->request->post['config_affiliate_commission'];
		} elseif ($this->config->has('config_affiliate_commission')) {
			$data['config_affiliate_commission'] = $this->config->get('config_affiliate_commission');
		} else {
			$data['config_affiliate_commission'] = '5.00';
		}

		if (isset($this->request->post['config_affiliate_id'])) {
			$data['config_affiliate_id'] = (int)$this->request->post['config_affiliate_id'];
		} else {
			$data['config_affiliate_id'] = $this->config->get('config_affiliate_id');
		}

		if (isset($this->request->post['config_return_id'])) {
			$data['config_return_id'] = (int)$this->request->post['config_return_id'];
		} else {
			$data['config_return_id'] = $this->config->get('config_return_id');
		}

		if (isset($this->request->post['config_return_status_id'])) {
			$data['config_return_status_id'] = (int)$this->request->post['config_return_status_id'];
		} else {
			$data['config_return_status_id'] = $this->config->get('config_return_status_id');
		}

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		if (isset($this->request->post['config_captcha'])) {
			$data['config_captcha'] = $this->request->post['config_captcha'];
		} else {
			$data['config_captcha'] = $this->config->get('config_captcha');
		}

		$this->load->model('setting/extension');

		$data['captchas'] = [];

		// Get a list of installed captchas
		$extensions = $this->model_setting_extension->getExtensionsByType('captcha');

		foreach ($extensions as $extension) {
			$this->load->language('extension/' . $extension['extension'] . '/captcha/' . $extension['code'], 'extension');

			if ($this->config->get('captcha_' . $extension['code'] . '_status')) {
				$data['captchas'][] = [
					'text'  => $this->language->get('extension_heading_title'),
					'value' => $extension['code']
				];
			}
		}

		if (isset($this->request->post['config_captcha_page'])) {
			$data['config_captcha_page'] = $this->request->post['config_captcha_page'];
		} elseif ($this->config->has('config_captcha_page')) {
		   	$data['config_captcha_page'] = $this->config->get('config_captcha_page');
		} else {
			$data['config_captcha_page'] = [];
		}

		$data['captcha_pages'] = [];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_register'),
			'value' => 'register'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_guest'),
			'value' => 'guest'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_review'),
			'value' => 'review'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_return'),
			'value' => 'return'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_contact'),
			'value' => 'contact'
		];

		// Images
		if (isset($this->request->post['config_logo'])) {
			$data['config_logo'] = $this->request->post['config_logo'];
		} else {
			$data['config_logo'] = $this->config->get('config_logo');
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['config_logo'], ENT_QUOTES, 'UTF-8'))) {
			$data['logo'] = $this->model_tool_image->resize(html_entity_decode($data['config_logo'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['logo'] = $data['placeholder'];
		}

		if (isset($this->request->post['config_icon'])) {
			$data['config_icon'] = $this->request->post['config_icon'];
		} else {
			$data['config_icon'] = $this->config->get('config_icon');
		}

		if (is_file(DIR_IMAGE . html_entity_decode($data['config_icon'], ENT_QUOTES, 'UTF-8'))) {
			$data['icon'] = $this->model_tool_image->resize(html_entity_decode($data['config_icon'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['icon'] = $data['placeholder'];
		}

		if (isset($this->request->post['config_image_category_width'])) {
			$data['config_image_category_width'] = (int)$this->request->post['config_image_category_width'];
		} elseif ($this->config->get('config_image_category_width')) {
			$data['config_image_category_width'] = $this->config->get('config_image_category_width');
		} else {
			$data['config_image_category_width'] = 80;
		}

		if (isset($this->request->post['config_image_category_height'])) {
			$data['config_image_category_height'] = (int)$this->request->post['config_image_category_height'];
		} elseif ($this->config->get('config_image_category_height')) {
			$data['config_image_category_height'] = $this->config->get('config_image_category_height');
		} else {
			$data['config_image_category_height'] = 80;
		}

		if (isset($this->request->post['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = (int)$this->request->post['config_image_thumb_width'];
		} elseif ($this->config->get('config_image_thumb_width')) {
			$data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
		} else {
			$data['config_image_thumb_width'] = 228;
		}

		if (isset($this->request->post['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = (int)$this->request->post['config_image_thumb_height'];
		} elseif ($this->config->get('config_image_thumb_height')) {
			$data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
		} else {
			$data['config_image_thumb_height'] = 228;
		}

		if (isset($this->request->post['config_image_popup_width'])) {
			$data['config_image_popup_width'] = (int)$this->request->post['config_image_popup_width'];
		} elseif ($this->config->get('config_image_popup_width')) {
			$data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
		} else {
			$data['config_image_popup_width'] = 500;
		}

		if (isset($this->request->post['config_image_popup_height'])) {
			$data['config_image_popup_height'] = (int)$this->request->post['config_image_popup_height'];
		} elseif ($this->config->get('config_image_popup_height')) {
			$data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
		} else {
			$data['config_image_popup_height'] = 500;
		}

		if (isset($this->request->post['config_image_product_width'])) {
			$data['config_image_product_width'] = (int)$this->request->post['config_image_product_width'];
		} elseif ($this->config->get('config_image_product_width')) {
			$data['config_image_product_width'] = $this->config->get('config_image_product_width');
		} else {
			$data['config_image_product_width'] = 228;
		}

		if (isset($this->request->post['config_image_product_height'])) {
			$data['config_image_product_height'] = (int)$this->request->post['config_image_product_height'];
		} elseif ($this->config->get('config_image_product_height')) {
			$data['config_image_product_height'] = $this->config->get('config_image_product_height');
		} else {
			$data['config_image_product_height'] = 228;
		}

		if (isset($this->request->post['config_image_additional_width'])) {
			$data['config_image_additional_width'] = (int)$this->request->post['config_image_additional_width'];
		} elseif ($this->config->get('config_image_additional_width')) {
			$data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
		} else {
			$data['config_image_additional_width'] = 74;
		}

		if (isset($this->request->post['config_image_additional_height'])) {
			$data['config_image_additional_height'] = (int)$this->request->post['config_image_additional_height'];
		} elseif ($this->config->get('config_image_additional_height')) {
			$data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
		} else {
			$data['config_image_additional_height'] = 74;
		}

		if (isset($this->request->post['config_image_related_width'])) {
			$data['config_image_related_width'] = (int)$this->request->post['config_image_related_width'];
		} elseif ($this->config->get('config_image_related_width')) {
			$data['config_image_related_width'] = $this->config->get('config_image_related_width');
		} else {
			$data['config_image_related_width'] = 80;
		}

		if (isset($this->request->post['config_image_related_height'])) {
			$data['config_image_related_height'] = (int)$this->request->post['config_image_related_height'];
		} elseif ($this->config->get('config_image_related_height')) {
			$data['config_image_related_height'] = $this->config->get('config_image_related_height');
		} else {
			$data['config_image_related_height'] = 80;
		}

		if (isset($this->request->post['config_image_compare_width'])) {
			$data['config_image_compare_width'] = (int)$this->request->post['config_image_compare_width'];
		} elseif ($this->config->get('config_image_compare_width')) {
			$data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
		} else {
			$data['config_image_compare_width'] = 90;
		}

		if (isset($this->request->post['config_image_compare_height'])) {
			$data['config_image_compare_height'] = (int)$this->request->post['config_image_compare_height'];
		} elseif ($this->config->get('config_image_compare_height')) {
			$data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
		} else {
			$data['config_image_compare_height'] = 90;
		}

		if (isset($this->request->post['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = (int)$this->request->post['config_image_wishlist_width'];
		} elseif ($this->config->get('config_image_wishlist_width')) {
			$data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
		} else {
			$data['config_image_wishlist_width'] = 47;
		}

		if (isset($this->request->post['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = (int)$this->request->post['config_image_wishlist_height'];
		} elseif ($this->config->get('config_image_wishlist_height')) {
			$data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
		} else {
			$data['config_image_wishlist_height'] = 47;
		}

		if (isset($this->request->post['config_image_cart_width'])) {
			$data['config_image_cart_width'] = (int)$this->request->post['config_image_cart_width'];
		} elseif ($this->config->get('config_image_cart_width')) {
			$data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
		} else {
			$data['config_image_cart_width'] = 47;
		}

		if (isset($this->request->post['config_image_cart_height'])) {
			$data['config_image_cart_height'] = (int)$this->request->post['config_image_cart_height'];
		} elseif ($this->config->get('config_image_cart_height')) {
			$data['config_image_cart_height'] =$this->config->get('config_image_cart_height');
		} else {
			$data['config_image_cart_height'] = 47;
		}

		if (isset($this->request->post['config_image_location_width'])) {
			$data['config_image_location_width'] = (int)$this->request->post['config_image_location_width'];
		} elseif ($this->config->get('config_image_location_width')) {
			$data['config_image_location_width'] = $this->config->get('config_image_location_width');
		} else {
			$data['config_image_location_width'] = 268;
		}

		if (isset($this->request->post['config_image_location_height'])) {
			$data['config_image_location_height'] = (int)$this->request->post['config_image_location_height'];
		} elseif ($this->config->get('config_image_location_height')) {
			$data['config_image_location_height'] = $this->config->get('config_image_location_height');
		} else {
			$data['config_image_location_height'] = 50;
		}

		// Mail
		if (isset($this->request->post['config_mail_engine'])) {
			$data['config_mail_engine'] = $this->request->post['config_mail_engine'];
		} else {
			$data['config_mail_engine'] = $this->config->get('config_mail_engine');
		}

		if (isset($this->request->post['config_mail_parameter'])) {
			$data['config_mail_parameter'] = $this->request->post['config_mail_parameter'];
		} else {
			$data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
		}

		if (isset($this->request->post['config_mail_smtp_hostname'])) {
			$data['config_mail_smtp_hostname'] = $this->request->post['config_mail_smtp_hostname'];
		} else {
			$data['config_mail_smtp_hostname'] = $this->config->get('config_mail_smtp_hostname');
		}

		if (isset($this->request->post['config_mail_smtp_username'])) {
			$data['config_mail_smtp_username'] = $this->request->post['config_mail_smtp_username'];
		} else {
			$data['config_mail_smtp_username'] = $this->config->get('config_mail_smtp_username');
		}

		if (isset($this->request->post['config_mail_smtp_password'])) {
			$data['config_mail_smtp_password'] = $this->request->post['config_mail_smtp_password'];
		} else {
			$data['config_mail_smtp_password'] = $this->config->get('config_mail_smtp_password');
		}

		if (isset($this->request->post['config_mail_smtp_port'])) {
			$data['config_mail_smtp_port'] = (int)$this->request->post['config_mail_smtp_port'];
		} elseif ($this->config->has('config_mail_smtp_port')) {
			$data['config_mail_smtp_port'] = $this->config->get('config_mail_smtp_port');
		} else {
			$data['config_mail_smtp_port'] = 25;
		}

		if (isset($this->request->post['config_mail_smtp_timeout'])) {
			$data['config_mail_smtp_timeout'] = (int)$this->request->post['config_mail_smtp_timeout'];
		} elseif ($this->config->has('config_mail_smtp_timeout')) {
			$data['config_mail_smtp_timeout'] = $this->config->get('config_mail_smtp_timeout');
		} else {
			$data['config_mail_smtp_timeout'] = 5;
		}

		if (isset($this->request->post['config_mail_alert'])) {
			$data['config_mail_alert'] = $this->request->post['config_mail_alert'];
		} elseif ($this->config->has('config_mail_alert')) {
		   	$data['config_mail_alert'] = $this->config->get('config_mail_alert');
		} else {
			$data['config_mail_alert'] = [];
		}

		$data['mail_alerts'] = [];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_account'),
			'value' => 'account'
		];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_affiliate'),
			'value' => 'affiliate'
		];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_order'),
			'value' => 'order'
		];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_review'),
			'value' => 'review'
		];

		if (isset($this->request->post['config_mail_alert_email'])) {
			$data['config_mail_alert_email'] = $this->request->post['config_mail_alert_email'];
		} else {
			$data['config_mail_alert_email'] = $this->config->get('config_mail_alert_email');
		}

		// Server
		if (isset($this->request->post['config_shared'])) {
			$data['config_shared'] = $this->request->post['config_shared'];
		} else {
			$data['config_shared'] = $this->config->get('config_shared');
		}

		if (isset($this->request->post['config_robots'])) {
			$data['config_robots'] = $this->request->post['config_robots'];
		} else {
			$data['config_robots'] = $this->config->get('config_robots');
		}

		if (isset($this->request->post['config_seo_url'])) {
			$data['config_seo_url'] = $this->request->post['config_seo_url'];
		} else {
			$data['config_seo_url'] = $this->config->get('config_seo_url');
		}

		if (isset($this->request->post['config_file_max_size'])) {
			$data['config_file_max_size'] = (int)$this->request->post['config_file_max_size'];
		} elseif ($this->config->get('config_file_max_size')) {
			$data['config_file_max_size'] = $this->config->get('config_file_max_size');
		} else {
			$data['config_file_max_size'] = 300000;
		}

		if (isset($this->request->post['config_file_ext_allowed'])) {
			$data['config_file_ext_allowed'] = $this->request->post['config_file_ext_allowed'];
		} else {
			$data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
		}

		if (isset($this->request->post['config_file_mime_allowed'])) {
			$data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
		} else {
			$data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}

		if (isset($this->request->post['config_maintenance'])) {
			$data['config_maintenance'] = $this->request->post['config_maintenance'];
		} else {
			$data['config_maintenance'] = $this->config->get('config_maintenance');
		}

		if (isset($this->request->post['config_password'])) {
			$data['config_password'] = $this->request->post['config_password'];
		} else {
			$data['config_password'] = $this->config->get('config_password');
		}

		if (isset($this->request->post['config_encryption'])) {
			$data['config_encryption'] = $this->request->post['config_encryption'];
		} else {
			$data['config_encryption'] = $this->config->get('config_encryption');
		}

		if (isset($this->request->post['config_compression'])) {
			$data['config_compression'] = $this->request->post['config_compression'];
		} else {
			$data['config_compression'] = $this->config->get('config_compression');
		}

		if (isset($this->request->post['config_error_display'])) {
			$data['config_error_display'] = $this->request->post['config_error_display'];
		} else {
			$data['config_error_display'] = $this->config->get('config_error_display');
		}

		if (isset($this->request->post['config_error_log'])) {
			$data['config_error_log'] = $this->request->post['config_error_log'];
		} else {
			$data['config_error_log'] = $this->config->get('config_error_log');
		}

		if (isset($this->request->post['config_error_filename'])) {
			$data['config_error_filename'] = $this->request->post['config_error_filename'];
		} else {
			$data['config_error_filename'] = $this->config->get('config_error_filename');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting', $data));
	}

	public function save(): void {
		$this->load->language('setting/setting');
		
		$json = [];
		
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_meta_title']) {
			$json['error']['meta_title'] = $this->language->get('error_meta_title');
		}

		if (!$this->request->post['config_name']) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
			$json['error']['owner'] = $this->language->get('error_owner');
		}

		if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
			$json['error']['address'] = $this->language->get('error_address');
		}

		if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->request->post['config_product_description_length']) {
			$json['error']['product_description_length'] = $this->language->get('error_product_description_length');
		}

		if (!$this->request->post['config_pagination']) {
			$json['error']['pagination'] = $this->language->get('error_pagination');
		}

		if (!$this->request->post['config_pagination_admin']) {
			$json['error']['pagination_admin'] = $this->language->get('error_pagination');
		}

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$json['error']['customer_group_display'] = $this->language->get('error_customer_group_display');
		}

		if ($this->request->post['config_login_attempts'] < 1) {
			$json['error']['login_attempts'] = $this->language->get('error_login_attempts');
		}

		if (!$this->request->post['config_voucher_min']) {
			$json['error']['voucher_min'] = $this->language->get('error_voucher_min');
		}

		if (!$this->request->post['config_voucher_max']) {
			$json['error']['voucher_max'] = $this->language->get('error_voucher_max');
		}

		if (!isset($this->request->post['config_processing_status'])) {
			$json['error']['processing_status'] = $this->language->get('error_processing_status');
		}

		if (!isset($this->request->post['config_complete_status'])) {
			$json['error']['complete_status'] = $this->language->get('error_complete_status');
		}

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$json['error']['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$json['error']['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$json['error']['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$json['error']['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$json['error']['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$json['error']['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$json['error']['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$json['error']['image_wishlist'] = $this->language->get('error_image_wishlist');
		}

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$json['error']['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['config_image_location_width'] || !$this->request->post['config_image_location_height']) {
			$json['error']['image_location'] = $this->language->get('error_image_location');
		}

		if (!$this->request->post['config_file_max_size']) {
			$json['error']['file_max_size'] = $this->language->get('error_file_max_size');
		}

		$disallowed = [
			'php',
			'php4',
			'php3'
		];

		$extensions = explode("\n", $this->request->post['config_file_ext_allowed']);

		foreach ($extensions as $extension) {
			if (in_array(trim($extension), $disallowed)) {
				$json['error']['extension'] = $this->language->get('error_extension');

				break;
			}
		}

		$disallowed = [
			'php',
			'php4',
			'php3'
		];

		$mimes = explode("\n", $this->request->post['config_file_mime_allowed']);

		foreach ($mimes as $mime) {
			if (in_array(trim($mime), $disallowed)) {
				$json['error']['mime'] = $this->language->get('error_mime');

				break;
			}
		}

		if (!$this->request->post['config_error_filename']) {
			$json['error']['log'] = $this->language->get('error_log_required');
		} elseif (preg_match('/\.\.[\/\\\]?/', $this->request->post['config_error_filename'])) {
			$json['error']['log'] = $this->language->get('error_log_invalid');
		} elseif (substr($this->request->post['config_error_filename'], strrpos($this->request->post['config_error_filename'], '.')) != '.log') {
			$json['error']['log'] = $this->language->get('error_log_extension');
		}

		if ((utf8_strlen($this->request->post['config_encryption']) < 32) || (utf8_strlen($this->request->post['config_encryption']) > 1024)) {
			$json['error']['encryption'] = $this->language->get('error_encryption');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$json['error']'warning'] = $this->language->get('error_warning');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function theme(): void {
		$image = '';

		$theme = basename($this->request->get['theme']);

		if ($theme == 'basic') {
			$image = HTTP_CATALOG . 'catalog/view/image/' . $theme . '.png';
		} else {
			$this->load->model('setting/extension');

			$extension_info = $this->model_setting_extension->getExtensionByCode('theme', $theme);

			if ($extension_info) {
				$image = DIR_EXTENSION . $extension_info['extension'] . '/catalog/view/image/' . $extension_info['code'] . '.png';
			}
		}

		if ($image) {
			$this->response->setOutput($image);
		} else {
			$this->response->setOutput(HTTP_CATALOG . 'image/no_image.png');
		}
	}
}
