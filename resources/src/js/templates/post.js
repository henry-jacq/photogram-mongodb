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

// Download post image in zip format.
$('.btn-download').on('click', function () {
    if (this.hasAttribute('href')) {
        return;
    }

    var post_id = $(this).parent().data('id');

    $.ajax({
        url: '/api/posts/download',
        method: 'GET',
        data: { id: post_id },
        xhrFields: {
            responseType: 'arraybuffer'
        },
        success: function (data, textStatus, xhr) {
            var contentType = xhr.getResponseHeader('Content-Type');
            var blob = new Blob([data], { type: contentType });
            var tempURL = window.URL.createObjectURL(blob);
            $(this).attr('href', tempURL);
            var contentDisposition = xhr.getResponseHeader('Content-Disposition');
            var filename = contentDisposition.match(/filename="?([^"]+)"?/);
            filename = filename ? filename[1] : 'images.zip';
            $(this).attr('download', filename);
            $(this).get(0).click();
            window.URL.revokeObjectURL(tempURL);
            $(this).removeAttr('href');
        }.bind(this),
        error: function (error) {
            showToast("Photogram", "Just Now", "Cannot download the post!");
        }
    });
});

// Copy the post link
$('.btn-copy-link').on('click', function () {
    let successAudio = $('<audio>', {
        id: 'successTone',
        src: '/assets/sounds/success.mp3'
    });
    if ($('#successTone').length === 0) {
        $('body').append(successAudio);
    }
    let carousel = $(this).parents('header').next();
    let activeItem = carousel.find('.active');
    let image = activeItem.find('img').attr('src');
    let textToCopy = window.location.origin + (this.getAttribute('value') != undefined ? $(this).attr('value') : image);

    if (navigator.clipboard) {
        if (navigator.clipboard.writeText(textToCopy)) {
            successAudio[0].play();
            showToast("Photogram", "Just Now", "Copied the post link to the clipboard!");
        }
    } else {
        console.error("Can't copy the post link!");
        showToast("Photogram", "Just Now", "Can't copy the post link to the clipboard!");
    }
});
