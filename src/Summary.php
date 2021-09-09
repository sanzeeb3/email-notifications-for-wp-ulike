<?php
/**
 * The weekely summary email is sent using Action Scheduler.
 *
 * Action Scheduler Homepage: https://actionscheduler.org/api/
 *
 * Github: https://github.com/woocommerce/action-scheduler
 */

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;

/**
 * Weekly Summary Email Class.
 *
 * @since 1.2.0
 */
class Summary {

	/**
	 * Get weekly summary email section.
	 *
	 * @since 1.2.0
	 *  
	 * @return array
	 */
	public static function get_weekly_summary_section() {

		return array(
			'id'     => 'weekly-summary',
			'type'   => 'fieldset',
			'title'  => __( 'Weekly Summary Email', 'email-notifications-for-wp-ulike' ),
			'fields' => array(
				array(
					'id'      => 'weekly_summary_email_enable',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Weekly Summary Email', 'email-notifications-for-wp-ulike' ),
					'default' => true,
					'desc'    => esc_html__( 'A summary of how your posts performed in terms of LIKES, past week. The email is by default sent to site administrator.', 'email-notifications-for-wp-ulike' ),
				),
			),
		);
	}
}
