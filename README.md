
![tunnel.png](https://raw.githubusercontent.com/secretsnow/proxy/78d35ea270006b1f3587867ddf542b0f5c66c960/resources/tunnel.png).

WebProxy
=====
This is a WebProxy script written in PHP by Hugo Leisink <hugo@leisink.net>.

Copyright © by Hugo Leisink. All rights reserved.

Installation
------------
- Copy all files to a suitable location.
- Make the WebServer rewrite *all* requests to index.php.
- Make this Proxy available via both HTTP and HTTPS.
- Use an SSL certificate that is valid for \*.proxy.tld and proxy.tld.

You can use the script certificate/generate to create a self-signed certificate.

Hiawatha WebServer Example Configuration
----------------------------------------
In the following configuration, replace proxy.domain.tld with your own hostname.

	VirtualHost {
		Hostname = proxy.domain.tld, *.proxy.domain.tld
		WebsiteRoot = /var/www/proxy
		StartFile = index.php
		AccessLogfile = /var/log/hiawatha/proxy-access.log
		ErrorLogfile = /var/log/hiawatha/proxy-error.log
		UseFastCGI = PHP5
		UseToolkit = proxy
		TimeForCGI = 60
		TLScertFile = tls/proxy.pem
	}
	
	UrlToolkit {
		ToolkitID = proxy
		Header Host !^proxy.domain.tld$ Skip 1
		RequestURI isfile Return
		Match [^?]*(\?.*)? Rewrite /index.php$1
	}
