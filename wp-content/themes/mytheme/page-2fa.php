<?php
/* Template Name: 2FA Authorization Page */

get_header();

$auth_form = '';

if (isset($_GET['key'])) {

	$key = sanitize_text_field($_GET['key']);

	$auth_form = get_transient('custom_2fa_auth_form_user_' . $key);
}

// redirect away if no auth form
if (!$auth_form) {
	wp_safe_redirect(home_url('/'));
}
?>

<?php
// Start the Loop.
while (have_posts()) : the_post();
	//$postId = get_the_ID();

?>

	<section id="TopBar">
		<div class="container">
			<h2>Please Enter Authorization Code</h2>
		</div>
	</section>

	<section id="Content">
		<div class="container">

			<?php
			if (isset($_GET['tfa']) && $_GET['tfa'] == 'failed') {

				if (isset($_GET['input'])) {

					if ($_GET['input'] == 'totp') {

						echo '
						<div class="alert alert-danger">
							<i class="icon-cancel-circled"></i>
							<h5>Authentication Failed</h5>
							Incorrect 2FA authorization code. <br>Please make sure you have entered the correct authorization code from the Google Authenticator app.
						</div>';

					} else if ($_GET['input'] == 'backup_codes') {

						echo '
						<div class="alert alert-danger">
							<i class="icon-cancel-circled"></i>
							<h5>Authentication Failed</h5>
							Incorrect backup code. <br>Please make sure you have entered any one of your backup codes.
						</div>';
						
					}
				}
			}
			?>

			<div class="form-container">
				<?php echo $auth_form;  //this is set in WP2FA::Login class 
				?>
			</div>

		</div>
	</section>

<?php
endwhile;
?>

<script>
	jQuery(document).ready(function($) {

		//Toggle between enter Google Authenticator TFA code and enter backup code
		$('.toggle-tfa-input').click(function(event) {

			event.preventDefault();

			var input = $(this).data('input');

			$('#provider').val(input);

			$('.tfa-input-row').hide();
			$('.tfa-input-row').find('input').prop('disabled', 'disabled');

			$('.tfa-input-' + input).show();
			$('.tfa-input-' + input).find('input').removeProp('disabled');

		})

	});
</script>

<?php
get_footer();
