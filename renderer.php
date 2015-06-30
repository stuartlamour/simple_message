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
        return "<div id='sm-navigation'>
        <h2>Direct Messages</h2>
        <ol>
          <li>User name <span class='sm-unread'>3</span></li>
          <li>User name <span class='sm-unread'>3</span></li>
          <li>User name <span class='sm-unread'>3</span></li>
        </ol>
        <h2>My Courses</h2>
        <ol>
          <li>Course title <span class='sm-unread'>3</span></li>
          <li>Course title <span class='sm-unread'>3</span></li>
          <li>Course title <span class='sm-unread'>3</span></li>
        </ol>
        <h2>Group Chat</h2>
        <ol>
          <li>Group title <span class='sm-unread'>3</span></li>
          <li>Group title <span class='sm-unread'>3</span></li>
          <li>Group title <span class='sm-unread'>3</span></li>
        </ol>
        <div>";
    }

    public function render_existing_conversations() {
    }

    public function render_conversation() {
      return "<div id='sm-conversation'>
              <textarea>your message here</textarea>
              send
              </div>";
    }
}
