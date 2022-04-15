<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'MKwatches' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '12345678' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'QUKisP2ICLH6Wzszs3CJEUw96oRB2sn0DRr1oBHhXjLaETsPaffcgYMvynKby0sk' );
define( 'SECURE_AUTH_KEY',  '1DIh3memqMpQZihX7vQkpXQzGkUgZnKo7lPgniVB0Sl6ViEH3vtEE8mE6dYQ7l7N' );
define( 'LOGGED_IN_KEY',    'nC9uFHnKUnjHMxfKuNeFvarC7RrsDeqweG9DtwkUgwTJMAWAtxvefbKVtfJKus5y' );
define( 'NONCE_KEY',        'VAB8FVAoHVy0b6DgAxWetCANxcE9Zi7ZQVE6WnTWpjzN9tubwEpEirfklSbpZUm5' );
define( 'AUTH_SALT',        'ydLUurtsBMBM96t4z6LxKOTGiPqXfM8Uh0zzc7QdxPiZkSM2stC3bp9rInTDRNqQ' );
define( 'SECURE_AUTH_SALT', 'oEjb6ufZSdnzuBXcIusq38kzeoFPGFOUCMVFep9lHPFcrEdIWfnJrVQ2NTVoyLc2' );
define( 'LOGGED_IN_SALT',   'GHDqgBQtTRFICcH6azTunF1quTQOtpQ4Jc91NS90TPkpn8V5zTc6tvcAjsDdQQn1' );
define( 'NONCE_SALT',       'omKF3LfUFGscwQJHVm6JMsVh6hWUBYdlCpcyTuQE8QiB0iS7jr86hbb6f628K3YL' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
