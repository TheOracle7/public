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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'Yz;8]CAY2o|e$;]vAOJ#w_zffxh.oUK1$u{-bjxv1Pl=:T8-)aO~Wv*OC{z(gZe5' );
define( 'SECURE_AUTH_KEY',   'bS!:cILf$A7:t*&/izpRok5jdTGW9*Ae$Hj0smVMEx:oc~6YKe(!Rq#.pH.=+);S' );
define( 'LOGGED_IN_KEY',     'YP/4i;JZhg`Hb6:o-uawn]>(J?*4)$v6<zl!oda:MFzwDzUEz]ffD6kXe@U{fB!;' );
define( 'NONCE_KEY',         '~RD@d+.^fNd u8&1Yk.#OS{hj0XW{}zoeS6@Wz@$0.FF[I1yV5p~0ITK^t[Ox`B>' );
define( 'AUTH_SALT',         '*<Ma/ryft%D1I0CEqP%0z]hIfgwO-4Wq~HOPf|yVVT^EiY|Q=IS~klDXLil%@{f_' );
define( 'SECURE_AUTH_SALT',  'l/3Z9(pqnKi=m[]!SqL A9pxZ^LkM|SDmU_ar+0:2er[x<YMl(X-rR) 1]Kx),6Z' );
define( 'LOGGED_IN_SALT',    'z%te8ZO8en EUor5#.aF]5!aicUyzhXaDq8E7nbOz!lpAj>T9XarmV{vgwL/rKNe' );
define( 'NONCE_SALT',        '*tQ(f@?L6P>:,.lBB1no`2{tQ!c0`,3*~Rt2#:*dSpb%:hj[l#}6Au_}-WJJ{#!h' );
define( 'WP_CACHE_KEY_SALT', 'Wg}=04; qF+2PRY-BMB3t9V<l]O^X&J&q|*.}^ R6JURFJ<0]$_bG6jpThc+M &e' );


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

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
