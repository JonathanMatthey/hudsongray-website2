<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
// define( 'WPCACHEHOME', '/home1/mattheyj/public_html/jonathanmatthey.com/grey/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('MMAFF', 'hostgator' ); //Added by QuickInstall

define('DB_NAME', 'mattheyj_wrdp4');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'mahakala8');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '$Ql!?Q~vb0yiFjfsT3Z12Wv/2a$cdxJo$iIW4xNL76cS5yV5IIhjTRD~KySQ_rBlg');
define('SECURE_AUTH_KEY',  '');
define('LOGGED_IN_KEY',    '~t4BzAzK6dYom_)!cF#-XqkX\`OwgurpOoIS15^u57!Uwnb4(1M3!\`Z?56KB$');
define('NONCE_KEY',        'vWob\`(6L~-->arfC<_Q)Q\`;9qh5)aqXDuTd8V/8|1oL_bu4jw\`aesXI/lh/RxN;x*Mi');
define('AUTH_SALT',        'UuNzh9jcId0EhkImjFOpV/2XP0r5xQ;U2vQBQ~PJOZzQ*)QTYiy*slan2*;xjS');
define('SECURE_AUTH_SALT', ':k$tvT=LC<*i(hEf-|B:JcCoX@N=t(YaB6I!)Bk_NiPjXd;/RZEJ=>>t(ed|@ue>Du');
define('LOGGED_IN_SALT',   'TR~16YOt!_*^4DyZgOvYp@BG=ag:g;rmjFTpHtk<YDgK=BLhU\`Jj-4FIDABJBD^D<R1:mJk41>b<B');
define('NONCE_SALT',       'p9n25MP!/1a6fqNNBnDlPCZX1U44thAVC_RHAnQg!Krl/*ZkS3g#\`CY~$i<bg3)U3fa)');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
