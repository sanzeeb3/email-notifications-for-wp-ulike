<?php
/**
 * Plugin Name: Email Notifications For WP ULike
 * Description: Sends email notification whenever you got a like on a post or comment.
 * Version: 1.1.0
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

			$author_id    = get_post_field( 'post_author', $id );
			$author_email = get_the_author_meta( 'user_email', $author_id );

			$title    = get_the_title( absint( $id ) );
			$message  = 'Oh hi, there\'s a like on your post - <i>' . $title . '</i>';
			$message .= '<br><br>Post Link: ' . get_permalink( $id );

		} elseif ( '_commentliked' === $key && 'like' === $status ) {

			$comment         = get_comment( absint( $id ) );
			$comment_content = ! empty( $comment->comment_content ) ? $comment->comment_content : '';
			$author_email    = ! empty( $comment->comment_author_email ) ? $comment->comment_author_email : '';

			$message  = 'Oh hi, there\'s a like on your comment: <br><br><i> ' . $comment_content . '</i>';
			$message .= '<br><br>Post Link: ' . get_permalink( $comment->comment_post_ID );

		} else {
			return;
		}

		if ( $author_email && is_email( $author_email ) ) {

			$header = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $author_email, 'You got a like! ❤️', $message, $header );
		}
	},
	10,
	4
);
