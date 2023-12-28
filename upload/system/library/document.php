<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		https://www.opencart.com
*/
namespace Opencart\System\Library;
/**
 * Class Document
 */
class Document {
	/**
	 * @var string
	 */
	private string $title = '';
	/**
	 * @var string
	 */
	private string $seo = '';
	/**
	 * @var string
	 */
	private string $schema = '';
	/**
	 * @var array
	 */
	private array $links = [];
	/**
	 * @var array
	 */
	private array $styles = [];
	/**
	 * @var array
	 */
	private array $scripts = [];

	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return void
	 */
	public function setTitle(string $title): void {
		$this->title = $title;
	}

	/**
	 * getTitle
	 *
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * setSeo
	 *
	 * @param string $seo
	 */
	public function setSeo(string $seo): void {
		$this->seo = $seo;
	}

	/**
	 * getSeo
	 *
	 * @return    string
	 */
	public function getSeo(): string {
		return $this->seo;
	}

	/**
	 * setSchema
	 *
	 * @param string $schema
	 */
	public function setSchema(string $schema): void {
		$this->schema = $schema;
	}

	/**
	 * getSchema
	 *
	 * @return    string
	 */
	public function getSchema(): string {
		return $this->schema;
	}

	/**
	 * addLink
	 *
	 * @param string $href
	 * @param string $rel
	 *
	 * @return void
	 */
	public function addLink(string $href, string $rel): void {
		$this->links[$href] = [
			'href' => $href,
			'rel' => $rel
		];
	}

	/**
	 * getLinks
	 *
	 * @return array
	 */
	public function getLinks(): array {
		return $this->links;
	}

	/**
	 * addStyle
	 *
	 * @param string $href
	 * @param string $rel
	 * @param string $media
	 *
	 * @return void
	 */
	public function addStyle(string $href, string $rel = 'stylesheet', string $media = 'screen'): void {
		$this->styles[$href] = [
			'href' => $href,
			'rel' => $rel,
			'media' => $media
		];
	}

	/**
	 * getStyles
	 *
	 * @return array
	 */
	public function getStyles(): array {
		return $this->styles;
	}

	/**
	 * addScript
	 *
	 * @param string $href
	 * @param string $position
	 *
	 * @return void
	 */
	public function addScript(string $href, string $position = 'header'): void {
		$this->scripts[$position][$href] = ['href' => $href];
	}

	/**
	 * getScripts
	 *
	 * @param string $position
	 *
	 * @return array
	 */
	public function getScripts(string $position = 'header'): array {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return [];
		}
	}
}
