<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin/onboarding
 */
?>
<?php 
	$form_fields = apply_filters( 'mwb_on_boarding_form_fields', array() );
?>

<?php if ( ! empty( $form_fields ) ) : ?>
	<div class="mwb-on-boarding-wrapper-background">
		<div class="mwb-on-boarding-wrapper">
			<h3 class="mwb-on-boarding-heading">Welcome to MakeWebBetter </h3>
			<p class="mwb-on-boarding-desc">We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.</p>
			<form action="#" method="post" class="mwb-on-boarding-form">
				<?php foreach ( $form_fields as $key => $field_attr ) : ?>
					<?php echo $this->render_field_html( $field_attr ); ?>
				<?php endforeach; ?>
				<div class="mwb-on-boarding-form-submit">
					<input type="submit" class="mwb-on-boarding-submit" name="Send Us Data">
				</div>
			</form>
		</div>
	</div>
<?php endif; ?>