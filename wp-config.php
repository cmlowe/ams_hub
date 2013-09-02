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
define('DB_NAME', 'ams_db');

/** MySQL database username */
define('DB_USER', 'ams_user');

/** MySQL database password */
define('DB_PASSWORD', 'Sl0IpoVi');

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
define('AUTH_KEY',         '6DjQ*?|-=tR-#E[1iBkS-~|HT3Bw;~zkV.C+7xrz(t;^(}d5_ZN-?st:Cj+F-Q`b');
define('SECURE_AUTH_KEY',  'S.AFx|-Xy@s0+QDzC{9!/&)qqdOqb0aR,n=HC7g!Yled(Qb}y]mm.8Y[~>4Cjp^|');
define('LOGGED_IN_KEY',    '4u`z*pNGp@NUUu:*21ix~5jVnKB^OV-]uoA*ou^|22pIeT}RUwNx8@.WHQ#+[OKX');
define('NONCE_KEY',        'b)`5|zv7:YA.o&8w[e+Z{}COC+]>agN,|$]XGg*oFilr|s7?M(&9ZtRJ_65xDo!l');
define('AUTH_SALT',        '?G^<yZRYjXwI>/*;Y0NEQoLk}[(yaSg2e:*!z(accKXobZDkFr+//Uh(#  FI`Mp');
define('SECURE_AUTH_SALT', '%rK{t<n[lH*aOu@^sdOs]ExfM1beF!4b+ |L2+25u7-8}RYtQ,AfaQRR8M>-hWPf');
define('LOGGED_IN_SALT',   '|`zQeZ9h<)#5z80<2o:Uc#;djMK7<mOT.|[c^4x#}4lc=65?TdG@+95Y9?Rke%BA');
define('NONCE_SALT',       'EJt+lj[3KoXr!8B6R06UMi@ <wrp&^j{`QBaCGA/Q|7C%3$4|f:6Kb|N=O~0 $g|');

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
