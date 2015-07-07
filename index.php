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

// Add jQuery support for pre-2.9 versions
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');

$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/simple_message/js/sm.js'));

// Initialize $PAGE
$PAGE->set_url('/local/simple_message/message.php');
$PAGE->set_title(get_string('title', 'local_simple_message'));
$PAGE->set_heading(get_string('title', 'local_simple_message'));
$renderer = $PAGE->get_renderer('local_simple_message');
/// Print the page header

echo $OUTPUT->header();

echo "<div id='sm-wrapper' class='clearfix'>";
echo $renderer->render_navigation();

if ($conversationid >= 0) {
	global $DB;
	
	if ($DB->count_records('sm_conversation_users', array('conversationid' => $conversationid, 'userid' => $USER->id)) == 0) {
		// no entry for current user in conversation, so user can't view messages
		error('You cannot view this conversation.');
	}
	
	$conversation = local_simple_message_conversation::find_converstation_by_id($conversationid);
	// print_r($conversation);
	echo $renderer->render_conversation($conversation);
} else {
	echo $renderer->render_welcome_message();
}

echo "</div>";


echo $OUTPUT->footer();
