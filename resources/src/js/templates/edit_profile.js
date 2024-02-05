// Update profile details
if (window.location.pathname === "/profile/edit") {
    $('.btn-save-data').on('click', function (e) {
        e.preventDefault();
        let form = document.querySelector('.user-form-data')
        const formData = new FormData(form);
        let saveBtn = $('.btn-save-data');
        let spinner = `<div class="spinner-border spinner-border-sm me-1" role="status"><span class="visually-hidden">Loading...</span></div>`;

        saveBtn.attr('disabled', true);
        saveBtn.html(spinner + 'Updating...');

        $.ajax({
            url: '/api/users/profile/update',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.message == 'Updated') {
                    saveBtn.attr('disabled', false);
                    saveBtn.html('Update profile');
                    if ($('.alert.alert-primary.alert-dismissible.fade.show').length === 0) {
                        var successMessage = $('<div>').addClass('alert alert-primary alert-dismissible fade show').html('<i class="bi bi-info-circle me-2"></i>Profile was successfully updated<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>');
                        $(successMessage).insertBefore(form);
                    }
                    document.querySelector('.profile-body').scrollIntoView();
                } else if (response.message == 'Not Updated') {
                    saveBtn.attr('disabled', false);
                    saveBtn.html('Update profile');
                }
            },
            error: function (error) {
                console.error('Error while updating:', error);
            }
        });
    });

    // Remove avatar
    $('#btnRemoveAvatar').on('click', function () {
        const title = 'After removing the avatar, the default user avatar generated from <a href="https://dicebear.com" class="text-decoration-none">dicebear.com</a> will be set as your default avatar.<br><br><b class="fw-semibold">Are you sure to continue?</b>';
        d = new Dialog('<i class="bi bi-trash me-2"></i>Remove Avatar', title);
        d.setButtons([
            {
                'name': "Cancel",
                "class": "btn-secondary",
                "onClick": function (event) {
                    $(event.data.modal).modal('hide');
                }
            },
            {
                'name': 'Yes, remove',
                'class': 'btn-danger',
                "onClick": function (event) {
                    $.post('/api/users/profile/delete',
                        function (data, textSuccess) {
                            if (data.message == true && textSuccess == "success") {
                                location.reload();
                            } else {
                                console.error(data, textSuccess);
                            }
                        });

                    $(event.data.modal).modal('hide')
                }
            }
        ]);
        d.show();
    });
}