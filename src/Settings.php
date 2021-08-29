<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;

/**
 * Email Notifications For WP ULike Settings.
 *
 * @since 1.1.2
 */
class Settings {

	/**
	 * Initialize.
	 *
	 * @since 1.1.2
	 */
	public function init() {
		add_action( 'wp_ulike_loaded', array( $this, 'add_notification_settings' ) );
	}

	/**
	 * Add Email Notifications section in WP ULike settings page.
	 *
	 * @since 1.1.2
	 */
	public function add_notification_settings() {

		// Email Notifications.
		\ULF::createSection(
			'wp_ulike_settings',
			array(
				'parent' => 'configuration',
				'title'  => esc_html__( 'Email Notifications', 'email-notifications-for-wp-ulike' ),
				'fields' =>

					array(

						array(
							'id'       => 'posts_group',
							'type'     => 'fieldset',
							'title'    => __( 'Posts' ),
							'fields'   => $this->get_posts_options(),
							'sanitize' => 'wp_ulike_sanitize_multiple_select',
						),

						array(
							'id'       => 'comments_group',
							'type'     => 'fieldset',
							'title'    => __( 'Comments' ),
							'fields'   => $this->get_comments_options(),
							'sanitize' => 'wp_ulike_sanitize_multiple_select',
						),
					),
			)
		);
	}

	/**
	 * Get options for posts.
	 *
	 * @since 1.1.2
	 *
	 * @return array Options.
	 */
	public function get_posts_options() {
		return apply_filters(
			'wp_ulike_panel_email_notifications_posts_options',
			array(
				array(
					'id'      => 'post_like_email_enable',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Notifications on posts LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => true,
					'desc'    => esc_html__( 'Email Notification sent to post author when the post gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'post_like_email_suject',
					'type'    => 'text',
					'title'   => esc_html__( 'Email Subject For post LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => __( 'You got a like! ❤️', 'email-notifications-for-wp-ulike' ),
					'desc'    => esc_html__( 'Email subject to the email sent when the post gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'post_like_email_message',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Email Message for post LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => __( 'You got a like! ❤️', 'email-notifications-for-wp-ulike' ),
					'desc'    => esc_html__( 'Email message when the post gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'post_like_email_exclude',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Do not send email to specific author or post', 'email-notifications-for-wp-ulike' ),
					'default' => '',
					'desc'    => esc_html__( 'Add author email, post ID or post URL to exclude separated by comma.', 'email-notifications-for-wp-ulike' ),
				),
			)
		);

	}

	/**
	 * Get options for comments.
	 *
	 * @since 1.1.2
	 *
	 * @return array Options.
	 */
	public function get_comments_options() {

		return apply_filters(
			'wp_ulike_panel_email_notifications_comments_options',
			array(
				array(
					'id'      => 'comment_like_email_enable',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Notifications on comments LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => true,
					'desc'    => esc_html__( 'Email Notification sent to comment author when the comment gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'comment_like_email_suject',
					'type'    => 'text',
					'title'   => esc_html__( 'Email Subject For comment LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => __( 'You got a like! ❤️', 'email-notifications-for-wp-ulike' ),
					'desc'    => esc_html__( 'Email subject to the email sent when the comment gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'comment_like_email_message',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Email Message for comment LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => __( 'You got a like! ❤️', 'email-notifications-for-wp-ulike' ),
					'desc'    => esc_html__( 'Email message when the comment gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'comment_like_email_exclude',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Do not send email to specific author or comment', 'email-notifications-for-wp-ulike' ),
					'default' => '',
					'desc'    => esc_html__( 'Add author email or comment ID to exclude separated by comma.', 'email-notifications-for-wp-ulike' ),
				),
			)
		);
	}
}
