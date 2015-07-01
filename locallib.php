<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-6-30
 * Time: 14:15
 */

defined('MOODLE_INTERNAL') || die();

class local_simple_message_interface {

    public function create_conversation() {
        global $DB;
    }

    public function send_message() {
        global $DB, $USER;
    }


}

class local_simple_message_conversation {
    private $id;
    private $last_update;
    private $subject;


    public function __construct($conversationorid, $last_update = 0, $subject = '') {
        if (is_object($conversationorid)) {

            $this->id = $conversationorid->id;
            $this->last_update = $conversationorid->last_update;
            $this->subject = $conversationorid->subject;
        }  else {
            $this->id = $id;
            $this->last_update = $last_update;
            $this->subject = $subject;
        }
    }

    public function fetch_users() {
        global $DB;
    }

    public function fetch_messages() {
        global $DB;
        
    }

    public static function find_conversation($user1, $user2) {
        global $DB;

        $sql = '
SELECT
    c.id,
    c.subject,
    c.last_update
FROM
    {sm_conversation} c
JOIN
    {sm_conversation_users} cu ON c.id = cu.conversationid
JOIN
    {sm_conversation_users} cu2 ON cu2.conversationid = cu.conversationid
WHERE
    cu.userid = ? AND
    cu2.userid = ?';

        $conversations = $DB->get_records_sql($sql, array($user1, $user2));
        if (!empty($conversations)) {
            return new local_simple_message_conversation(reset($conversations));
        }
        return null;
    }

    public static function create_conversation($users, $subject) {
        global $DB;
        $users = $DB->get_records_list('user', 'id', $users);

        $conversation = new stdClass;
        $conversation->subject = $subject;
        $conversation->last_update = time();
        $conversation->id = $DB->insert_record('sm_conversation', $conversation);


        foreach ($users as $user) {
            if ($user->deleted != false) {
                $map = new stdClass;
                $map->conversationid = $conversation->id;
                $map->userid = $user->id;
                $DB->insert_record('sm_conversation_users', $map, true, true);
            }
        }

        return new local_simple_message_conversation($conversation);


    }

}


class local_simple_message_message {
    private $id;
    private $senderid;
    private $body;
    private $timestamp;

    public function __construct($id, $senderid, $body, $timestamp) {
        $this->id = $id;
        $this->senderid = $senderid;
        $this->body = $body;
        $this->timestamp = $timestamp;
    }


}

