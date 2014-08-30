hudsongray.com-wp
============

Wordpress theme/configuration for the hudsongray.com website


## Installation


Clone the repository into the `wp-content/themes` directory of your Wordpress installation.

Run `php composer.phar install` to install the external dependencies in the `vendor` directory.


Activate the theme in Wordpress.

## Development

To test the theme outside of Wordpress, run

`foreman start`

from the CLI.

The static pages will be accessible on localhost, e.g. `localhost:8002/blog-static.php`

### PHP-CGI

In case you get CGI error from the webserver, you probably don't have php-cgi installed on your system.

Follow the instructions on this site: <http://wayneeaker.com/blog/2012/04/05/setting-php-cgi-mac-os-x>