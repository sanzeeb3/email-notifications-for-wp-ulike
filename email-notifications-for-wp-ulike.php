<?php
/**
 * Plugin Name: Email Notifications For WP ULike
 * Description: Sends email notification whenever you got a like on a post or comment.
 * Version: 1.1.3
 * Author: Sanjeev Aryal
 * Author URI: http://www.sanjeebaryal.com.np
 * Text Domain: email-notifications-for-wp-ulike
 * Domain Path: /languages/
 *
 * @package    Email Notifications For WP ULike
 * @author     Sanjeev Aryal
 * @link       https://github.com/sanzeeb3/email-notifications-for-wp-ulike
 * @since      1.0.0
 * @license    GPL-3.0+
 */

defined( 'ABSPATH' ) || exit;

define( 'EMAIL_NOTIFICATIONS_FOR_WP_ULIKE', __FILE__ );

/**
 * Plugin version.
 *
 * @var string
 */
const EMAIL_NOTIFICATIONS_FOR_WP_ULIKE_VERSION = '1.1.3';

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Return the main instance of Plugin Class.
 *
 * @since  1.0.0
 *
 * @return Plugin.
 */
function email_notifications_for_wp_ulike() {
	$instance = \EmailNotificationsForWPULike\Plugin::get_instance();
	$instance->init();

	return $instance;
}

email_notifications_for_wp_ulike();
