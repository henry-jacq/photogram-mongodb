// Comment on post
let comment_frame = `<div class="container"><ul id="comment-list" class="list-group list-group-flush my-3"></ul></div>`;

let comment_clone = `<li id="comment" class="list-group-item border-0"><div class="d-flex"><div class="mt-1 me-1"><div class="me-2"><img id="commenter-avatar" class="border rounded-circle" src="" width="46" height="46" loading="lazy"/></div></div><div class="bg-body-tertiary border px-3 py-2 rounded w-100"><div class="d-flex justify-content-between mb-1"><h6 class="fw-semibold mb-0"><a id="commenter-name" class="text-body" href=""></a></h6><small id="commented-time" class="ms-2">Now</small></div><p id="commenter-text" class="mb-2"></p></div></div></li>`;

let comment_send_form = `<div class="me-2"><img id="user-comment-avatar" class="border rounded-circle" src="" width="40" height="40"></div><form class="text-body position-relative w-100"><textarea id="add-comment" class="form-control pe-5" rows="1" maxlength="43" placeholder="Add a comment..."></textarea><button class="btn btn-comment-send focus-ring focus-ring-prime border-0 position-absolute top-50 end-0 translate-middle-y" type="button" disabled><i class="bi bi-send-fill text-prime"></i></button></form>`;

var deleteBtn = `<a class="btn-delete-comment mt-2 small text-danger" role="button">Delete</a>`;

$('.btn-comment').on('click', function () {
    var d = new Dialog('Comments', comment_frame);
    d.show('', true);
    var modal = d.clone;
    var modal_footer = modal.find('.modal-footer');
    modal.find('.modal-body').addClass('p-2');
    modal_footer.addClass('flex-nowrap');
    modal_footer.empty().html(comment_send_form);
    modal_footer.find('#add-comment').css('resize', 'none');
    modal.find('.modal-dialog').addClass('modal-dialog-scrollable');

    const post_id = $(this).attr('data-id');
    const target = modal.find('#comment-list');

    // Display comments on modal
    $.post('/api/posts/users',
        {
            comments: post_id
        }, function (data, textSuccess) {
            if (textSuccess == 'success') {
                const sess_user_name = data.owner.username;
                const sess_user_avatar = data.owner.avatar;
                modal_footer.find('#user-comment-avatar').attr('src', sess_user_avatar);
                if (data.message == true && data.comments.users != false) {
                    modal.find('.modal-title').text(`Comments (${data.comments.users.length})`);
                    for (let count = 0; count < data.comments.users.length; count++) {
                        let ud = data.comments.users[count];
                        let comment_id = ud.comment_id;
                        let comment_body = ud.comment;
                        let timestamp = ud.timestamp;
                        let username = ud.username;
                        let fullname = ud.fullname;
                        let avatar = ud.avatar;
                        target.append(comment_clone);
                        target.find('#commenter-avatar').attr('src', avatar);
                        target.find('#commenter-avatar').attr('id', Math.random() * 1000 + count);
                        target.find('#commenter-name').text(fullname);
                        target.find('#commenter-name').attr('href', '/profile/' + username);
                        target.find('#commenter-name').attr('id', Math.random() * 1000 + count);
                        target.find('#commented-time').empty().text(timestamp);
                        target.find('#commented-time').attr('id', Math.random() * 1000 + count);
                        target.find('#comment').attr('id', Math.random() * 1000 + count);
                        target.find('#commenter-text').empty().text(comment_body);
                        if (sess_user_name == username) {
                            $(deleteBtn).insertAfter(target.find('#commenter-text'));
                            let btnDeleteComment = $(target.find('#commenter-text')).next();
                            btnDeleteComment.attr('data-cid', comment_id);
                            btnDeleteComment.attr('data-pid', post_id);
                        }
                        target.find('#commenter-text').attr('id', Math.random() * 1000 + count);
                    }
                } else {
                    $('<h5 class="comment-not-found text-center my-5"><i class="bi bi-exclamation-triangle me-2"></i>No comments found</h5>').appendTo(target);
                }
            } else {
                console.error("Cannot fetch comments for post ID: " + post_id);
            }
        });

    // Handle form
    $(modal_footer.find('#add-comment')).on('input', function () {
        let commentText = $(this).val();
        if (commentText != '' && commentText.length < 43) {
            $('.btn-comment-send').removeAttr('disabled');
        } else {
            $('.btn-comment-send').attr('disabled', true);
        }
    });

    // Send a new comment
    $('.btn-comment-send').on('click', function () {
        let commentText = $(modal_footer.find('#add-comment')).val();
        var messageAudio = $('<audio>', {
            id: 'messageTone',
            src: '/assets/sounds/message-tone.mp3'
        });
        if ($('#messageTone').length === 0) {
            $('body').append(messageAudio);
        }
        $.post('/api/posts/comments/create',
            {
                pid: post_id,
                comment: commentText
            }, function (data) {
                if (data.message == true) {
                    if (target.find('.comment-not-found')) {
                        target.find('.comment-not-found').remove();
                    }
                    target.prepend(comment_clone);
                    messageAudio[0].play();
                    target.find('#commenter-avatar').attr('src', data.avatar);
                    target.find('#commenter-avatar').attr('id', Math.random() * 1000)
                    target.find('#commenter-name').text(data.fullname);
                    target.find('#commenter-name').attr('href', '/profile/' + data.username);
                    target.find('#commenter-name').attr('id', Math.random() * 1000)
                    modal_footer.find('#add-comment').val('');
                    target.find('#comment').attr('id', Math.random() * 1000)
                    target.find('#commenter-text').empty().text(commentText);
                    $(deleteBtn).insertAfter(target.find('#commenter-text'));
                    let btnDeleteComment = $(target.find('#commenter-text')).next();
                    btnDeleteComment.attr('data-cid', data.comment_id)
                    btnDeleteComment.attr('data-pid', post_id)
                    target.find('#commenter-text').attr('id', Math.random() * 1000);
                    $('.btn-comment-send').attr('disabled', true);
                }
            });
    });

    // Delete comment
    $(target).on('click', '.btn-delete-comment', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var successAudio = $('<audio>', {
            id: 'successTone',
            src: '/assets/sounds/success.mp3'
        });
        if ($('#successTone').length === 0) {
            $('body').append(successAudio);
        }
        let cid = $(this).attr('data-cid');
        let pid = $(this).attr('data-pid');
        let comment_box = $(this).parents('.list-group-item');

        $.post('/api/posts/comments/delete',
            {
                comment_id: cid,
                post_id: pid
            }, function (data) {
                if (data.message == true) {
                    successAudio[0].play();
                    comment_box.fadeOut(300, function () {
                        comment_box.remove();
                    });
                }
            });
    });
});