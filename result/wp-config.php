<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u630329510_rHHBB' );

/** Database username */
define( 'DB_USER', 'u630329510_jNVJu' );

/** Database password */
define( 'DB_PASSWORD', 'flu2ni1c1T' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'hRE@04KRkG|<wU[ZjxkHT)(D:~wG-=1x)xe=^S9R6:9TwN]UqdbsrjqJ=-EYmmNT' );
define( 'SECURE_AUTH_KEY',   '<KZg;zWt,J8,R9MjY_{]Ue@FhU$:n?]H~ofDSp999HIDtW0S;3vkffvvefr%hF;~' );
define( 'LOGGED_IN_KEY',     '2Z1=0#Fs_4P;#L5d.Yv Y{Z0oQy?sbWK)XJEPF7#I4&H*~C|*6DVBX?n ~RWj=mK' );
define( 'NONCE_KEY',         'wh(jwi%D!PY-;jCS0aT[*Z,:bH([YY;J+NDqt}DVJ~-z+lX7[A5(>8ih/ jIF>K1' );
define( 'AUTH_SALT',         'l&K =EsU2#U,BM%gWLav4*D|(L/Q&3|P_g6*g-?O_ C%lC>#q#ji4:b?&LwAR9[z' );
define( 'SECURE_AUTH_SALT',  'OE>k]3D}h474%?.$-;c XF#BkID]?j_eD~qOdLS}z+KO@_ui;3uCwfhU#Yw &_fG' );
define( 'LOGGED_IN_SALT',    '-FcX/s6upP6&}3-[7xrs44%w+bU}{6OF6A4v1vx5$VsH;c(D@TPehY{]|L(5JkkN' );
define( 'NONCE_SALT',        '(NpT.`[|ZDq4is+.)&m+xhvhpc}!l+tEJkH~_C-y$[d]]lslai<G r%d_A<5zxOk' );
define( 'WP_CACHE_KEY_SALT', 'EFW}yO~^wbk=h_0^s[nu, Yl6 D>,J?`,*@>zbgjTM:uY(/t}=%L7BXyMZPA6N}T' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
