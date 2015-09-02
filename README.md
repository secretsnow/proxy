Proxy
=====
This is a webproxy script written in PHP by Hugo Leisink <hugo@leisink.net>.

Installation
------------
- Copy all files to a suitable location.
- Make the webserver rewrite *all* requests to index.php.
- Make this proxy available via both HTTP and HTTPS.
- Create a wildcard SSL certificate for \*.proxy.tld.
- Replace the first part of the hostname with a wildcard and use the result
  as an alias in your webserver configuration. For example, if you choose
  www.proxy.tld as the hostname for this proxy, use \*.proxy.tld as an alias
  for it.
  
The hostname proxy.tld will work with for HTTP, but will cause an SSL error
when using it with HTTPS because of the wildcard certificate.

Hiawatha webserver example configuration
----------------------------------------
VirtualHost {
	Hostname = www.proxy.tld, *.proxy.tld
	WebsiteRoot = /var/www/proxy
	StartFile = index.php
	AccessLogfile = /var/log/hiawatha/proxy-access.log
	ErrorLogfile = /var/log/hiawatha/proxy-error.log
	UseFastCGI = PHP5
	UseToolkit = catch_all
	TimeForCGI = 60
	TLScertFile = tls/proxy.pem
}

UrlToolkit {
	ToolkitID = catch_all
	Match .* Rewrite /index.php
}
