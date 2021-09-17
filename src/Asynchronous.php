<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;

/**
 * Send emails asynchronously.
 *
 * @since 1.4.0
 */
class Asynchronous {
	
	/**
	 * Initiate the class.
	 * 
	 * @since 1.4.0
	 */
	public function init() {}

	/**
	 * Get settings.
	 *
	 * @since 1.4.0
	 * 
	 */
	public static function get_asynchronous_section() {

		return array(
			'id'     => 'asynchronous_group',
			'type'   => 'fieldset',
			'title'  => __( 'Send emails asynchronously', 'email-notifications-for-wp-ulike' ),
			'fields' => array(
				array(
					'id'      => 'send_emails_asynchronously_enable',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable sending emails asynchronously', 'email-notifications-for-wp-ulike' ),
					'default' => false,
					'desc'    => esc_html__( 'Sending emails asynchronously in the background helps processing the like faster, but the emails can be delayed on low traffic-sites.', 'email-notifications-for-wp-ulike' ),
				),
			)
		);
	}
}