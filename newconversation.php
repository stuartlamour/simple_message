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

$sendbtn = optional_param('sendbtn', false, PARAM_ALPHA);
$cancelbtn = optional_param('cancelbtn', false, PARAM_ALPHA);


require_login();


if ($cancelbtn) {
    redirect('index.php');
    die;
} else if ($sendbtn) {
    //$messagebody = optional_param('sm_message', false, PARAM_TEXT);
    $messagebody = optional_param('sm_message', false, PARAM_RAW);
    $recipients = optional_param_array('recipient', false, PARAM_INT);
    $url = 'index.php';
    if (!empty($recipients) && !empty($messagebody)) {
        $recipients[] = $USER->id;
        $recipients = array_flip($recipients);
        $recipients = array_keys($recipients);
        $conversation = local_simple_message_conversation::create_conversation($recipients);
        $conversation->send_message($USER->id, $messagebody);
        $url = 'index.php?conversation=' . $conversation->id . '#sm-conversation';
    }

    redirect($url);
    die;
}

$PAGE->set_context($context);

//TODO:
// - set url (when selected a new conversation..)
// - title


// Initialize $PAGE

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');

$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/simple_message/js/sm.js'));

// Import strings for dialog boxes
$PAGE->requires->string_for_js('cantsendempty', 'local_simple_message');
$PAGE->requires->string_for_js('wannadiscard', 'local_simple_message');

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
