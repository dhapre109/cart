<?php
//
// Cloud Command line tool for installing OpenCart
//
// Usage:
//
//   cd install
//   php cli_cloud.php install --username admin
//                             -
//                             --email email@example.com
//                             -password admin
//
//
//
// Error Codes
//
// {
//   error {
//     php_version: 'You need to use PHP7+ or above for OpenCart to work!
//     file_upload: 'You need to use PHP7+ or above for OpenCart to work!
//     session_auto_start: 'You need to use PHP7+ or above for OpenCart to work!
//     mysqli: 'You need to use PHP7+ or above for OpenCart to work!
//     gd: 'You need to use PHP7+ or above for OpenCart to work!
//     curl: 'You need to use PHP7+ or above for OpenCart to work!
//     openssl: 'You need to use PHP7+ or above for OpenCart to work!
//     zlib: 'You need to use PHP7+ or above for OpenCart to work!
//	},
// {
// }
// }
//


ini_set('display_errors', 1);

error_reporting(E_ALL);

// DIR
define('DIR_OPENCART', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/');

define('DIR_APPLICATION', DIR_OPENCART . 'install/');
define('DIR_SYSTEM', DIR_OPENCART . '/system/');
define('DIR_IMAGE', DIR_OPENCART . '/image/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_SYSTEM . 'storage/cache/');
define('DIR_DOWNLOAD', DIR_SYSTEM . 'storage/download/');
define('DIR_LOGS', DIR_SYSTEM . 'storage/logs/');
define('DIR_MODIFICATION', DIR_SYSTEM . 'storage/modification/');
define('DIR_SESSION', DIR_SYSTEM . 'storage/session/');
define('DIR_UPLOAD', DIR_SYSTEM . 'storage/upload/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/plain; charset=utf-8');
$registry->set('response', $response);

set_error_handler(function($code, $message, $file, $line, array $errcontext) {
	// error was suppressed with the @-operator
	if (error_reporting() === 0) {
		return false;
	}

	throw new ErrorException($message, 0, $code, $file, $line);
});

class ControllerCliInstall extends Controller {
	public function index() {
		if (isset($this->request->server['argv'])) {
			$argv = $this->request->server['argv'];
		} else {
			$argv = array();
		}

		// Just displays the path to the file
		$script = array_shift($argv);

		// Get the arguments passed with the command
		$command = array_shift($argv);

		switch ($command) {
			case 'install':
				$output = $this->install($argv);



				json_encode($output);


				break;
			case 'usage':
			default:
				$output = $this->usage($argv);
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($output);
	}

	public function install($argv) {
		$json = array();

		$json['error'] = array();

		// Validate args
		$total = count($argv);

		for ($i = 0; $i < $total; $i = $i + 2) {
			$is_flag = preg_match('/^--(.*)$/', $argv[$i], $match);

			if (!$is_flag) {
				return $argv[$i] . ' found in command line args instead of a valid option name starting with \'--\'';
			}

			$option[$match[1]] = $argv[$i + 1];
		}

		// Validation
		$required = array(
			'username',
			'email',
			'password'
		);

		$missing = array();

		foreach ($required as $value) {
			if (!array_key_exists($value, $option)) {
				$missing[] = $value;
			}
		}

		if (count($missing)) {
			return 'FAILED! Following inputs were missing or invalid: ' . implode(', ', $missing) . "\n\n";
		}

		// Requirements
		if (version_compare(phpversion(), '7.0.0', '<')) {
			$json['error']['php_version'] = 'You need to use PHP7+ or above for OpenCart to work!';
		}

		if (!ini_get('file_uploads')) {
			$json['error']['file_upload'] = 'file_uploads needs to be enabled!';
		}

		if (ini_get('session.auto_start')) {
			$json['error']['session_auto_start'] = 'OpenCart will not work with session.auto_start enabled!';
		}

		if (!extension_loaded('mysqli')) {
			$json['error']['mysqli'] = 'MySQLi extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('gd')) {
			$json['error']['gd'] = 'GD extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('curl')) {
			$json['error']['curl'] = 'CURL extension needs to be loaded for OpenCart to work!';
		}

		if (!function_exists('openssl_encrypt')) {
			$json['error']['openssl'] = 'OpenSSL extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('zlib')) {
			$json['error']['zlib'] = 'ZLIB extension needs to be loaded for OpenCart to work!';
		}

		if (!is_file(DIR_OPENCART . 'config.php')) {
			$json['error']['config_catalog_exist'] = 'config.php does not exist. You need to rename config-dist.php to config.php!';
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$json['error']['config_catalog_writable'] = 'config.php needs to be writable for OpenCart to be installed!';
		} elseif (!is_file(DIR_OPENCART . 'admin/config.php')) {
			$json['error']['config_admin_exist'] = 'admin/config.php does not exist. You need to rename admin/config-dist.php to admin/config.php!';
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$json['error']['config_admin_writable'] = 'admin/config.php needs to be writable for OpenCart to be installed!';
		}

		if ($json) {
			return $json;
		}

		try {
			// Database
			$db = new DB($option['db_driver'], htmlspecialchars_decode($option['db_hostname']), htmlspecialchars_decode($option['db_username']), htmlspecialchars_decode($option['db_password']), htmlspecialchars_decode($option['db_database']), $option['db_port']);

			// Setup database data
			$lines = file($file, FILE_IGNORE_NEW_LINES);

			if ($db->connected()) {


				$db->query("SET CHARACTER SET utf8");

				$db->query("SET @@session.sql_mode = 'MYSQL40'");

				$db->query("DELETE FROM `" . $option['db_prefix'] . "user` WHERE user_id = '1'");

				$db->query("INSERT INTO `" . $option['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($option['username']) . "', salt = '', password = '" . $db->escape(password_hash($option['password'], PASSWORD_DEFAULT)) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($option['email']) . "', status = '1', date_added = NOW()");

				$db->query("DELETE FROM `" . $option['db_prefix'] . "setting` WHERE `key` = 'config_email'");
				$db->query("INSERT INTO `" . $option['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($option['email']) . "'");

				$db->query("DELETE FROM `" . $option['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
				$db->query("INSERT INTO `" . $option['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(token(1024)) . "'");

				$db->query("UPDATE `" . $option['db_prefix'] . "product` SET `viewed` = '0'");

				$db->query("INSERT INTO `" . $option['db_prefix'] . "api` SET username = 'Default', `key` = '" . $db->escape(token(256)) . "', status = 1, date_added = NOW(), date_modified = NOW()");

				$api_id = $db->getLastId();

				$db->query("DELETE FROM `" . $option['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");
				$db->query("INSERT INTO `" . $option['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");

				// set the current years prefix
				$db->query("UPDATE `" . $option['db_prefix'] . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
			}
		} catch (ErrorException $e) {
			return 'FAILED!: ' . $e->getMessage() . "\n";
		}

		// Return success message
		$output  = 'SUCCESS! OpenCart successfully installed on your server' . "\n";
		$output .= 'Store link: ' . $option['http_server'] . "\n";
		$output .= 'Admin link: ' . $option['http_server'] . 'admin/' . "\n\n";

		return $output;
	}

	public function usage() {
		$option = implode(' ', array(
			'--http_server',
			'http://localhost/opencart/',
			'--username',
			'admin',
			'--password',
			'admin',
			'--password',
			'youremail@example.com'
		));

		$output = 'Usage:' . "\n";
		$output .= '======' . "\n\n";
		$output .= 'php cli_install.php install ' . $option . "\n\n";

		return $output;
	}
}

// Controller
$controller = new ControllerCliInstall($registry);
$controller->index();

// Output
$response->output();