<?php
/**
 * This class manages the data of the passwords coming in and out of the database
 */

if ( defined( ABSPATH ) ) { exit; }

class QTC_Password_manager {
	public static function create_password( $password ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'qtc_passwords';

		$password = sanitize_text_field( $password );
		if ( count( $password ) == 0 ) {
			return false;
		}

		$wpdb->insert(
			$table_name,
			array(
				'password' => $password
			)
		);

		return true;
	}

	public static function get_passwords( $limit = 0 ) {
		$table_name = $wpdb->prefix . 'qtc_passwords';
		if ( intval( $limit ) > 0 ) {
			$limit = ' LIMIT ' . intval( $limit );
		} else {
			$limit = '';
		}
		if( ! empty( $_POST['pid'] ) ) {
			$table_name .= ' WHERE postid="' . intval( $_POST['pid'] ) . '" ';
		}
		$results = $wpdb->get_results(
			"
					SELECT *
					FROM $table_name
					ORDER BY id DESC$limit
					"
		);
		return $results;
	}
}