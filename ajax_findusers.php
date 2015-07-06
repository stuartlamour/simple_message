<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-7-1
 * Time: 12:46
 */


define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

$name = required_param('sm_name', PARAM_ALPHA);

require_login();


if (strlen($name) > 0) {

    $users = local_simple_message_find_users($name);
	
	if (count($users) > 0) {
		foreach ($users as $user) {
			echo '<div class="sm-user" data-id="' . $user->id . '" data-name="' . $user->full_name_clear . '">' . $user->firstname . ' ' . $user->lastname . '</div>';
		}
		
		$info = '';
		
		if (count($users) == 1) $info = get_string('oneuserfound', 'local_simple_message');
		else $info = get_string('xusersfound', 'local_simple_message', count($users));
		
		echo '<div class="info">' . $info . '</div>';
	} else {
		echo '<div class="info">' . get_string('nousersfound', 'local_simple_message') . '</div>';
	}
}
