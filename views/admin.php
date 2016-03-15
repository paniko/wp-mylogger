<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP My Logger
 * @author    Nicolò Palmigiano
 * @copyright 2015 PaNiko
 */
	$options = get_option('wp_my_loggger'); 
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
<?php
	if(isset($_GET['message']) && $_GET['message'] == '1') {
?>
		<div id='message' class='updated fade'><p><strong>Configurazione salvata.</strong></p></div>
<?php 
	}
?>
	
	<form name="wp_my_logger_form" method="post" action="<?php echo plugins_url().'/wp-mylogger/post.php'; ?>">
		
		<input type="hidden" name="action" value="save_wp_mylogger_options" />
		<!-- Adding security through hidden referrer field -->
		<?php wp_nonce_field('wp_mylogger'); ?>
		
		<h3>WP MyLogger</h3>
		<p>Setting here the parameter for your configuration logger.</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Path file logger</th>
					<td>
						<input type="text" name="path_logger" value="<?php echo esc_html($options['path_logger']); ?>" class="regular-text"><br>
						<p class="description" style="color: red">WARN: make sure the path configured to have write permissions</p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="wp-mylogger-save" id="wp-mylogger-save" class="button button-primary" value="<?php echo __('Save configuration')?>">
		</p>
	</form>

</div>
