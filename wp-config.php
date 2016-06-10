<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'qAHvOTtLYr');

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
define('AUTH_KEY',         'HrBV-GotqL#el$7F/IWD7VN|?GzcT$?EzKwoJ9[xSy,y7s)Yn9.5{MoeT=t*zBp9');
define('SECURE_AUTH_KEY',  '@]7))buicQpPYDD2^@*Dwe[Zq.KD-y]eMLybB*zv)5PCZ5%JU~$u^`9C}J>aIeU-');
define('LOGGED_IN_KEY',    'SI/53$f<I`*3/5gIiY$IW#|. D07;c`/o@PkYpJZNzMU<_i>bjxrUnZPfB{1rmZj');
define('NONCE_KEY',        ',CZ`+u97PcIo5L(PnXU~3/nd8LZyh5/GNTMp:e+h5&$;)KWVaq1rL*ErNO:Zpn(h');
define('AUTH_SALT',        ']T^}Uk>M*|Z!JM1V$^ FyE/]jmg ^^KiN^<c!:Y,M/5Sv=f:kooytfg}6Ec1m~7~');
define('SECURE_AUTH_SALT', '(-o2NH;[|X}fejrR56m?ylh}<] 7Js4s.o%vij!I% cSmb0^bI:uFOk0F{V]Fxa9');
define('LOGGED_IN_SALT',   '6B?7/<%Ue,BNx<]=U^~;%`S-o[_X`<>/$Fw5Fkm13y`:drjw)mb#nwC{|OB_efe~');
define('NONCE_SALT',       'b=L]`W5uT]9eBB1.g*m|_Hvy0.{V;r%<aHLFS(Jmosh%m!GgBq(;`p{Sw,R;^{CB');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
