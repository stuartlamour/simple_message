<?php
/**
 * Created by PhpStorm.
 * User: moni
 * Date: 15-6-30
 * Time: 13:58
 */


defined('MOODLE_INTERNAL') || die;


class local_simple_message_renderer extends plugin_renderer_base {

    public function render_navigation() {
        global $USER;
        $conversations = local_simple_message_conversation::find_conversations_for_user($USER->id);
        
        $output = '';
        foreach ($conversations as $conversation) {
            $url = new moodle_url('/local/simple_message/index.php', array('conversation' => $conversation->id));
            $unreadcount = $conversation->get_unread_count($USER->id);
            $unreadinfo = ($unreadcount > 0) ? " <span class='sm-unread'>" . $unreadcount . "</span></a></li>" : "";
            $output .= "<li><a href='" . $url->out(true) . "#sm-conversation'>" . $conversation->get_name() . $unreadinfo . "\n";
        }
        
        return "<nav id='sm-navigation'>
        <h5>Messages</h5>
        <h6>Direct <a href='newconversation.php'>new message</a></h6>
        <ol>
          $output
        </ol>
        <h6>Course <a href='#sm-conversation'>new message</a></h6>
        <ol>
          <li>Course title <span class='sm-unread'>3</span></li>
          <li>Course title <span class='sm-unread'>1</span></li>
          <li>Course title </li>
        </ol>
        <h6>Group <a href='#sm-conversation'>new message</a></h6>
        <ol>
          <li>Group title <span class='sm-unread'>5</span></li>
          <li>Group title </li>
          <li>Group title </li>
        </ol>
        </nav>";
    }

    public function render_new_conversation() {
      return '
        <form method="post" enctype="multipart/form-data" action="newconversation.php" id="sm-message-form" >
        <div id="sm-new-conversation">
              <input type="text" name="users" id="sm-searchname" placeholder="Search for user" autocomplete="off">
              <div id="sm-users">
              </div>
              <hr>
              <div id="sm-recipients">
              </div>
              <hr>
			  ' . $this->render_message_form() . '
              <hr>

              </div>
              </form>';
    }

    public function render_conversation($conversation) {
        $output = '';
		
		if ($conversation) {
            //global $DB;
            global $DB, $USER;
            $messages = $conversation->fetch_messages();
            $m_output = "<div id='sm-conversation-messages'>";

            // TODO - should we move this object building out of renderer?

            // Get all the conversation users in one db call
            $sendersids = array();
            foreach ($messages as $message) {
                    $sendersids[] = $message->senderid;
            }
            $senders = $DB->get_records_list('user', 'id', $sendersids);
            foreach ($messages as $message) {
				$messagemeta = $this->render_user($senders[$message->senderid]);
				// Display formatted date instead of timestamp
				$messagemeta .= userdate($message->timestamp);
                $m_output .= "<div>$messagemeta<p>". $message->body . "</p><hr></div>";
            }
            $m_output .= "</div>";
			$m_output .= "<hr>";
			$m_output .= $this->render_conversation_reply($conversation);
			
			$output = "<div id='sm-conversation'>
                          <h6>Conversation title</h6>
                          $m_output
                       </div>";

			// $output .= $this->render_user_image();
            
            $lastid = end($messages)->id;
            
            $sql = '
UPDATE
    {sm_conversation_users}
SET
    last_read = :lastid
WHERE
    conversationid = :conversationid AND userid = :userid';
            
            $DB->execute($sql, array('lastid' => $lastid, 'conversationid' => $conversation->id, 'userid' => $USER->id));
        }
		
        return $output;
      }
	  
	  public function render_conversation_reply($conversation) {
        global $DB;
		
		$recipientshtml = '';
		$users = $DB->get_records('sm_conversation_users', array('conversationid' => $conversation->id));
		
		foreach ($users as $user) {
			$recipientshtml .= '<input type="hidden" name="recipient[]" value="' . $user->userid . '" />';
		}
		
		return '
		  <form method="post" enctype="multipart/form-data" action="newconversation.php" id="sm-message-form" >
		  <div id="sm-conversation-reply">
		    ' . $recipientshtml . '
			' . $this->render_message_form() . '
		  </div>
		  </form>';
	  }
	  
	  public function render_message_form() {
	    return '<textarea name="sm_message"></textarea>
                <br>
                <input type="submit" class="btn" value="Send" name="sendbtn" />
                <input type="submit" class="btn btn-link" value="Cancel" name="cancelbtn" />';
	  }
	  
	  public function render_welcome_message() {
	    return "<div id='sm-conversation'>
                   <h6>Welcome to messages</h6>
                   <p>Simple messages lets you to have conversations with individual memebers of your courses,
                    an entire course cohort or pick a few people to have a conversation with.</p>
                 </div>";
	  }
	  



      public function render_user($user = null) {
		  if (is_null($user)) {
		  	global $USER;
          	$user = $USER;
		  }
		  
          $userpicture = new user_picture($user);
          $userpicture->link = false;
          $userpicture->alttext = false;
          $userpicture->size = 38;
          $picture = $this->render($userpicture);
          // name
          $fullname = format_string(fullname($user));
          return "<div class='sm-user'>
                $picture $fullname
                </div>";
      }

      // TODO - what do we pass in?
      public function render_message() {
          // $uprofile = render_user($user);
          return "<div class='sm-message'>
                  $uprofile
                  $mdate
                  $mbody
                  </div>";
      }
}
