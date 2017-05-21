<?php
class Language {
	private $default = 'en-gb';
	private $directory;
	private $data = array();

	public function __construct($directory = '') {
		$this->directory = $directory;
	}

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	public function getData() {
		return $this->data;
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function load($filename, &$data = array()) {
		$_ = array();

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (is_file($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		if (is_file($file)) {
			require($file);
		}

		$this->data = array_merge($this->data, $_);

		$data = array_merge($data, $this->data);

		return $this->data;
	}
}
