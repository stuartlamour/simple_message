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


    public function __construct($id, $last_update, $subject) {
        $this->id = $id;
        $this->last_update = $last_update;
        $this->subject = $subject;
    }

    public function fetch_users() {
        global $DB;
    }

    public function fetch_messages() {
    }


    public static function create_conversation($users, $subject) {
        global $DB;
        $users = $DB->get_records_list('user', 'id', $users);

        $record = new stdClass;
        $record->subject = $subject;
        $record->last_update = time();
        $record->id = $DB->insert_record('sm_conversation', $record);


        foreach ($users as $user) {
            if ($user->deleted != false) {
                $map = new stdClass;
                $map->id = 

            }
        }


    }

    public function
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

