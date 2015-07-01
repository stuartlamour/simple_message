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
        echo '<option value="' . $user->id . '">' . $user->firstname . ' ' . $user->lastname . '</option>';
    }
}
