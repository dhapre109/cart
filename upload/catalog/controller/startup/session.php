<?php
class ControllerStartupSession extends Controller {
	public function index() {
		if (isset($this->request->get['api_token']) && isset($this->request->get['route']) && substr($this->request->get['route'], 0, 4) == 'api/') {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE TIMESTAMPADD(HOUR, 1, date_modified) < NOW()");
					
			// Make sure the IP is allowed
			$api_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (a.api_id = ai.api_id) WHERE a.status = '1' AND `as`.`session_id` = '" . $this->db->escape($this->request->get['api_token']) . "' AND ai.ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		 
			if ($api_query->num_rows) {
				$this->registry->set('session', new Session($this->request->get['api_token']));
				
				// keep the session alive
				$this->db->query("UPDATE `" . DB_PREFIX . "api_session` SET `date_modified` = NOW() WHERE `api_session_id` = '" . (int)$api_query->row['api_session_id'] . "'");
			} else {
				exit('Error: You do not have permission to access the API!');
			}
		} else {
			// Default session
			if (isset($_COOKIE[$this->config->get('session_name')])) {
				$session_id = $_COOKIE[$this->config->get('session_name')];
			} else {
				$session_id = '';
			}
			
			$class = 'Session\\' . $this->config->get('session_engine');
			
			if ($this->config->get('session_engine') == 'db') {
				$handler = new $class($registry);
			} else {
				$handler = new $class($registry);
			}
			
			$session = new Session($session_id);
			
			setcookie($this->config->get('session_name'), $session->getId(), ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
		
			$this->registry->set('session', $session);			
		}
	}
}
