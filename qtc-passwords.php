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
    }
}

if ( in_array( 'woo-flanny-conversion-tracker/woo-flanny-conversion-tracker.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	register_activation_hook( __FILE__, array( 'QTC_Passwords', 'activate' ) );
    $QTC_Passwords = new QTC_Passwords();
}