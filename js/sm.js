
/* Scroll to bottom of user messages */
var messageWindow = document.getElementById("sm-conversation-messages");
if(messageWindow) {
    messageWindow.scrollTop = messageWindow.scrollHeight;
}


/* ajax search users */
$(document).ready( function() {
    $('#sm-new-conversation #users').hide();
    $('#sm-new-conversation #searchname').keyup(function(e) {
        $('#sm-new-conversation #users .user').off('click');
        $.ajax({
            'url': M.cfg.wwwroot + '/local/simple_message/ajax_findusers.php',
            'data': 'sm_name=' + e.currentTarget.value,
            'method': 'get',
            'dataType': 'html',
            'success': function(data, textStatus, jqXHR) {
                if (data.length > 0) {
                    $('#sm-new-conversation #users').html(data);
                    $('#sm-new-conversation #users .user').click(local_sm_user_select);
                    $('#sm-new-conversation #users').show();
                }
            }
        });

    });

});

function local_sm_user_select(e) {
    var u = $(this);
    var holder = u.parent('#sm-new-conversation #users');
    var recipients = $('#sm-new-conversation #recipients');
    holder.hide();
    var checkboxid = 'recipient' + Math.round(Math.random() * 1000000);
    var checkbox = '<input type="checkbox" value="' + u.data('id') + '" checked="checked" id="' + checkboxid + '">';
    checkbox += '<label for="' + checkboxid + '">' + u.data('name') + '</label>';
    recipients.append(checkbox);

}