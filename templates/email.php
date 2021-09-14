<?php
/**
 * The Email template.
 *
 * This template can be overridden by copying it to your-child-theme/email-notifications-for-wp-ulike/email.php.
 *
 * HOWEVER, on occasion Email Notifications For WP ULike will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @since 1.2.0
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$settings 		  = get_option( 'wp_ulike_settings' );
$background_color = ! empty( $settings['templates_group']['background_color'] ) ? $settings['templates_group']['background_color']  : '#f7f7f7';
$border_color = $background_color = ! empty( $settings['templates_group']['border_color'] ) ? $settings['templates_group']['border_color']  : '#FFFFFF';

$footer_text     = apply_filters( 'email_notifications_for_wp_ulike_email_footer_text', sprintf( /* translators: %1$s - Blog URL; %2$s - Blog Title. */ __( '<a href="%1$1s">%2$2s</a>', 'email-notifications-for-wp-ulike' ), get_bloginfo( 'url' ), get_bloginfo() ) );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo esc_url( get_bloginfo() ); ?> </title>
		<style type="text/css">
		body {margin: 0; padding: 0; min-width: 100%!important;}
		.content {width: 100%; max-width: 600px;}  
		</style>
	</head>
	<body>
		<table width="100%" bgcolor="<?php echo esc_attr( $backgound_color ); ?>" style="border: 0.5px solid <?php echo esc_attr( $border_color);?> " cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table class="content" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td style="padding: 20px">
								<?php
									echo $message; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</td>
						</tr>
					</table>

					<table class="content" align="center" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<?php
									echo $footer_text; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
