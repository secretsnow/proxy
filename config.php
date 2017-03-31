<?php
	$_CONFIG = array(

	/* Proxy hostname
	 *
	 * Hostname of this proxy website. Set manually if the proxy isn't working correctly.
	 */
	"proxy_hostname" => $_SERVER["SERVER_NAME"],

	/* Access codes
	 *
	 * An array that contains access codes for using this proxy.
	 */
	"access_codes" => array(),

	/* No authentication for websites
	 *
	 * An array with hostnames of the websites that can be visited without an access code.
	 */
	"no_auth_websites" => array(),

	/* No authentication for clients
	 *
	 * An array with IP addresses from where no access code is required.
	 */
	"no_auth_clients" => array(),

	/* Quick links
	 *
	 * An array with URL's for the Quick Links sections
	 * Format: array("The Pirate Bay" => "https://thepiratebay.org/", ...)
	 */
	"quick_links" => array(
		"The Pirate Bay" => "https://thepiratebay.org/"),

	/* Forwarding proxy
	 *
	 * Use this other proxy (Tor) to handle all requests.
	 # Format: "socks://localhost:3128"
	 */
	"forwarding_proxy" => null,

	/* Private browsing
	 *
	 * An array that contains hostnames for which cookies are always dropped.
	 */
	"private_browsing" => array(
		"www.google.com", "google.com",
		"www.google.nl", "google.nl",
		"www.facebook.com", "facebook.com"),

	/* Access control
	 *
	 * Define which websites can or cannot be visited via this proxy.
	 */
	"whitelist" => array(),
	"blacklist" => array(),

	);
?>
