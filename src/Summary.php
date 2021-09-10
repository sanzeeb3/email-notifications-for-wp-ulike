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
	 * Initialize.
	 *
	 * @since 1.2.0
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'schedule_summary_email' ) );
		add_action( 'email_notifications_for_wp_ulike_weekly_summary_email', array( $this, 'initiate_email_sending' ) );
	}

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

	/**
	 * Schedule weekly summary email.
	 *
	 * @since 1.2.0
	 *
	 * @return integer|void The actions'd ID or void.
	 */
	public function schedule_summary_email() {

		if ( false === as_next_scheduled_action( 'email_notifications_for_wp_ulike_weekly_summary_email' ) ) {
			as_schedule_recurring_action( strtotime( '+ 7 days' ), WEEK_IN_SECONDS, 'email_notifications_for_wp_ulike_weekly_summary_email', array(), 'email_notifications_for_wp_ulike' );
		}
	}

	/**
	 * Initiate email sending.
	 *
	 * @since 1.2.0
	 *
	 * @return void.
	 */
	public function initiate_email_sending() {

	}
}
