<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;

/**
 * Templates Class. Email template settings and options.
 *
 * @since 1.3.0
 */
class Templates {

	/**
	 * Initialize.
	 *
	 * @since 1.3.0
	 */
	public function init() {
		// Silence is golden, sometimes.
	}

	/**
	 * Get Templates section.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public static function get_templates_section() {

		return array(
			'id'     => 'templates_group',
			'type'   => 'fieldset',
			'title'  => __( 'Customize the email', 'email-notifications-for-wp-ulike' ),
			'fields' => array(
				array(
					'id'      => 'html_plain',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable HTML email', 'email-notifications-for-wp-ulike' ),
					'default' => true,
					'desc'    => esc_html__( 'For best experience, HTML email is recommended. If disabled, plain text email will be sent, which is optimal for email delivery.', 'email-notifications-for-wp-ulike' ),
				),
				array(
					'id'      => 'background_color',
					'type'    => 'color',
					'title'   => esc_html__( 'Background color', 'email-notifications-for-wp-ulike' ),
					'default' => '#f7f7f7',
					'desc'    => esc_html__( 'Background color of email body', 'email-notifications-for-wp-ulike' ),
				),
				array(
					'id'      => 'border_color',
					'type'    => 'color',
					'title'   => esc_html__( 'Border Color', 'email-notifications-for-wp-ulike' ),
					'default' => '#FFFFFF',
					'desc'    => esc_html__( 'Border color of the email body.', 'email-notifications-for-wp-ulike' ),
				),
				array(
					'id'      => 'customize_template',
					'type'    => 'content',
					'title'   => esc_html__( 'Customize the email template', 'email-notifications-for-wp-ulike' ),
					'content' => sprintf(
						wp_kses( /* translators: %1$s - doc link. */
							__( '<a href="%1$s" target="_blank" rel="noopener noreferrer">Learn to fully customize the email template.</a>', 'email-notifications-for-wp-ulike' ),
							array(
								'strong' => true,
								'a'      => array(
									'href'   => true,
									'target' => true,
									'rel'    => true,
								),
							)
						),
						'https://sanjeebaryal.com.np/'
					),
				),
			),
		);
	}
}
