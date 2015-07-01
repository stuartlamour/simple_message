<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-6-30
 * Time: 14:07
 */


require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');


$context = context_system::instance();

require_login();

$PAGE->set_context($context);

//TODO:
// - set url (when selected a new conversation..)
// - title


// Initialize $PAGE
$PAGE->set_url('/local/simple_message/message.php');
$PAGE->set_title(get_string('title', 'local_simple_message'));
$PAGE->set_heading(get_string('title', 'local_simple_message'));


$renderer = $PAGE->get_renderer('local_simple_message');

/// Print the page header

echo $OUTPUT->header();


/*echo "<div id='sm-wrapper' class='clearfix'>";
echo $renderer->render_conversation();
echo $renderer->render_navigation();
echo "</div>";*/

print_r(local_simple_message_find_users('u'));



echo $OUTPUT->footer();
