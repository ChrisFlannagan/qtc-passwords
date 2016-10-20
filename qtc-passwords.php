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

		public function qtc_admin_pages() {
			add_submenu_page( 'qtc-woo-page', 'Woo Conversion Passwords', 'Passwords', 'manage_options', 'qtc-woo-password-settings', array(
				$this,
				'password_page_settings'
			) );
		}

		public function password_page_settings() {
			//Include our settings page template
			include( sprintf( "%s/views/qtc-password-page.php", dirname( __FILE__ ) ) );
		}
    }
}

if ( in_array( 'woo-flanny-conversion-tracker/woo-flanny-conversion-tracker.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$QTC_Passwords = new QTC_Passwords();
}