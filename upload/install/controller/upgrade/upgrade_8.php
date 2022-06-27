<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade8 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// customer_activity
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `customer_activity_id` = `activity_id` WHERE `customer_activity_id` IS NULL or `customer_activity_id` = ''");
			}

			// Customer Group
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_group' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$customer_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");

				foreach ($customer_group_query->rows as $customer_group) {
					$language_query = $this->db->query("SELECT `language_id` FROM `" . DB_PREFIX . "language`");

					foreach ($language_query->rows as $language) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($customer_group['name']) . "'");
					}
				}
			}

			// Affiliate customer merge code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate'");

			if ($query->num_rows) {
				// Removing affiliate and moving to the customer account.
				$config = new \Opencart\System\Engine\Config();

				$setting_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0'");

				foreach ($setting_query->rows as $setting) {
					$config->set($setting['key'], $setting['value']);
				}

				$affiliate_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate`");

				foreach ($affiliate_query->rows as $affiliate) {
					$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '" . $this->db->escape($affiliate['email']) . "'");

					if (!$customer_query->num_rows) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET `customer_group_id` = '" . (int)$config->get('config_customer_group_id') . "', `language_id` = '" . (int)$config->get('config_customer_group_id') . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `email` = '" . $this->db->escape($affiliate['email']) . "', `password` = '" . $this->db->escape($affiliate['password']) . "', `newsletter` = '0', `custom_field` = '" . $this->db->escape(json_encode([])) . "', `ip` = '" . $this->db->escape($affiliate['ip']) . "', `status` = '" . $this->db->escape($affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");

						$customer_id = $this->db->getLastId();

						$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET `customer_id` = '" . (int)$customer_id . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `address_1` = '" . $this->db->escape($affiliate['address_1']) . "', `address_2` = '" . $this->db->escape($affiliate['address_2']) . "', `city` = '" . $this->db->escape($affiliate['city']) . "', `postcode` = '" . $this->db->escape($affiliate['postcode']) . "', `zone_id` = '" . (int)$affiliate['zone_id'] . "', `country_id` = '" . (int)$affiliate['country_id'] . "', `custom_field` = '" . $this->db->escape(json_encode([])) . "'");
					} else {
						$customer_id = $customer_query->row['customer_id'];
					}

					$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

					if (!$customer_query->num_rows) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `tracking` = '" . $this->db->escape($affiliate['code']) . "', `commission` = '" . (float)$affiliate['commission'] . "', `tax` = '" . $this->db->escape($affiliate['tax']) . "', `payment` = '" . $this->db->escape($affiliate['payment']) . "', `cheque` = '" . $this->db->escape($affiliate['cheque']) . "', `paypal` = '" . $this->db->escape($affiliate['paypal']) . "', `bank_name` = '" . $this->db->escape($affiliate['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($affiliate['bank_branch_number']) . "', `bank_account_name` = '" . $this->db->escape($affiliate['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($affiliate['bank_account_number']) . "', `status` = '" . (int)(isset($affiliate['approved']) ? $affiliate['approved'] : $affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");
					}

					$affiliate_transaction_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");

					foreach ($affiliate_transaction_query->rows as $affiliate_transaction) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$affiliate_transaction['order_id'] . "', `description` = '" . $this->db->escape($affiliate_transaction['description']) . "', `amount` = '" . (float)$affiliate_transaction['amount'] . "', `date_added` = '" . $this->db->escape($affiliate_transaction['date_added']) . "'");

						$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_transaction_id` = '" . (int)$affiliate_transaction['affiliate_transaction_id'] . "'");
					}

					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `affiliate_id` = '" . (int)$customer_id . "' WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");
				}
			}
			
			$config_captcha_page = json_decode((array)$config->get('config_captcha_page'), true);
		
			// Config Session Expire
			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_session_expire'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_session_expire', `value` = '3600000000', `serialized` = '0'");
			}

			// Config Cookie ID
			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_cookie_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_cookie_id', `value` = '0', `serialized` = '0'");
			}

			// Config GDPR ID
			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_gdpr_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_gdpr_id', `value` = '0', `serialized` = '0'");
			}

			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_gdpr_limit'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_gdpr_limit', `value` = '180', `serialized` = '0'");
			}

			// Config affiliate Status ID
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_affiliate_status'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_affiliate_status', `value` = '1', `serialized` = '0'");
			}

			// Config affiliate expire
			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_affiliate_expire'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_affiliate_expire', `value` = '3600000000', `serialized` = '0'");
			}

			// Config Subscriptions
			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_subscription_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_subscription_status_id', `value` = '1', `serialized` = '0'");
			}

			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_subscription_active_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_subscription_active_status_id', `value` = '2', `serialized` = '0'");
			}

			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_subscription_expired_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_subscription_expired_status_id', `value` = '6', `serialized` = '0'");
			}

			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_subscription_canceled_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_subscription_canceled_status_id', `value` = '4', `serialized` = '0'");
			}

			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_subscription_failed_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_subscription_failed_status_id', `value` = '3', `serialized` = '0'");
			}

			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_subscription_denied_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_subscription_denied_status_id', `value` = '5', `serialized` = '0'");
			}

			// Config - Fraud Status ID
			$query = $this->db->query("SELECT * FROM `setting` WHERE `key` = 'config_fraud_status_id'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'config', `key` = 'config_fraud_status_id', `value` = '8', `serialized` = '0'");
			}

			// Country
			$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `address_format_id` = '1' WHERE `address_format_id` = '0'");

			// Api
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `name` = `username` WHERE `username` IS NULL or `username` = ''");
			}

			// Drop Fields
			$remove = [];

			$remove[] = [
				'table' => 'api',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'firstname'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'lastname'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'password'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'cart'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'salt'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'approved'
			];

			$remove[] = [
				'table' => 'customer_activity',
				'field' => 'activity_id'
			];

			$remove[] = [
				'table' => 'customer_group',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'order',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'language',
				'field' => 'directory'
			];

			$remove[] = [
				'table' => 'location',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'store',
				'field' => 'ssl'
			];

			$remove[] = [
				'table' => 'user',
				'field' => 'salt'
			];

			foreach ($remove as $result) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $result['table'] . "' AND COLUMN_NAME = '" . $result['field'] . "'");

				if ($query->num_rows) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $result['table'] . "` DROP `" . $result['field'] . "`");
				}
			}

			// Drop Tables
			$remove = [
				'affiliate',
				'affiliate_activity',
				'affiliate_login',
				'affiliate_transaction',
				'banner_image_description',
				'banner_image_description',
				'banner_image_description',
				'customer_ban_ip',
				'customer_field',
				'modification',
				'order_field',
				'order_custom_field',
				'url_alias'
			];

			foreach ($remove as $table) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

				if ($query->num_rows) {
					$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
