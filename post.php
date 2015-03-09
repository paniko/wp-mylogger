<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/wp-load.php';

if (!current_user_can('manage_options'))
	wp_die('Not allowed');

// Check that nonce field created in configuration form is present
//check_admin_referer('rcs_campaigns_importer');

$options = get_option('wp_my_loggger');

//Conumer Key
$fields = array(
		'path_logger',
);
foreach($fields as $option_name) {
	if(isset($_POST[$option_name])) {
		$options[$option_name] = sanitize_text_field($_POST[$option_name]);
	}
}

update_option('wp_my_loggger', $options);

// Redirect the page to the configuration form that was processed
wp_redirect(add_query_arg(array('page' => 'wp-mylogger', 'message' => '1'), admin_url('admin.php?page=wp-mylogger')));
