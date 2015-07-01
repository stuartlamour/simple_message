<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-6-30
 * Time: 14:07
 */


require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

$conversationid = optional_param('conversation', -1, PARAM_INT);




$context = context_system::instance();




require_login();



$PAGE->set_context($context);

//TODO:
// - set url (when selected a new conversation..)
// - title

$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/simple_message/js/sm.js'));

// Initialize $PAGE
$PAGE->set_url('/local/simple_message/message.php');
$PAGE->set_title(get_string('title', 'local_simple_message'));
$PAGE->set_heading(get_string('title', 'local_simple_message'));


$renderer = $PAGE->get_renderer('local_simple_message');

/// Print the page header

echo $OUTPUT->header();


echo "<div id='sm-wrapper' class='clearfix'>";
echo $renderer->render_conversation();

$conversation = local_simple_message_conversation::find_converstation_by_id($conversationid);



echo $renderer->render_navigation($conversation);
echo "</div>";
echo '<a href="newconversation.php">Add a conversation</a> <br />';





echo $OUTPUT->footer();
