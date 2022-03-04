<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade2 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (isset($this->request->get['admin'])) {
			$admin = basename($this->request->get['admin']);
		} else {
			$admin = 'admin';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// Get directory constants
		$config = [];

		$lines = file(DIR_OPENCART . 'config.php');

		foreach ($lines as $number => $line) {
			if (preg_match('/define\(\'(.*)\',\s+\'(.*)\'\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
				$config[$match[1][0]] = $match[2][0];
			}
		}

		$total = 0;

		$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

		if (is_file($file)) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {
				$total = $zip->numFiles;

				$start = ($page - 1) * 200;

				$remove = 'opencart-' . $version . '/upload/';

				// Check if any of the files already exist.
				for ($i = $start; $i < ($start + 200); $i++) {
					$source = $zip->getNameIndex($i);

					if (substr($source, 0, strlen($remove)) == $remove) {
						// Only extract the contents of the upload folder
						$destination = str_replace('\\', '/', substr($source, strlen($remove)));

						if (substr($destination, 0, 8) != 'install/') {
							// Default copy location
							$path = DIR_OPENCART . $destination;

							// Fixes admin folder being under a different name
							if (substr($destination, 0, 6) == 'admin/') {
								$path = DIR_OPENCART . $admin . '/' . substr($destination, 6);
							}

							// We need to use a different path for vendor folders.
							if (substr($destination, 0, 15) == 'system/storage/' && isset($config['DIR_STORAGE'])) {
								$path = $config['DIR_STORAGE'] . substr($destination, 15);
							}

							// Must not have a path before files and directories can be moved
							if (substr($path, -1) == '/') {
								if (!is_dir($path) && !mkdir($path, 0777)) {
									$json['error'] = sprintf($this->language->get('error_directory'), $path);
								}
							}

							// Check if the path is not directory and check there is no existing file
							if (substr($path, -1) != '/') {
								if (is_file($path)) {
									unlink($path);
								}

								if (!copy('zip://' . $file . '#' . $source, $path)) {
									$json['error'] = sprintf($this->language->get('error_copy'), $source, $path);
								}
							}
						}
					}
				}

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 2, 2, 8);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			if (($page * 200) <= $total) {
				$json['next'] = $this->url->link('upgrade/upgrade_2', 'version=' . $version . '&admin=' . $admin . '&page=' . ($page + 1), true);
			} else {
				$json['next'] = $this->url->link('upgrade/upgrade_3', '', true);

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
