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
    foreach ($users as $user) {

        echo '<div class="user" data-id="' . $user->id . '" data-name="' . $user->full_name_clear . '">' . $user->firstname . ' ' . $user->lastname . '</div>';
    }
} else {
    echo '<div class="info">No users found</div>';
}

//TODO: display found users count
