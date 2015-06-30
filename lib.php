<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-6-30
 * Time: 14:01
 */

function local_simple_message_extends_navigation(global_navigation $nav) {
    $nav->add(get_string('title', 'local_simple_message'), new moodle_url('/local/simple_message/index.php'), navigation_node::TYPE_CONTAINER);
}