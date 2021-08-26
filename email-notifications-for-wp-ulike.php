<?php
/**
 * Plugin Name: Email Notifications For WP ULike.
 * Description: Sends email notification whenever you got a like on a post or comment.
 * Version: 1.0.0
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

/**
 * Literally, the initial plugin.
 *
 * @since 1.0.0
 */
add_action(
	'wp_ulike_after_process',
	function( $id, $key, $user_id, $status ) {

		if ( '_liked' === $key && 'like' === $status ) {

			$title   = get_the_title( absint( $id ) );
			$message = 'Oh hi, there\'s a like on post - ' . $title;
		}

		if ( '_commentliked' === $key && 'like' === $status ) {

			$comment = get_comment( absint( $id ) );
			$comment = ! empty( $comment->comment_content ) ? $comment->comment_content : '';

			$message = 'Oh hi, there\'s a like on comment:' . $comment;

		}

		wp_mail( get_option( 'admin_email' ), 'You got a like! ❤️', $message );

	},
	10,
	4
);
