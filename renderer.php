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
      return '<div id="sm-new-conversation">
              <input list="users" name="users" placeholder="Search for user">
              <datalist id="users">
                <option value="tom brown">
                <option value="tom stan">
                <option value="tom elliot">
                <option value="bob stanley">
                <option value="iggy pop">
                <option value="david bowie">
                <option value="ozzy">
              </datalist>
              <a href="">Add</a>
              <hr>
              <input type="checkbox" name="recepient" id="dave" value="dave" checked> <label for="dave">Dave</label>,
              <input type="checkbox" name="recepient" id="bob" value="bob" checked> <label for="bob">Bob</label>
              <!-- <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>
              <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>
              <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>
              <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>
              <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>
              <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>
              <input type="checkbox" name="recepient" value="dave"> Dave<br>
              <input type="checkbox" name="recepient" value="bob" checked> Bob<br>-->
              <hr>
              <textarea>your message here</textarea>
              <br>
              send
              <a href="#sm-navigation">cancel</a>
              <hr>

              </div>';
    }

    public function render_conversation() {
              return "<div id='sm-conversation'>
                      <h6>Conversation title</h6>
                      <div id='sm-conversation-messages'>

                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>
                      <div>img name date</div>
                      <div><p>Messages</p><hr></div>

                      </div>
                      <hr>
                      <textarea>your message here</textarea>
                      <br>
                      send
                      <a href='#sm-navigation'>cancel</a>
                      </div>";
      }


      public function render_user($user) {
          // icon
          $userpicture = new user_picture($user);
          $userpicture->link = false;
          $userpicture->alttext = false;
          $userpicture->size = 50;
          $picture = $this->render($userpicture);
          // name
          $fullname = format_string(fullname($user));

          return "<div class='sm-user'>
                $picture
                $fullname
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
