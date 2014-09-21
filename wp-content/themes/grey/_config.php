<?php

define( 'THEME_DIR', __DIR__);
define( 'TEMPLATE_DIR', THEME_DIR.'/templates/');
define( 'CLASS_DIR', THEME_DIR.'/lib/');
define( 'VENDOR_DIR', THEME_DIR.'/vendor/');
define( 'DATA_DIR', THEME_DIR.'/data/');

foreach (glob(VENDOR_DIR."handlebars.php-master/src/Handlebars/*.php") as $filename)
{
    require_once $filename;
}
foreach (glob(VENDOR_DIR."handlebars.php-master/src/Handlebars/*/*.php") as $filename)
{
    require_once $filename;
}



/* require_once VENDOR_DIR.'autoload.php'; */
require_once CLASS_DIR.'class.page.php';
require_once CLASS_DIR.'class.home.php';
require_once CLASS_DIR.'class.blog.php';
require_once CLASS_DIR.'class.single-blog.php';
require_once CLASS_DIR.'class.social-counter.php';

?>