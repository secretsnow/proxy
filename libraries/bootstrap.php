<?php
	/* Proxy bootstrap
	 *
	 * Written by Hugo Leisink <hugo@leisink.net>
	 */

	class bootstrap {
		private $config = null;
		private $hostname = null;
		private $user_input = null;

		/* Constructor
		 *
		 * INPUT:  array configuration
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($config) {
			$this->config = $config;
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "user_input": return $this->user_input;
				case "hostname": return $this->hostname;
			}

			return null;
		}

		/* Check if hostname exists in list
		 *
		 * INPUT:  string hostname, array list
		 * OUTPUT: boolean hostname exists in list
		 * ERROR:  -
		 */
		private function hostname_in_list($hostname, $list) {
			foreach ($list as $item) {
				if ($item[0] == "*") {
					$item = substr($item, 1);
					if (substr($hostname, -strlen($item)) == $item) {
						return true;
					}
				} else if ($hostname == $item) {
					return true;
				}
			}

			return false;
		}


		/* Check if a login is required
		 *
		 * INPUT:  -
		 * OUTPUT: boolean login required
		 * ERROR:  -
		 */
		private function login_required() {
			if (count($this->config["access_codes"]) == 0) {
				return false;
			}

			if ($this->hostname_in_list($this->hostname, $this->config["no_auth_websites"])) {
				return false;
			} else if (in_array($_SERVER["REMOTE_ADDR"], $this->config["no_auth_clients"])) {
				return false;
			}

			if (in_array($_SESSION["access_code"], $this->config["access_codes"])) {
				return false;
			}

			if (in_array($_POST["access_code"], $this->config["access_codes"])) {
				$_SESSION["access_code"] = $_POST["access_code"];
				$_SERVER["REQUEST_METHOD"] = "GET";
				return false;
			}

			return true;
		}

		/* Execute bootstrap procedure
		 *
		 * INPUT:  -
		 * OUTPUT: integer result
		 * ERROR:  -
		 */
		public function execute() {
			$http_host = $_SERVER["HTTP_HOST"];

			/* Block searchbots
			 */
			$search_bots = array("Googlebot", "bingbot");
			foreach ($search_bots as $bot) {
				if (strpos($_SERVER["HTTP_USER_AGENT"], $bot) !== false) {
					return 403;
				}
			}

			/* Local files
			 */
			if ($http_host == $this->config["proxy_hostname"]) {
				list(, $directory) = explode("/", $_SERVER["REQUEST_URI"], 3);
				if (($_SERVER["REQUEST_URI"] == "/robots.txt") || ($directory == "resources")) {
					return LOCAL_FILE;
				}
			}

			/* User input
			 */
			$http_host_len = strlen($http_host);
			$proxyname_len = strlen($this->config["proxy_hostname"]);

			if ($http_host_len < $proxyname_len) {
				return INTERNAL_ERROR;
			}

			if (($hostname_len = $http_host_len - $proxyname_len - 1) == -1) {
				if ($http_host != $this->config["proxy_hostname"]) {
					return INTERNAL_ERROR;
				}
			} else if (substr($http_host, $hostname_len) != ".".$this->config["proxy_hostname"]) {
				return INTERNAL_ERROR;
			}

			$hostname = substr($http_host, 0, $hostname_len);
			$this->hostname = str_replace(DOT_REPLACEMENT, ".", $hostname);

			/* Authentication
			 */
			if ($this->login_required()) {
				return LOGIN_REQUIRED;
			}

			if (($http_host == $this->config["proxy_hostname"]) || ($hostname == $this->config["proxy_prefix"])) {
				return NO_USER_INPUT;
			}

			$this->user_input = $this->hostname;
			if ($_SERVER["REQUEST_URI"] != "/") {
				$this->user_input .= $_SERVER["REQUEST_URI"];
			}

			/* Access control
			 */
			if (count($this->config["whitelist"]) > 0) {
				if ($this->hostname_in_list($this->hostname, $this->config["whitelist"]) == false) {
					return FORBIDDEN_HOSTNAME;
				}
			}

			if ($this->hostname_in_list($this->hostname, $this->config["blacklist"])) {
				return FORBIDDEN_HOSTNAME;
			}

			return 0;
		}
	}
?>
