// Toast wrapper
// TODO: Show colored toast for priority
function showToast(title, subtitle, message) {
    let tst = new Toast(title, subtitle, message, {});
    tst.show();
    let tstid = tst.id;
    let tstSubtitle = $('#' + tstid).find('.toast-header').find('small');
    tst.showSec(tstSubtitle);
}

// Delete post
$('.btn-delete').on('click', function () {
    var successAudio = $('<audio>', {
        id: 'successTone',
        src: '/assets/sounds/success.mp3'
    });
    if ($('#successTone').length === 0) {
        $('body').append(successAudio);
    }
    let message = `<p>Are you sure you want to delete this post?</p><p>This action cannot be undone.</p>`;
    let post_id = $(this).parent().attr('data-id');
    d = new Dialog('<i class="bi bi-trash me-2"></i>Delete Post', message);
    d.setButtons([
        {
            'name': "Cancel",
            "class": "btn-secondary",
            "onClick": function (event) {
                $(event.data.modal).modal('hide');
            }
        },
        {
            'name': "Delete post",
            "class": "btn-danger",
            "onClick": function (event) {
                $.post('/api/posts/delete',
                    {
                        id: post_id
                    }, function (data, textSuccess) {
                        if (textSuccess == "success") {
                            sl = document.querySelector(`#post-${post_id}`);
                            masonry.remove(sl);
                            masonry.layout();
                            successAudio[0].play();
                            showToast("Photogram", "Just Now", "Your post was successfully deleted!");
                        } else {
                            showToast("Photogram", "Just Now", "Can't delete your post!");
                        }
                    });

                $(event.data.modal).modal('hide')
            }
        }
    ]);
    d.show();
});
