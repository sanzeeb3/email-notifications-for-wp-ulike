<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;   // Exit if accessed directly.

/**
 * Plugin Class.
 *
 * @since 1.1.2
 */
final class Plugin {

	/**
	 * Instance of this class.
	 *
	 * @since 1.1.2
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.2
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize.
	 *
	 * @since 1.1.2
	 */
	public function init() {

		// Load Settings.
		$settings = new Settings();
		$settings->init();

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wp_ulike_after_process', array( $this, 'process_email_send' ), 10, 4 );
	}

	/**
	 * Load Localisation files.
	 *
	 * @since 1.1.2
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/email-notifications-for-wp-ulike/email-notifications-for-wp-ulike-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/email-notifications-for-wp-ulike-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'email-notifications-for-wp-ulike' );

		load_textdomain( 'email-notifications-for-wp-ulike', WP_LANG_DIR . '/email-notifications-for-wp-ulike/email-notifications-for-wp-ulike-' . $locale . '.mo' );
		load_plugin_textdomain( 'email-notifications-for-wp-ulike', false, plugin_basename( dirname( EMAIL_NOTIFICATIONS_FOR_WP_ULIKE ) ) . '/languages' );
	}

	/**
	 * Process email sending.
	 *
	 * @since 1.1.2
	 *
	 * @return bool|void true when the email is sent.
	 */
	public function process_email_send( $id, $key, $user_id, $status ) {

		if ( '_liked' === $key && 'like' === $status ) {

			$author_id    = get_post_field( 'post_author', $id );
			$author_email = get_the_author_meta( 'user_email', $author_id );

			$title    = get_the_title( absint( $id ) );
			$message  = 'Oh hi, there\'s a new LIKE on your post - <i>' . $title . '</i>';
			$message .= '<br><br>Total number of likes: ' . wp_ulike_get_post_likes( $id ) . '</i>';
			$message .= '<br><br>Post Link: ' . get_permalink( $id );

		} elseif ( '_commentliked' === $key && 'like' === $status ) {

			$comment         = get_comment( absint( $id ) );
			$comment_content = wpautop( get_comment_text( $id ), true );
			$author_email    = ! empty( $comment->comment_author_email ) ? $comment->comment_author_email : '';

			$message  = 'Oh hi, there\'s a new LIKE on your comment: <br><br><i> ' . $comment_content . '</i>';
			$message .= '<br><br>Total number of likes: ' . wp_ulike_get_comment_likes( $id ) . '</i>';
			$message .= '<br><br>Post Link: ' . get_permalink( $comment->comment_post_ID );

		} else {
			return;
		}

		if ( $author_email && is_email( $author_email ) ) {

			$header = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $author_email, 'You got a like! ❤️', $message, $header );
		}
	}
}
