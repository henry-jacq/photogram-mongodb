// Toast wrapper
// TODO: Show colored toast for priority
function showToast(title, subtitle, message) {
    let tst = new Toast(title, subtitle, message, {});
    tst.show();
    let tstid = tst.id;
    let tstSubtitle = $('#' + tstid).find('.toast-header').find('small');
    tst.showSec(tstSubtitle);
}

// Change like button status
function likeBtn(mainSelector) {
    var likeAudio = $('<audio>', {
        id: 'likePop',
        src: '/assets/sounds/like-pop.mp3'
    });
    if ($('#likePop').length === 0) {
        $('body').append(likeAudio);
    }
    var likeBtnID = mainSelector.find('a').attr('id');
    var likeIconSelector = $('#' + likeBtnID).find('i');
    var placeholder = mainSelector.parent().next().find('.like-count');
    var currentLikes = parseInt(placeholder.text());
    if (likeIconSelector.hasClass('fa-regular fa-heart')) {
        likeAudio[0].play()
        likeIconSelector.removeClass('fa-regular fa-heart');
        likeIconSelector.addClass('fa-solid fa-heart text-danger');
        placeholder.text(currentLikes += 1);
    }
    else {
        if (likeIconSelector.hasClass('fa-solid fa-heart text-danger') && currentLikes != 0) {
            likeIconSelector.removeClass('fa-solid fa-heart text-danger');
            likeIconSelector.addClass('fa-regular fa-heart');
            placeholder.text(currentLikes - + 1);
        } else {
            console.error('Cannot dislike the button');
        }
    }
}

// Toggle like button
function likePost(selector, post_id) {
    if (selector !== undefined && post_id !== undefined) {
        // Toggle like or dislike
        likeBtn(selector);
        $.post('/api/posts/like',
        {
            id: post_id
        }).fail(function () {
            likeBtn(selector);
            console.error("Can't like the post ID: " + post_id);
        });
    }
}

// It will like the post when the user clicks on the like button
$('.btn-like').on('click', function () {
    let thisBtn = $(this);
    let post_id = $(this).attr('data-id');
    likePost(thisBtn, post_id);
});

// It will like the post if the image is double clicked
$(".post-card-image, .carousel").on('dblclick', function () {
    let thisBtn = $(this).next().find('.btn-group').find('.btn-like');
    let post_id = $(this).attr('data-id');
    likePost(thisBtn, post_id);
});

// Shows list of users who liked post in modal
$('.likedby-users').on('click', function () {
    let html = `<div class="container"><ul id="liked-users-list" class="list-group list-group-flush"></ul></div>`;
    let clone = `<li class="list-group-item"><div class="d-flex align-items-center justify-content-between"><div class="me-2"><div class="d-flex align-items-center"><div class="me-2"><img id="user-avatar" class="border rounded-circle" src="" width="40" height="40" loading="lazy"></div><div class="text-break"><h7 id="fullname" class="text-body"></h7><p id="username" class="mb-0 small fw-light"></p></div></div></div><div><a id="link" href="" class="btn btn-primary btn-sm">Show profile</a></div></div></li>`;
    const d = new Dialog('Likes', html);
    d.show('', true);
    const post_id = $(this).attr('data-id');
    const modal = d.clone;
    const target = modal.find('#liked-users-list')
    modal.find('.modal-body').addClass('p-2');
    modal.find('.modal-dialog').addClass('modal-dialog-scrollable');
    modal.find('.modal-footer').remove();

    $.post('/api/posts/users',
        {
            likes: post_id
        }, function (data) {
            if (data.message == true && data.users != null) {
                for (let count = 0; count < data.users.length; count++) {
                    let ud = data.users[count];
                    let username = ud.username;
                    let fullname = ud.fullname;
                    let avatar = ud.avatar;
                    target.append(clone);
                    target.find('#username').text('@' + username);
                    target.find('#username').attr('id', 'username' + count);
                    target.find('#fullname').text(fullname);
                    target.find('#fullname').attr('id', 'fullname' + count);
                    target.find('#user-avatar').attr('src', avatar);
                    target.find('#user-avatar').attr('id', 'user-avatar' + count);
                    target.find('#link').attr('href', '/profile/' + username);
                    target.find('#link').attr('id', 'link' + count);
                }
            } else {
                $('<h5 class="text-center my-5"><i class="bi bi-exclamation-triangle me-2"></i>No liked users found</h5>').prependTo(modal.find('.modal-body').empty())
            }
        });
});

const successAudio = $('<audio>', {
    id: 'successTone',
    src: '/assets/sounds/success.mp3'
});

