<?php
	define("VERSION", "3.1");

	define("SESSION_KEY", "proxy_session_key");

	define("CONNECTION_ERROR",   -1);
	define("NO_USER_INPUT",      -2);
	define("LOGIN_REQUIRED",     -3);
	define("INTERNAL_ERROR",     -4);
	define("FORBIDDEN_HOSTNAME", -5);
	define("LOOPBACK",           -6);
	define("LOCAL_FILE",         -7);

	define("DOT_REPLACEMENT", "_");

	/* Class autoloader
	 */
	function __autoload($class_name) {
		$file = "libraries/".strtolower($class_name).".php";
		if (file_exists($file)) {
			require($file);
		}
	}

	/* Log debug information
	 */
	function debug_log($str) {
		if (($fp = fopen("debug.log", "a")) === false) {
			return false;
		}

		if (func_num_args() > 1) {
			$args = func_get_args();
			array_shift($args);
			$str = vsprintf($str, $args);
		}

		fprintf($fp, "%s|%s|%s", date("D d M Y H:i:s"), $_SERVER["REMOTE_ADDR"], $str);

		fclose($fp);
	}

	/* Function gzdecode()
	 */
	if (function_exists("gzdecode") == false) {
		function gzdecode($data) {
			$file = tempnam("/tmp", "gzip");

			@file_put_contents($file, $data);
			ob_start();
			readgzfile($file);
			$data = ob_get_clean();
			unlink($file);

			return $data;
		}
	}

	/* Suppress error messages
	 */
	function error_handler($error) {
	}
	set_error_handler("error_handler", E_ALL);
?>
