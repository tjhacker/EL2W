diff -rubN ipxe/src/include/ipxe/proxy.h ipxepatch/src/include/ipxe/proxy.h
--- ipxe/src/include/ipxe/proxy.h	1970-01-01 00:00:00.000000000 +0000
+++ ipxepatch/src/include/ipxe/proxy.h	2021-12-06 00:41:30.904138561 +0000
@@ -0,0 +1,17 @@
+#ifndef _IPXE_PROXY_H
+#define _IPXE_PROXY_H
+
+/** @file
+ *
+ * HTTP Proxy
+ *
+ */
+
+FILE_LICENCE ( GPL2_OR_LATER );
+
+int is_proxy_set ( );
+struct uri *get_proxy ( );
+const char *proxied_uri_host ( struct uri *uri );
+unsigned int proxied_uri_port ( struct uri *uri, unsigned int default_port );
+
+#endif /* _IPXE_PROXY_H */
diff -rubN ipxe/src/net/proxy.c ipxepatch/src/net/proxy.c
--- ipxe/src/net/proxy.c	1970-01-01 00:00:00.000000000 +0000
+++ ipxepatch/src/net/proxy.c	2021-12-06 00:07:21.478009856 +0000
@@ -0,0 +1,70 @@
+#include <string.h>
+#include <ipxe/proxy.h>
+#include <ipxe/uri.h>
+#include <ipxe/settings.h>
+
+/** @file
+ *
+ * HTTP Proxy
+ *
+ */
+
+FILE_LICENCE ( GPL2_OR_LATER );
+
+struct uri *proxy_uri = NULL;
+
+/** HTTP proxy address setting */
+struct setting http_proxy_setting __setting ( SETTING_MISC, "" ) = {
+	.name = "http-proxy",
+	.description = "Address and port of the HTTP (not HTTPS) proxy to use, as a http scheme URI",
+	.type = &setting_type_string,
+};
+
+int is_proxy_set ( ) {
+	/* Later, this may be expanded to encompass other settings */
+	if ( ! proxy_uri ) {
+		proxy_uri = get_proxy();
+	}
+	return ! ! proxy_uri;
+}
+
+struct uri *get_proxy ( ) {
+	char *http_proxy_unexpanded, *http_proxy;
+
+	if ( setting_exists ( NULL, &http_proxy_setting ) && ! proxy_uri ) {
+		/* Later, this may select from multiple settings*/
+		fetch_string_setting_copy ( NULL, &http_proxy_setting, &http_proxy_unexpanded );
+		http_proxy = expand_settings ( http_proxy_unexpanded );
+		proxy_uri = parse_uri ( http_proxy );
+		free ( http_proxy_unexpanded );
+		free ( http_proxy );
+		/* Only the http scheme is currently supported */
+		if ( strcmp ( proxy_uri->scheme, "http" ) != 0 ) {
+			uri_put ( proxy_uri );
+			DBG ( "http-proxy must begin with \"http://\"" );
+			return NULL;
+		}
+	}
+
+	return proxy_uri;
+}
+
+const char *proxied_uri_host ( struct uri *uri ) {
+	/* Later, this could select from multiple proxies,
+	based on hostname patterns matched against the uri */
+	if ( is_proxy_set ( ) ) {
+		return proxy_uri->host;
+	} else {
+		return uri->host;
+	}
+}
+
+unsigned int proxied_uri_port ( struct uri *uri, unsigned int default_port ) {
+	/* Later, this could select from multiple proxies,
+	based on hostname patterns matched against the uri */
+	if ( is_proxy_set ( ) ) {
+		return uri_port ( proxy_uri, default_port);
+	} else {
+		return uri_port ( uri, default_port);
+	}
+}
diff -rubN ipxe/src/net/tcp/httpconn.c ipxepatch/src/net/tcp/httpconn.c
--- ipxe/src/net/tcp/httpconn.c	2021-12-06 00:06:48.218845265 +0000
+++ ipxepatch/src/net/tcp/httpconn.c	2021-12-06 00:08:33.739367454 +0000
@@ -42,6 +42,7 @@
 #include <ipxe/open.h>
 #include <ipxe/pool.h>
 #include <ipxe/http.h>
+#include <ipxe/proxy.h>
 
 /** HTTP pooled connection expiry time */
 #define HTTP_CONN_EXPIRY ( 10 * TICKS_PER_SEC )
@@ -250,7 +251,9 @@
 		return -EINVAL;
 
 	/* Identify port */
-	port = uri_port ( uri, scheme->port );
+	/* port = uri_port ( uri, scheme->port ); */
+
+        port = proxied_uri_port ( uri, scheme->port );
 
 	/* Look for a reusable connection in the pool.  Reuse the most
 	 * recent connection in order to accommodate authentication
@@ -298,7 +301,8 @@
 	server.st_port = htons ( port );
 	if ( ( rc = xfer_open_named_socket ( &conn->socket, SOCK_STREAM,
 					     ( struct sockaddr * ) &server,
-					     uri->host, NULL ) ) != 0 )
+					     /* uri->host, NULL ) ) != 0 ) */
+	                                     proxied_uri_host ( uri ), NULL ) ) != 0 )
 		goto err_open;
 
 	/* Add filter, if any */
diff -rubN ipxe/src/net/tcp/httpcore.c ipxepatch/src/net/tcp/httpcore.c
--- ipxe/src/net/tcp/httpcore.c	2021-12-06 00:06:48.218845265 +0000
+++ ipxepatch/src/net/tcp/httpcore.c	2021-12-06 00:08:44.579421097 +0000
@@ -58,6 +58,7 @@
 #include <ipxe/errortab.h>
 #include <ipxe/efi/efi_path.h>
 #include <ipxe/http.h>
+#include <ipxe/proxy.h>
 
 /* Disambiguate the various error causes */
 #define EACCES_401 __einfo_error ( EINFO_EACCES_401 )
@@ -614,8 +615,14 @@
 
 	/* Calculate request URI length */
 	memset ( &request_uri, 0, sizeof ( request_uri ) );
+	if ( is_proxy_set ( ) ) {
+	   /*include all fields*/
+	   memcpy( &request_uri, uri, sizeof( request_uri ));
+	}
+	else {
 	request_uri.epath = ( uri->epath ? uri->epath : "/" );
 	request_uri.equery = uri->equery;
+	}
 	request_uri_len =
 		( format_uri ( &request_uri, NULL, 0 ) + 1 /* NUL */);
 
