<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Language class
*/
class Language {
	private $default = 'en-gb';
	private $directory;
	public $data = array();
	
	/**
	 * Constructor
	 *
	 * @param	string	$file
	 *
 	*/
	public function __construct($directory = '') {
		$this->directory = $directory;
	}
	
	/**
     * 
     *
     * @param	string	$key
	 * 
	 * @return	string
     */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	/**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	/**
     * 
     *
	 * @return	array
     */	
	public function all() {
		return $this->data;
	}
	
	/**
     * 
     *
     * @param	string	$filename
	 * @param	string	$key
	 * 
	 * @return	array
     */	
	public function load($filename, $prefix = '') {
		$_ = array();

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (is_file($file)) {
			require($file);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		if (is_file($file)) {
			require($file);
		}

		if ($prefix) {
			foreach ($_ as $key => $value) {
				$_[$prefix . $key] = $value;

				unset($_[$key]);
			}
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}