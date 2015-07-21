<?php

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

$messageid = required_param('sm_message', PARAM_INT);

require_login();

global $DB, $USER;

$sql = '
SELECT
    *
FROM
    {sm_message} m
JOIN
    {sm_conversation} c ON m.conversationid = c.id
JOIN
    {sm_conversation_users} cu ON cu.conversationid = c.id
WHERE
    m.id = :messageid AND cu.userid = :userid';

if ($DB->count_records_sql($sql, array('messageid' => $messageid, 'userid' => $USER->id)) > 0) {
    $message = $DB->get_record('sm_message', array('id' => $messageid));
    $conversationid = $message->conversationid;
    if ($DB->delete_records('sm_message', array('id' => $messageid))) {
        if ($DB->count_records('sm_message', array('conversationid' => $conversationid)) == 0) {
            $DB->delete_records('sm_conversation_users', array('conversationid' => $conversationid));
            $DB->delete_records('sm_conversation', array('id' => $conversationid));
            echo '{"status":"deleted_conversation"}';
            exit();
        }
        //echo '{"status":"ok"}';
        echo '{"status":"deleted_message"}';
        exit();
    }
}

echo '{"status":"error"}';