// Delete post
$('.btn-delete').on('click', function () {
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
                $.ajax({
                    url: '/api/posts/delete',
                    type: 'POST',
                    data: {
                        id: post_id
                    },
                    success: function (data, textStatus) {
                        if (textStatus === "success") {
                            if ($('#masonry-area').length !== 0) {
                                var sl = $('#post-' + post_id);
                                $grid.masonry('remove', sl);
                                $grid.masonry('layout');
                                successAudio[0].play();
                                showToast("Photogram", "Just Now", "Your post was successfully deleted!");
                            } else {
                                successAudio[0].play();
                                showToast("Photogram", "Just Now", "Your post was successfully deleted!");
                                location.reload();
                            }
                        } else {
                            showToast("Photogram", "Just Now", "Can't delete your post!");
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // console.error("Error deleting post:", errorThrown);
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

// Show post image preview in modal
$('.btn-full-preview').on('click', function () {
    var clone_element = $(this).parents('header').next();
    var d = new Dialog('<i class="fa-solid fa-expand me-2 small"></i>Full Preview', '', 'xlarge');
    var modal = d.clone;
    var target = modal.find('.modal-body');

    $(modal).on({
        // Disable right-click on Images
        contextmenu: function () {
            return false;
        },
        // Disable Image Dragging
        dragstart: function (e) {
            e.preventDefault();
        }
    });

    modal.find('.modal-body').addClass('p-0');
    modal.find('.modal-header').addClass('border-0 px-3 py-1');
    modal.find('.modal-title').addClass('fs-6 fw-normal');
    modal.find('.modal-footer').remove();
    d.show('', true);

    if (clone_element.hasClass('carousel')) {
        clone_element.clone().appendTo(target);
        carousel_sel = 'post-image-full-preview';
        target.find('.carousel').attr('id', carousel_sel);
        target.find('.carousel-item > img').removeClass('post-img');
        target.find('.carousel-inner').addClass('rounded');
        target.find('.carousel-control-prev').attr('data-bs-target', '#' + carousel_sel);
        target.find('.carousel-control-next').attr('data-bs-target', '#' + carousel_sel);
    } else if (clone_element.hasClass('post-card-image')) {
        var wrapper = $('<div>').addClass('d-flex align-items-center justify-content-center').html(clone_element.clone());
        wrapper.appendTo(target);
        target.find('.post-card-image').removeClass('post-img');
        target.find('.post-card-image').addClass('img-fluid');
    } else {
        console.error('Cannot preview post image.');
    }
})

// Edit post text
$('.btn-edit-post').on('click', function () {
    if ($('#successTone').length === 0) {
        $('body').append(successAudio);
    }
    const pid = $(this).parent().attr('data-id');
    let el = $(this).parents('header').next().next();
    let ptext = el.find('.post-text').text();
    let actText = el.find('.post-text').html().replace(/<br\s*\/?>/ig, '');
    
    // Remove HTML tags
    var textWithoutHtml = actText.replace(/<[^>]+>/g, '');
    
    const message = `<div class="container my-3"><p class="form-label">Change post text:</p><textarea class="form-control post-text" name="post_text" rows="5" placeholder="Say something..." spellcheck="false">${textWithoutHtml}</textarea><p class="total-chars visually-hidden text-end mt-2"></p></div>`;
    let d = new Dialog('<i class="bi bi-pencil me-2"></i>Edit Your Post', message);
    d.setButtons([
        {
            'name': "Cancel",
            "class": "btn-secondary",
            "onClick": function (event) {
                $(event.data.modal).modal('hide')
            }
        },
        {
            'name': "Update post",
            "class": "btn-prime btn-update-post",
            "onClick": function (event) {
                let txt = $(d.clone).find('.post-text').val();
                let ptxt = txt.replace(/#(\w+)/g, '<a href="/discover/tags/$1">#$1</a>');
                $(d.clone).find('.btn-update-post').prop('disabled', true);

                $.ajax({
                    url: '/api/posts/update',
                    type: 'POST',
                    data: {
                        id: pid,
                        text: ptxt
                    },
                    success: function (data, textStatus) {
                        if (textStatus == "success") {
                            successAudio[0].play();
                            el.find('.post-text').css('white-space', 'pre-line');
                            el.find('.post-text').html(ptxt.replace(/<br\s*\/?>/ig, '<br>'));
                            $grid.masonry('layout');;
                            showToast("Photogram", "Just Now", "Post text changed successfully!");
                        } else {
                            showToast("Photogram", "Just Now", "Can't change the post text!");
                        }
                    },
                    error: function (xhr, status, error) {
                        showToast("Photogram", "Just Now", "Can't change the post text: " + xhr.responseText);
                    }
                });
                $(event.data.modal).modal('hide');
            }
        }
    ]);
    d.show();
    let txtarea = $(d.clone).find('.post-text');
    $(d.clone).find('.btn-update-post').prop('disabled', true);
    $(txtarea).on('input', function () {
        if (txtarea.val() != ptext) {
            $(d.clone).find('.btn-update-post').prop('disabled', false);
        } else {
            $(d.clone).find('.btn-update-post').prop('disabled', true);
        }
        // Character limit on post text
        const maxLength = 240;
        const charCount = $('.total-chars');
        const length = $(this).val().length;
        charCount.removeClass('visually-hidden');

        if (length > maxLength) {
            const truncatedValue = $(this).val().slice(0, maxLength);
            $(this).val(truncatedValue);
        }
        charCount.text(`${$(this).val().length}/${maxLength}`);
    });
});
