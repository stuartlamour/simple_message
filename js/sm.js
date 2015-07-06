
/* Scroll to bottom of user messages */
// TODO - this should really scroll to the first unread iteam, and focus for screenreaders.
function setMessageScroll() {
	var messageWindow = document.getElementById("sm-conversation-messages");
	if(messageWindow) {
		messageWindow.scrollTop = messageWindow.scrollHeight;
	}
}


/* ajax search users */
$(document).ready( function() {
	setMessageScroll(); // execute when DOM is fully available
    $('#sm-new-conversation #sm-users').hide();
    $('#sm-new-conversation #sm-searchname').keyup(function(e) {
        $('#sm-new-conversation #sm-users .sm-user').off('click');
        $.ajax({
            'url': M.cfg.wwwroot + '/local/simple_message/ajax_findusers.php',
            'data': 'sm_name=' + e.currentTarget.value,
            'method': 'get',
            'dataType': 'html',
            'success': function(data, textStatus, jqXHR) {
                if (data.length > 0) {
                    $('#sm-new-conversation #sm-users').html(data);
                    $('#sm-new-conversation #sm-users .sm-user').click(local_sm_user_select);
                    $('#sm-new-conversation #sm-users').show();
                }
            }
        });

    });

});

function local_sm_user_select(e) {
    var u = $(this);
    var holder = u.parent('#sm-new-conversation #sm-users');
    var recipients = $('#sm-new-conversation #sm-recipients');
    holder.hide();
    // TODO - user id for checkboxid
    // TODO - add user image
    var checkboxid = 'recipient' + Math.round(Math.random() * 1000000);
    var checkbox = '<input type="checkbox" value="' + u.data('id') + '" checked="checked" id="' + checkboxid + '" name="recipient[]">';
    checkbox += '<label for="' + checkboxid + '">' + u.data('name') + '</label>';
    recipients.append(checkbox);

}
