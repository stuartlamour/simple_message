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
        return "<nav id='sm-navigation'>
        <h5>Messages</h5>
        <h6>Direct <a href='newconversation.php'>new message</a></h6>
        <ol>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li><a href='#sm-conversation'>User name</a> </li>
          <li><a href='#sm-conversation'>User name</a> <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
          <li><a href='#sm-conversation'>User name <span class='sm-unread'>3</span></a></li>
          <li>User name </li>
          <li>User name <span class='sm-unread'>2</span></li>
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
        <form method="post" enctype="multipart/form-data" action="newconversation.php" >
        <div id="sm-new-conversation">
              <input type="text" name="users" id="sm-searchname" placeholder="Search for user" autocomplete="off">
              <div id="sm-users">
              </div>
              <hr>
              <div id="sm-recipients">
              </div>
              <hr>
              <textarea name="sm-message">your message here</textarea>
              <br>
                <input type="submit" class="btn" value="Send" name="sendbtn" />
                <input type="submit" class="btn btn-link" value="Cancel" name="cancelbtn" />
              <hr>

              </div>
              </form>';
    }

    public function render_conversation($conversation) {
        if ($conversation) {
          global $DB;
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
                $messagemeta .= $message->timestamp;
                $m_output .= "<div>$messagemeta<p>". $message->body . "</p><hr></div>";
            }
            $m_output .= "</div>";
            $m_output .= "<hr>
                          <textarea>your message here</textarea>
                          <br>
                          send
                          <a href='#sm-navigation'>cancel</a>";
        }
        else {
          // TODO - no conversation passed in...
          // How did we get to this state? What is a good thing to show?
          $m_output = "<h6>Welcome to messages</h6>
          <p>Simple messages lets you to have conversations with individual memebers of your courses,
           an entire course cohort or pick a few people to have a conversation with.</p>";
        }

        $output = "<div id='sm-conversation'>
                      <h6>Conversation title</h6>
                      $m_output
                    </div>";

        // $output .= $this->render_user_image();
        return $output;
      }


      public function render_user($user) {
          global $USER;
          $user = $USER;
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
