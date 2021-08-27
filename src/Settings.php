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
				'fields' => apply_filters(
					'wp_ulike_panel_email_notifications',
					array(
						array(
							'id'      => 'enable_post_email_notifications',
							'type'    => 'switcher',
							'title'   => esc_html__( 'Enable Notifications on Posts Likes', 'email-notifications-for-wp-ulike' ),
							'default' => false,
							'desc'    => esc_html__( 'Email Notification sent to post author when the post gets a LIKE.', 'email-notifications-for-wp-ulike' ),
						),
					)
				),
			)
		);
	}
}
