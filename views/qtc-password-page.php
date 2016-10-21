<?php
	$search = '';
	if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'] ) ) {
		if ( QTC_Password_Manager::create_password( $_POST['tracking_password'] ) ) {
			echo '<h2>New Password Added</h2>';
		}
		$search = $_POST['search_password'];
	}
?>
<div class="wrap">
	<h2>Woo Conversion Tracking Passwords</h2>
	<form action="" method="POST">
		<?php wp_nonce_field(); ?>
		<p>
			New Tracking Password: <input type="text" value="<?php ?>" name="tracking_password" /> <button type="submit">Save Password</button>
		</p>
		<p>
			Search For Password: <input type="text" value="<?php ?>" name="search_password" /> <button type="submit">Search</button>
		</p>
	</form>
	<ul><?php
		QTC_Password_Manager::display_passwords_list( 0, $search );
	?></ul>
</div>