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

						Summary::get_weekly_summary_section(),

						array(
							'id'     => 'motivation',
							'type'   => 'fieldset',
							'title'  => __( 'Boost my motivation?', 'email-notifications-for-wp-ulike' ),
							'fields' => array(
								array(
									'id'      => 'review_text',
									'type'    => 'content',
									'content' => sprintf(
										wp_kses( /* translators: %1$s - WP.org link; %2$s - same WP.org link. */
											__( 'Please rate <strong>Email Notifications For WP ULike</strong> <a href="%1$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you!', 'email-notifications-for-wp-ulike' ),
											array(
												'strong' => true,
												'a'      => array(
													'href' => true,
													'target' => true,
													'rel'  => true,
												),
											)
										),
										'https://wordpress.org/support/plugin/email-notifications-for-wp-ulike/reviews/#new-post',
										'https://wordpress.org/support/plugin/email-notifications-for-wp-ulike/reviews/#new-post'
									),
								),
							),
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
					'default' => esc_html__( 'You got a like! ❤️', 'email-notifications-for-wp-ulike' ),
					'desc'    => esc_html__( 'Email subject to the email sent when the post gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'post_like_email_message',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Email Message for post LIKES', 'email-notifications-for-wp-ulike' ),
					'default' => $this->get_default_post_email_message(),
					'desc'    => esc_html__( 'Email message when the post gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'post_like_email_do_not_send',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Do not send email to specific author or post', 'email-notifications-for-wp-ulike' ),
					'default' => '',
					'desc'    => esc_html__( 'Add author email, post ID or post URL to not send email to, separated by comma.', 'email-notifications-for-wp-ulike' ),
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
					'default' => $this->get_default_comment_email_message(),
					'desc'    => esc_html__( 'Email message when the comment gets a LIKE.', 'email-notifications-for-wp-ulike' ),
				),

				array(
					'id'      => 'comment_like_email_do_not_send',
					'type'    => 'textarea',
					'title'   => esc_html__( 'Do not send email to specific author or comment', 'email-notifications-for-wp-ulike' ),
					'default' => '',
					'desc'    => esc_html__( 'Add author email or comment ID to not send email to, separated by comma.', 'email-notifications-for-wp-ulike' ),
				),
			)
		);
	}

	/**
	 * Default email message for posts.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public static function get_default_post_email_message() {

		return 'Oh hi, there\'s a new LIKE on your post - "{post_title}"

Total number of likes: {total_post_likes}

Post Link: {post_link}';
	}

	/**
	 * Default email message for posts.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public static function get_default_comment_email_message() {

		return 'Oh hi, there\'s a new LIKE on your comment:


{comment}


Total number of likes: {total_comment_likes}

Post Link: {post_link}';
	}
}
