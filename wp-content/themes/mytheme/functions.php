<?php
//check if WP 2FA plugin is installed and enabled
if( is_plugin_active( 'wp-2fa/wp-2fa.php' ) ) {
	
	//action with priority 11 that will execute right after WP2FA wp_login action
	add_action('wp_login', 'mytheme_intercept_2fa_form', 11, 2);

	//show our own TFA authorization page template
	function mytheme_intercept_2fa_form( $user_login, $user ) {

		//instantiate WP2FA Login class to access checker method
		$login = new WP2FA\Authenticator\Login();

		$url = home_url('/tfa-authorization-code');

		$url = add_query_arg( array(
			'key' => md5($user_login)
		), $url);

		//check if user has 2FA enabled
		if( $login->is_user_using_two_factor( $user->ID ) ) {
			wp_safe_redirect( $url );
			exit;
		}

	}

	//action to handle error states
	add_action('wp_login_failed', 'mytheme_show_2fa_error', 10, 2);

	//handle TFA form error when entering incorrect authorization code or backup code
	function mytheme_show_2fa_error($user_login, $input)  {

		$url = home_url('/tfa-authorization-code');

		$url = add_query_arg( array(
			'key' => md5($user_login),
			'tfa' => 'failed',
			'input' => $input
		), $url);

		wp_safe_redirect( $url );
		exit;
	}
	
}