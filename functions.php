<?php
/**
 * All the global functions in the plugin.
 *
 * @since 1.3.0
 */

/**
 * Get an email message with included template.
 *
 * @param  string $message An email message.
 *
 * @since 1.3.0
 *
 * @return string An email message.
 */
function en_wpulike_get_email_message_with_template( $message ) {

	ob_start();

	// Allow themes to override the template.
	$template = locate_template(
		'email-notifications-for-wp-ulike/email.php'
	);

	// Themes's template should be given the priority.
	if ( ! file_exists( $template ) ) {
		$template = EMAIL_NOTIFICATIONS_FOR_WP_ULIKE_PLUGIN_PATH . '/templates/email.php';
	}

	include $template;

	$email = ob_get_clean();

	$settings = get_option( 'wp_ulike_settings' );

	return isset( $settings['templates_group']['html_plain'] ) && empty( $settings['templates_group']['html_plain'] ) ? wp_strip_all_tags( $message ) : $email;
}

/**
 * Get header of the email.
 *
 * @since 1.3.0
 *
 * @return mixed Header in string or array.
 */
function en_wpulike_get_email_header() {

	$settings = get_option( 'wp_ulike_settings' );

	return isset( $settings['templates_group']['html_plain'] ) && empty( $settings['templates_group']['html_plain'] ) ? '' : array( 'Content-Type: text/html; charset=UTF-8' );
}
