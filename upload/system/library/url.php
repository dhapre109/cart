<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
 */

/**
 * URL class
 */
class Url {
	/** @var string */
	private $url;
	/** @var string */
	private $ssl;
	/** @var Controller[] */
	private $rewrite = [];

	/**
	 * Constructor
	 *
	 * @param	string	$url
	 * @param	string	$ssl
 	 */
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}

	/**
	 * @param	Controller	$rewrite
	 *
	 * @return	void
 	 */
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	/**
	 * @param	string		$route
	 * @param	string|string[]	$args
	 * @param	bool		$secure
	 *
	 * @return	string
 	 */
	public function link($route, $args = '', $secure = false) {
		if ($this->ssl && $secure) {
			$url = $this->ssl . 'index.php?route=' . (string)$route;
		} else {
			$url = $this->url . 'index.php?route=' . (string)$route;
		}

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
		return $url;
	}
}
