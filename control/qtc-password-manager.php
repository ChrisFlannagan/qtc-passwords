<?php
/**
 * This class manages the data of the passwords coming in and out of the database
 */

if ( defined( ABSPATH ) ) { exit; }

class QTC_Password_Manager {
	public static function create_password( $password ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'qtc_passwords';

		$password = sanitize_text_field( $password );
		if ( 0 == strlen( $password ) ) {
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

	private static function get_passwords( $limit = 0, $search = '' ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'qtc_passwords';

		// allow limits to the query
		if ( intval( $limit ) > 0 ) {
			$limit = ' LIMIT ' . intval( $limit );
		} else {
			$limit = '';
		}

		// allow searchable passwords
		if ( '' != $search ) {
			$search = " WHERE password = '" . sanitize_text_field( $search ) . "' ";
		} else {
			$search = '';
		}

		$results = $wpdb->get_results(
			"
					SELECT *
					FROM $table_name$search
					ORDER BY id DESC$limit
					"
		);

		return $results;
	}

	public static function del_password( $id ) {
		// delete password from list
	}

	public static function display_passwords_list( $limit = 0, $search = '' ) {
		$passwords = self::get_passwords( $limit, $search );
		foreach ( $passwords as $password ) {
			echo '<li>' . esc_html( $password->password ) . '</li>';
		}
	}
}