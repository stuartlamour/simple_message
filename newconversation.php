<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-6-30
 * Time: 14:07
 */


require_once('../../config.php');


$context = context_system::instance();

require_login();

$PAGE->set_context($context);

//TODO:
// - set url (when selected a new conversation..)
// - title


// Initialize $PAGE
$PAGE->set_url('/local/simple_message/newconversation.php');
$PAGE->set_title(get_string('title', 'local_simple_message'));
$PAGE->set_heading(get_string('title', 'local_simple_message'));


$renderer = $PAGE->get_renderer('local_simple_message');

/// Print the page header
echo $OUTPUT->header();
echo "<div id='sm-wrapper' class='clearfix'>";
echo $renderer->render_new_conversation();
echo "</div>";

echo $OUTPUT->footer();