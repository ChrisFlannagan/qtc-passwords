<?php
/**
 * Plugin Name: Passwords for Quick Tracking Conversions for WooCommerce
 * Description: Password protect your shop and track conversions of passwords used
 * Version:     0.1
 * Author:      Chris Flannagan
 * Author URI:  https://whoischris.com
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'QTC_Passwords' ) ) {
    class QTC_Passwords {
        public function __construct() {
			add_action( 'admin_menu', array( $this, 'qtc_admin_pages' ), 20 );
			add_action( 'template_redirect', array( $this, 'qtc_protect_page' ) );
        }

		/**
		 * Activate the plugin
		 */
		public static function activate() {
			global $wpdb;
			$table_name = $wpdb->prefix . 'qtc_passwords';

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  password varchar(55) NOT NULL,
				  UNIQUE KEY id (id)
				) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		} // END public static function activate

		public function qtc_protect_page() {
		    $found = 0;

		    // Option to reset cookie for testing purposes primarily
		    if ( isset( $_GET['qtc_reset_cookie'] ) ) {
                setcookie( 'qtc_woo_tracking_password', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
                setcookie( 'qtc_woo_tracking_code', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
		    }

            // Check for a submitted password
		    if ( isset( $_POST['qtc_woo_tracking_password'] ) ) {
    			include( sprintf( "%s/control/qtc-password-manager.php", dirname( __FILE__ ) ) );
    			$found = count( QTC_Password_Manager::check_password( $_POST['qtc_woo_tracking_password'] ) );

    			// Was it found
                if ( $found > 0 ) {
                	setcookie( 'qtc_woo_tracking_password', sanitize_text_field( $_POST['qtc_woo_tracking_password'] ), time() + ( 3 * 86400 ), COOKIEPATH, COOKIE_DOMAIN );
                	setcookie( 'qtc_woo_tracking_code', sanitize_text_field( $_POST['qtc_woo_tracking_password'] ), time() + ( 3 * 86400 ), COOKIEPATH, COOKIE_DOMAIN );
                	echo '<p>cookie set</p>';
                } else {
                    echo '<p>Password incorrect</p>';
                }
		    }

		    if ( $this->is_woo() && ! isset( $_COOKIE['qtc_woo_tracking_password'] ) && 0 == $found ) {
		        echo '<form action="" method="POST">This page is password protected: <input type="password" name="qtc_woo_tracking_password" /> <input type="submit" value="Submit Password" /></form>';
		        exit();
		    }
		}

		public function qtc_admin_pages() {
			add_submenu_page( 'qtc-woo-page', 'Woo Conversion Passwords', 'Passwords', 'manage_options', 'qtc-woo-password-settings', array(
				$this,
				'password_page_settings'
			) );
		}

		public function password_page_settings() {
			//Include our settings page template
			include( sprintf( "%s/control/qtc-password-manager.php", dirname( __FILE__ ) ) );
			include( sprintf( "%s/views/qtc-password-page.php", dirname( __FILE__ ) ) );
		}

		private function is_woo() {
		    if ( is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() || is_checkout() || is_wc_endpoint_url() ) {
		        return true;
		    } else {
		        return false;
		    }
		}
    }
}

if ( in_array( 'woo-flanny-conversion-tracker/woo-flanny-conversion-tracker.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	register_activation_hook( __FILE__, array( 'QTC_Passwords', 'activate' ) );
    $QTC_Passwords = new QTC_Passwords();
}