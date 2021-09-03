<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;

/**
 * Unsubscribe from emails.
 *
 * @since 1.2.0
 */
class Unsubscribe {

	/**
	 * Initialize.
	 *
	 * @since 1.2.0
	 */
	public function init() {
		add_filter( 'email_notifications_for_wp_ulike_email_message', array( $this, 'create_unsubscribe_link' ), 10, 3 );
		add_action( 'init', 'process_unsubscribe' );
	}

	/**
	 * Process the unsubscribe link.
	 *
	 * @param  string $message      An email message with smart tag.
	 * @param  int    $post_id      A post ID.
	 * @param  int    $comment_id   A comment ID.
	 *
	 * @return string.
	 */
	public function create_unsubscribe_link( $message, $post_id, $comment_id ) {

		if ( ! empty( $comment_id ) ) {
			$id   = $comment_id;
			$type = 'comment';
		} else {
			$id   = $post_id;
			$type = 'post';
		}

		$return_url = apply_filters( 'email_notifications_for_wp_ulike_return_url', get_permalink( $post_id ) );
		$url        = wp_nonce_url( $return_url . '?unsubscribe_wp_ulike_emails=1&type=' . $type . '&id=' . $id, 'unsubscribe_wp_ulike_emails' );

		$message = str_replace( '{unsubscribe}', $url, $message );

		return $message;
	}

	/**
	 * Unsubscribe the email.
	 *
	 * @return void.
	 */
	public function process_unsubscribe() {

		wp_verify_nonce( 'unsubscribe_wp_ulike_emails' );

		if ( isset( $_GET['unsubscribe_wp_ulike_emails'] ) ) {
			$type = ! empty( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'post';
			$id   = ! empty( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		}

		if ( 'comment' === $type ) {
			$comment        = get_comment( $id );
			$author_email   = ! empty( $comment->comment_author_email ) ? $comment->comment_author_email : '';
		} else{			

			$author_id      = get_post_field( 'post_author', $id );
			$author_email   = get_the_author_meta( 'user_email', $author_id );
		}

		$settings = get_option( 'wp_ulike_settings' );

		$post_group    = $settings['posts_group'];
		$comment_group = $settings['comments_group'];
	}
}
