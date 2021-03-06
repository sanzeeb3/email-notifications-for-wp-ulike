<?php

namespace EmailNotificationsForWPULike;

defined( 'ABSPATH' ) || exit;
// Exit if accessed directly.

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

		$classes = array( 'Settings', 'Unsubscribe', 'Summary', 'Milestones', 'Templates', 'Asynchronous' );

		foreach ( $classes as $class ) {
			if ( \class_exists( __NAMESPACE__ . '\\' . $class ) ) {
				$class = __NAMESPACE__ . '\\' . $class;
				$obj   = new $class();
				$obj->init();
			}
		}

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wp_ulike_after_process', array( $this, 'process_email_send' ), 10, 4 );

		// Asynchronously sending emails.
		add_action( 'email_notifications_for_wp_ulike_process_asynchronous_emails', array( $this, 'send' ), 20, 3 );

		add_filter( 'email_notifications_for_wp_ulike_email_message', array( $this, 'process_smart_tags' ), 10, 3 );
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
	 * @param int    $id post or comment ID.
	 * @param string $key liked or comment liked.
	 * @param int    $user_id User ID.
	 * @param string $status like or dislike.
	 *
	 * @since 1.1.2
	 */
	public function process_email_send( $id, $key, $user_id, $status ) {  //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$settings = get_option( 'wp_ulike_settings' );

		if ( '_liked' === $key && 'like' === $status ) {

			$email_settings = $settings['posts_group'];
			$author_id      = get_post_field( 'post_author', $id );
			$author_email   = get_the_author_meta( 'user_email', $author_id );

			$do_not_send = self::do_not_send( 'post', $id, $author_email );

			if ( $do_not_send ) {
				return;
			}

			$subject = ! empty( $email_settings['post_like_email_suject'] ) ? $email_settings['post_like_email_suject'] : esc_html__( 'You got a like! ??????', 'email-notifications-for-wp-ulike' );

			$message = ! empty( $email_settings['post_like_email_message'] ) ? $email_settings['post_like_email_message'] : Settings::get_default_post_email_message();
			$message = apply_filters( 'email_notifications_for_wp_ulike_email_message', wpautop( $message ), $id, '' );

		} elseif ( '_commentliked' === $key && 'like' === $status ) {

			$email_settings = $settings['comments_group'];
			$comment        = get_comment( absint( $id ) );
			$author_email   = ! empty( $comment->comment_author_email ) ? $comment->comment_author_email : '';

			$do_not_send = self::do_not_send( 'comment', $id, $author_email );

			if ( $do_not_send ) {
				return;
			}

			$subject = ! empty( $email_settings['comment_like_email_suject'] ) ? $email_settings['comment_like_email_suject'] : esc_html__( 'You got a like! ??????', 'email-notifications-for-wp-ulike' );

			$message = ! empty( $email_settings['comment_like_email_message'] ) ? $email_settings['comment_like_email_message'] : Settings::get_default_comment_email_message();
			$post_id = $comment->comment_post_ID;
			$message = apply_filters( 'email_notifications_for_wp_ulike_email_message', wpautop( $message ), $post_id, $id );

		} else {
			return;
		}//end if

		$asynchronous = ! empty( $settings['asynchronous_group']['send_emails_asynchronously_enable'] ) ? $settings['asynchronous_group']['send_emails_asynchronously_enable'] : false;

		if ( ! $asynchronous ) {
			$this->send( $author_email, $subject, $message );
		} else {
			as_enqueue_async_action( 'email_notifications_for_wp_ulike_process_asynchronous_emails', array( $author_email, $subject, $message ), 'email_notifications_for_wp_ulike' );
		}
	}

	/**
	 * Send the email now.
	 *
	 * @since 1.4.0 Fragment the methond process_email_send into send to process asynchronous.
	 *
	 * @param string $email The email address to send an email to.
	 * @param string $subject The subject of an email.
	 * @param string $message Email Message.
	 *
	 * @return bool|void true when the email is sent.
	 */
	public function send( $email, $subject, $message ) {

		// Now send.
		if ( $email && is_email( $email ) ) {
			$sent = wp_mail( $email, $subject, \en_wpulike_get_email_message_with_template( $message ), \en_wpulike_get_email_header() );
		}

		do_action( 'email_notifications_for_wp_ulike_after_email_sent', $sent );
	}

	/**
	 * Do not send email if the notification is disabled, excluded from the settings, or is unsubscribed.
	 *
	 * @param string $context post or comment.
	 * @param int    $id      Post ID or comment ID.
	 * @param string $author_email Author Email.
	 *
	 * @since 1.1.2
	 *
	 * @return bool
	 */
	public static function do_not_send( $context, $id, $author_email ) {

		$settings = get_option( 'wp_ulike_settings' );

		$email_settings      = isset( $settings[ $context . 's_group' ] ) ? $settings[ $context . 's_group' ] : array();
		$unsubscription_list = get_option( 'wp_ulike_unsubscription_list', array() );

		if ( isset( $email_settings[ $context . '_like_email_enable' ] ) && empty( $email_settings[ $context . '_like_email_enable' ] ) ) {
			return true;
		}

		$do_not_send = array();
		if ( ! empty( $email_settings[ $context . '_like_email_do_not_send' ] ) ) {
			$do_not_send = explode( ',', trim( $email_settings[ $context . '_like_email_do_not_send' ] ) );
		}

		$excludes = array_merge( $do_not_send, $unsubscription_list );

		foreach ( $excludes as $exclude ) {
			if ( $exclude == $id || get_permalink( $id ) == $exclude || $exclude == $author_email ) { //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				return true;
			}
		}

		return false;
	}

	/**
	 * Process smart tags.
	 *
	 * @param  string $message      An email message with smart tag.
	 * @param  int    $post_id      A post ID.
	 * @param  int    $comment_id   A comment ID.
	 *
	 * @todo:: Improve search and replace.
	 *
	 * @since 1.1.2
	 *
	 * @return string Final email message.
	 */
	public function process_smart_tags( $message, $post_id, $comment_id ) {

		// It's the comment being liked.
		if ( ! empty( $comment_id ) ) {

			$tags    = array( '{total_comment_likes}', '{comment}', '{post/comment}', '{title}', '{milestone}' );
			$replace = array( wp_ulike_get_comment_likes( $comment_id ), '<i>' . wpautop( get_comment_text( $comment_id ) . '</i>', true ), 'comment', '<i>' . wpautop( get_comment_text( $comment_id ) . '</i>', true ), wp_ulike_get_comment_likes( $comment_id ) );

			$message = str_replace( $tags, $replace, $message );

		} else {

			$tags    = array( '{post/comment}', '{title}', '{milestone}' );
			$replace = array( 'post', '<i>' . get_the_title( $post_id ) . '</i>', wp_ulike_get_post_likes( $post_id ) );

			$message = str_replace( $tags, $replace, $message );
		}

		$tags    = array( '{post_title}', '{total_post_likes}', '{post_link}' );
		$replace = array( '<i>' . get_the_title( $post_id ) . '</i>', wp_ulike_get_post_likes( $post_id ), get_permalink( $post_id ) );

		$message = str_replace( $tags, $replace, $message );

		return $message;
	}
}
