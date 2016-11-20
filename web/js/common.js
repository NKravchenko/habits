
$(document).ready(function(){
    $('.dropdown-toggle').dropdown();
    //console.log('test');

    /** общая библиотека для справочников */
    var showFatalError = function () {
        alert('Ошибка выполнения запроса!');
    };

    var doRequest = function (target, type, data, containerId, sender) {

        console.log(data.content);

        $.ajax({
            url: target,
            type: type,
            dataType: 'json',
            data: data,
            cache: false,
            beforeSend: function () {
                sender && sender.attr('disabled', 'disabled');
            }
        }).done(function (data) {
            sender && sender.removeAttr('disabled');
            console.log(data.content);
            if (data.content) {
                if (!data.error) {
                    switch (data.contentName) {
                        case 'inline': /*content for embeding*/
                            var container = $(containerId);
                            container.empty().html(data.content);
                            break;
                        case 'modal': /*modal content*/
                            $('#overlay_content').empty().html(data.content);
                            break;
                        case 'message': /*result message*/
                            alert(data.content);
                    }
                }
            } else {
                alert('Error: ' + data.error);
            }

            return false;
        })
            .fail(function (jqXHR, textStatus, e) {
                sender && sender.removeAttr('disabled');
                showFatalError();
            });
    };


    $(document).on('click', '.js-button-note-save', function () {
            var self = $(this),
                goalId = self.attr('data-id'),
                dayId = self.attr('data-day-id'),
                target = self.attr('data-action'),
                containerId = '.js-day-note-' + dayId,
                note = $("#js-day-note-"+dayId).val(),
                state = $("#js-day-state-"+dayId).val()
                ;

            doRequest(target, 'post', {'goalId': goalId, 'dayId': dayId, 'note':note, 'state':state}, containerId, self);
        }
    );
});

