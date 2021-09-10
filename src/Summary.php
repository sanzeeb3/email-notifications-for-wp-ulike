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
		add_action( 'email_notifications_for_wp_ulike_weekly_summary_email', array( $this, 'send' ) );
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
			'id'     => 'summary_group',
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

		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			return;
		}

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
	public function send() {

		$settings = get_option( 'wp_ulike_settings' );

		if ( empty( $settings['summary_group']['weekly_summary_email_enable'] ) ) {
			return;
		}

		$top_posts = wp_ulike_get_most_liked_posts( 5, array( 'post' ), 'post', 'week', 'like' );

		if ( empty( $top_posts ) ) {
			return;
		}

		$header  = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject = apply_filters( 'email_notifications_for_wp_ulike_weekly_summary_email_subject', esc_html__( 'Weekly Likes ❤️', 'email-notifications-for-wp-ulike' ) );
		$send_to = apply_filters( 'email_notifications_for_wp_ulike_weekly_summary_email_receipent', get_option( 'admin_email' ) );

		$message = wpautop( $this->get_weekly_summary_email_message( $top_posts ) );

		ob_start();

		// Allow themes to override the template.
		$template = locate_template(
			'email-notifications-for-wp-ulike/template.php'
		);

		// Themes's template should be given the priority.
		if ( ! file_exists( $template ) ) {
			$template = 'templates/template.php';
		}

		include $template;

		$email = ob_get_clean();

		$sent = wp_mail( $send_to, $subject, $email, $header );
	}

	/**
	 * Weekly summary email message content.
	 *
	 * @param array $top_posts Top posts in terms of likes.
	 *
	 * @since 1.2.0
	 *
	 * @return string A email message content.
	 */
	public function get_weekly_summary_email_message( $top_posts ) {

		$combine = '';

		foreach ( $top_posts as $post ) {
			$combine .= '<li><a target="_blank" rel="noopener noreferrer" href=" ' . get_permalink( $post->ID ) . ' ">' . $post->post_title . '</a> - <strong> ' . wp_ulike_get_post_likes( $post->ID ) . '</strong> likes.' . '</li>'; //phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
		}

		return sprintf(
			wp_kses(
					/* translators: %1$s - top liked posts */
				__(
					'<b>Hi there,</b>

Let\'s see how your contents performed in the past week in terms of LIKES.

the Top 5 posts this past week:

%1$s

',
					'email-notifications-for-wp-ulike'
				),
				array(
					'strong' => array(),
					'li'     => array(),
					'a'      => array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
					),
				)
			),
			$combine
		);

	}
}
