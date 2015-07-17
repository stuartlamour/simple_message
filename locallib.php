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
    public $id;
    private $last_update;
    private $subject;
    
    const MAX_USER_NAMES = 3;


    public function __construct($conversationorid, $last_update = 0, $subject = '') {
        if (is_object($conversationorid)) {
            $this->id = $conversationorid->id;
            $this->last_update = $conversationorid->last_update;
            $this->subject = $conversationorid->subject;
        }  else {
            $this->id = $conversationorid;
            $this->last_update = $last_update;
            $this->subject = $subject;
        }
    }

    public function fetch_users() {
        global $DB;
        
        $sql = '
SELECT
    u.*
FROM
    {sm_conversation_users} cu
JOIN
    {user} u ON cu.userid = u.id
WHERE
    cu.conversationid = :conversationid';
        
        return $DB->get_records_sql($sql, array('conversationid' => $this->id));
    }

    public function fetch_messages($from = 0, $to = 50) {
        global $DB;
        // we scroll down to latest message, so order messages by timestamp ascending
		return $DB->get_records('sm_message', array('conversationid' => $this->id), 'timestamp ASC', '*', $from, $to);
    }
    
    public function get_name() {
        if (!empty($this->subject)) {
            return $this->subject;
        } else {
            $users = $this->fetch_users();
            
            $count = 0;
            $usernames = array();
            
            foreach ($users as $user) {
                $usernames[] = fullname($user);
                if (++$count == self::MAX_USER_NAMES) break;
            }
            
            $name = implode(', ', $usernames);
            if (count($users) > self::MAX_USER_NAMES) {
                $name .= get_string('andothers', 'local_simple_message');
            }
            
            return $name;
        }
    }
    
    public function get_unread_count($userid = null) {
        global $DB;
        
        $sql = '
SELECT
    DISTINCT m.id
FROM
    {sm_message} m
WHERE
    m.conversationid = :conversationid1
AND
    m.id > (
        SELECT
            last_read
        FROM
            {sm_conversation_users}
        WHERE
            conversationid = :conversationid2 AND userid = :userid
    )';
        
        return count($DB->get_records_sql($sql, array('conversationid1' => $this->id, 'conversationid2' => $this->id, 'userid' => $userid)));
    }
    
    public function update_last_read() {
        global $DB, $USER;
        
        $messages = $this->fetch_messages();
        if (!empty($messages)) {
            $lastid = end($messages)->id;
            
            $sql = '
UPDATE
    {sm_conversation_users}
SET
    last_read = :lastid
WHERE
    conversationid = :conversationid AND userid = :userid';
            
            $DB->execute($sql, array('lastid' => $lastid, 'conversationid' => $this->id, 'userid' => $USER->id));
        }
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
	
	public static function find_conversation_by_users($userids) {
		global $DB;
		
		$sql = '
SELECT
    c.id,
	c.subject,
	c.last_update
FROM
    {sm_conversation} c
JOIN
    {sm_conversation_users} cu ON cu.conversationid = c.id
WHERE
    cu.userid IN (' . implode(',', array_fill(0, count($userids), '?')) . ')
AND
    cu.conversationid IN 
	(
	SELECT
	    cu2.conversationid 
	FROM 
	    {sm_conversation_users} cu2
	GROUP BY
	    cu2.conversationid
	HAVING
	    COUNT(cu2.userid) = ?
	)
GROUP BY
    c.id 
HAVING
    COUNT(*) = ?';
		
		$params = array();
		foreach ($userids as $userid)
			$params[] = $userid;
		$params[] = count($userids);
		$params[] = count($userids);
		
		$conversations = $DB->get_records_sql($sql, $params);
		if (!empty($conversations)) {
			return new local_simple_message_conversation(reset($conversations));
		}
		return null;
	}

    public static function find_converstation_by_id($convid) {
        global $DB;
        $conversation = $DB->get_record('sm_conversation', array('id' => $convid));
        if ($conversation) {
            return new local_simple_message_conversation($conversation);
        }
        return null;
    }

    public static function find_conversations_for_user($userid) {
        //TODO.....
		global $DB;
		
		$sql = '
SELECT
    c.*
FROM
    {sm_conversation} c
JOIN
    {sm_conversation_users} cu ON cu.conversationid = c.id
WHERE
    cu.userid = ?';
		
		$records = $DB->get_records_sql($sql, array($userid));
		if (!empty($records)) {
			$conversations = array();
			foreach ($records as $record) {
				$conversations[] = new local_simple_message_conversation($record);
			}
			return $conversations;
		}
		return null;
    }

    public function send_message($from, $body) {
        global $DB;
        $message = new stdClass;
        $message->senderid = $from;
        $message->body = $body;
        $message->timestamp = time();
        $message->conversationid = $this->id;
        $message->id =  $DB->insert_record('sm_message', $message);
        return $message;
    }

    public static function create_conversation($users, $subject = '') {
        global $DB;
        
        if (!empty($users)) {
            $conversation = self::find_conversation_by_users($users);
            if (!is_null($conversation)) {
                return $conversation;
            }
        }

        $users = $DB->get_records_list('user', 'id', $users);


        $conversation = new stdClass;
        $conversation->subject = $subject;
        $conversation->last_update = time();
        $conversation->id = $DB->insert_record('sm_conversation', $conversation);

        //print_r($users);
        foreach ($users as $user) {
            if (!$user->deleted) {
                $map = new stdClass;
                $map->conversationid = $conversation->id;
                $map->userid = $user->id;
                $DB->insert_record('sm_conversation_users', $map);
            }
        }

        return new local_simple_message_conversation($conversation);


    }

}



function local_simple_message_find_users($name) {
    global $DB;
    $sql = '
SELECT
    u.id,
    u.firstname,
    u.lastname
FROM
    {user} u
WHERE
    ' . $DB->sql_like('u.firstname', '?', false) . ' OR ' .
    $DB->sql_like('u.lastname', '?', false) .
' LIMIT 0, 15';
    $searchname = $name . '%';
    $users = $DB->get_records_sql($sql, array($searchname, $searchname));
    $pattern = '/^(' . $name . ')/i';
    foreach ($users as $id => $user) {
        $users[$id]->full_name_clear = $user->firstname . ' ' . $user->lastname;
        //TODO: onvert to moodle full name
        $users[$id]->firstname = preg_replace($pattern, '<strong>$1</strong>', $user->firstname);
        $users[$id]->lastname = preg_replace($pattern, '<strong>$1</strong>', $user->lastname);
    }
    return $users;
}
