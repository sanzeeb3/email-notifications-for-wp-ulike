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

    }

    /**
     * Get milestones section.
     *
     * @since 1.2.0
     *
     * @return array
     */
    public static function get_milestones_section() {

        return array(
            'id'     => 'milestones_group',
            'type'   => 'fieldset',
            'title'  => __( 'Milestones Emails', 'email-notifications-for-wp-ulike' ),
            'fields' => array(
                array(
                    'id'      => 'milestones_email_enable',
                    'type'    => 'switcher',
                    'title'   => esc_html__( 'Enable Milestone Emails', 'email-notifications-for-wp-ulike' ),
                    'default' => true,
                    'desc'    => esc_html__( 'An email sent to the author of the post when the post reaches the milestones. Default: 50 Likes.', 'email-notifications-for-wp-ulike' ),
                ),
            ),
        );
    }
}