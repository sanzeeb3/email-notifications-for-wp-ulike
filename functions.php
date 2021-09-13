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

	return $email;
}