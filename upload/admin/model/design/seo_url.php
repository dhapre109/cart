<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Seo Url
 *
 * @package Opencart\Admin\Model\Design
 */
class SeoUrl extends \Opencart\System\Engine\Model {
	/**
	 * Add Seo Url
	 *
	 * @param int    $store_id
	 * @param int    $language_id
	 * @param string $key
	 * @param string $value
	 * @param string $keyword
	 * @param int    $sort_order
	 *
	 * @return int
	 */
	public function addSeoUrl(int $store_id, $language_id, $key, $value, $keyword, $sort_order): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'information_id', `value` = '" . (int)$information_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");

		return $this->db->getLastId();
	}
	
	/**
	 * Edit Seo Url
	 *
	 * @param int                  $seo_url_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editSeoUrl(int $seo_url_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `key` = '" . $this->db->escape((string)$data['key']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `keyword` = '" . $this->db->escape((string)$data['keyword']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");
	}

	/**
	 * Delete Seo Url
	 *
	 * @param int $seo_url_id
	 *
	 * @return void
	 */
	public function deleteSeoUrl(int $seo_url_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");
	}

	/**
	 * Get Seo Url
	 *
	 * @param int $seo_url_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSeoUrl(int $seo_url_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");

		return $query->row;
	}

	/**
	 * Get Seo Urls
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSeoUrls(array $data = []): array {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` `s` WHERE `s`.`store_id` = `su`.`store_id`) AS `store`, (SELECT `name` FROM `" . DB_PREFIX . "language` `l` WHERE `l`.`language_id` = `su`.`language_id`) AS `language` FROM `" . DB_PREFIX . "seo_url` `su`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if (!empty($data['filter_key'])) {
			$implode[] = "LCASE(`key`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_key'])) . "'";
		}

		if (!empty($data['filter_value'])) {
			$implode[] = "LCASE(`value`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_value'])) . "'";
		}

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id']) && $data['filter_language_id'] !== '') {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'keyword',
			'key',
			'value',
			'sort_order',
			'store_id',
			'language_id'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `key`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Seo Urls
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalSeoUrls(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "seo_url`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if (!empty($data['filter_key'])) {
			$implode[] = "LCASE(`key`) = '" . $this->db->escape(oc_strtolower($data['filter_key'])) . "'";
		}

		if (!empty($data['filter_value'])) {
			$implode[] = "LCASE(`value`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_value'])) . "'";
		}

		if (!empty($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id']) && $data['filter_language_id'] !== '') {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Seo Url By Key Value
	 *
	 * @param string $key
	 * @param string $value
	 * @param int    $store_id
	 * @param int    $language_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSeoUrlByKeyValue(string $key, string $value, int $store_id, int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` = '" . $this->db->escape($value) . "' AND `store_id` = '" . (int)$store_id . "' AND `language_id` = '" . (int)$language_id . "'");

		return $query->row;
	}

	/**
	 * Get Seo Url By Keyword
	 *
	 * @param string $keyword
	 * @param int    $store_id
	 * @param int    $language_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSeoUrlByKeyword(string $keyword, int $store_id, int $language_id = 0): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE (`keyword` = '" . $this->db->escape($keyword) . "' OR LCASE(`keyword`) LIKE '" . $this->db->escape('%/' . oc_strtolower($keyword)) . "') AND `store_id` = '" . (int)$store_id . "'";

		if ($language_id) {
			$sql .= " AND `language_id` = '" . (int)$language_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row;
	}
}
