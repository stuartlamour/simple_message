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
    $DB->delete_records('sm_message', array('id' => $messageid));
    echo '{"status":"ok"}';
} else {
    echo '{"status":"error"}';
}
