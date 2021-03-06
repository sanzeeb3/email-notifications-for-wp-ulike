<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;

/**
 * Milestones Class. Emails sent when milestones is reached.
 *
 * @since 1.3.0
 */
class Milestones {

	/**
	 * Initialize.
	 *
	 * @since 1.3.0
	 */
	public function init() {
		add_action( 'wp_ulike_after_process', array( $this, 'process_email_send' ), 20, 4 );
	}

	/**
	 * Process sending milestone email.
	 *
	 * @param int    $id post or comment ID, actually id of the content being liked.
	 * @param string $key post liked or comment liked.
	 * @param int    $user_id User ID.
	 * @param string $status like or dislike.
	 *
	 * @todo simplify this method. Merge with Plugin.php 's process_email_send method.
	 *
	 * @since 1.3.0
	 *
	 * @return bool.
	 */
	public function process_email_send( $id, $key, $user_id, $status ) { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$settings            = get_option( 'wp_ulike_settings' );
		$milestones_settings = isset( $settings['milestones_group'] ) ? $settings['milestones_group'] : array();

		if ( '_liked' === $key && 'like' === $status ) {

			// Do not send for milestones if disabled specific to post.
			if ( isset( $milestones_settings['milestone_like_email_enable_for_posts'] ) && empty( $milestones_settings['milestone_like_email_enable_for_posts'] ) ) {
				return;
			}

			$author_id    = get_post_field( 'post_author', $id );
			$author_email = get_the_author_meta( 'user_email', $author_id );
			$total_likes  = wp_ulike_get_post_likes( $id );
			$post_id      = $id;
			$comment_id   = '';

		} elseif ( '_commentliked' === $key && 'like' === $status ) {

			// Do not send for milestones if disabled specific to comment.
			if ( isset( $milestones_settings['milestone_like_email_enable_for_comments'] ) && empty( $milestones_settings['milestone_like_email_enable_for_comments'] ) ) {
				return;
			}

			$comment_id   = $id;
			$comment      = get_comment( absint( $id ) );
			$post_id      = $comment->comment_post_ID;
			$author_email = ! empty( $comment->comment_author_email ) ? $comment->comment_author_email : '';

			$total_likes = wp_ulike_get_comment_likes( $id );

		} else {
			return;
		}//end if

		$do_not_send = Plugin::do_not_send( 'milestone', $id, $author_email );

		if ( $do_not_send ) {
			return;
		}

		$asynchronous = ! empty( $settings['asynchronous_group']['send_emails_asynchronously_enable'] ) ? $settings['asynchronous_group']['send_emails_asynchronously_enable'] : false;
		$subject      = esc_html__( 'Congratulations! You reached a milestone! ????', 'email-notifications-for-wp-ulike' );
		$subject      = ! empty( $milestones_settings['milestone_like_email_subject'] ) ? $milestones_settings['milestone_like_email_subject'] : $subject;
		$message      = self::get_default_milestone_email_message();
		$message      = ! empty( $milestones_settings['milestone_like_email_message'] ) ? $milestones_settings['milestone_like_email_message'] : $message;
		$message      = apply_filters( 'email_notifications_for_wp_ulike_email_message', wpautop( $message ), $post_id, $comment_id );

		$milestone = ! empty( $milestones_settings['milestone'] ) ? $milestones_settings['milestone'] : '50';
		$milestone = explode( ',', $milestone );

		if ( in_array( $total_likes, $milestone ) ) { //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

			if ( ! $asynchronous ) {
				Plugin::send( $author_email, $subject, $message );
			} else {
				as_enqueue_async_action( 'email_notifications_for_wp_ulike_process_asynchronous_emails', array( $author_email, $subject, $message ), 'email_notifications_for_wp_ulike' );
			}
		}

	}

	/**
	 * Get milestones section.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public static function get_milestones_section() {

		return array(
			'id'     => 'milestones_group',
			'type'   => 'fieldset',
			'title'  => __( 'Milestones Emails ???? <br><br> (posts and comments milestones).', 'email-notifications-for-wp-ulike' ),
			'fields' => array(
				array(
					'id'      => 'milestone_like_email_enable_for_posts',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Milestone Emails For Posts', 'email-notifications-for-wp-ulike' ),
					'default' => true,
					'desc'    => esc_html__( 'An email sent to the author of the post when the post reaches the milestones.', 'email-notifications-for-wp-ulike' ),
				),
				array(
					'id'      => 'milestone_like_email_enable_for_comments',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Milestone Emails For Comments', 'email-notifications-for-wp-ulike' ),
					'default' => true,
					'desc'    => esc_html__( 'An email sent to commenter when the comment reaches the milestones.', 'email-notifications-for-wp-ulike' ),
				),
				array(
					'id'      => 'milestone',
					'type'    => 'text',
					'title'   => esc_html__( 'Milestones ????', 'email-notifications-for-wp-ulike' ),
					'default' => '50',
					'desc'    => esc_html__( 'A milestone LIKES when the email is to be sent. Default: 50 likes which means the email is sent when the post or comment reached 50 likes. Multiple milestones can be entered separated by comma.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'milestone_like_email_subject',
					'type'    => 'text',
					'title'   => esc_html__( 'Email Subject For Milestone LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => esc_html__( 'Congratulations! You reached a milestone! ????', 'email-notifications-for-wp-ulike' ),
					'desc'    => esc_html__( 'Email subject to the email sent when the post or comment reaches the milestone.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'milestone_like_email_message',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Email Message for Milestone LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => self::get_default_milestone_email_message(),
					'desc'    => esc_html__( 'Email message when the post or comment reaches a milestone.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'milestone_like_email_do_not_send',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Do not send milestone email to specific author or post', 'email-notifications-for-wp-ulike' ),
					'default' => '',
					'desc'    => esc_html__( 'Add author email, post ID or post URL to not send email to, separated by comma.', 'email-notifications-for-wp-ulike' ),
				),
			),
		);
	}

	/**
	 * Default email message for milestone likes.
	 *
	 * @since 1.3.0
	 *
	 * @return string
	 */
	public static function get_default_milestone_email_message() {

		return 'Oh hi, your {post/comment}


{title}


reached a milestone of {milestone} LIKES.

That\'s great news! ????

Post Link: {post_link}';
	}
}
