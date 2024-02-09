$('.btn-search').on('click', function() {
    var queryText = $('.search-input').val();

    $.ajax({
        url: '/api/users/search',
        type: 'GET',
        data: {
            query: queryText
        },
        success: function (data, textStatus) {
            if (data.data.length === 0) {
                console.log("No Users Found!");
            } else {
                var data = data.data;
                var area = $('.list-users');

                var html = `
                <a href="#" class="list-group-item d-flex align-items-center bg-body-tertiary mb-2 rounded border">
                    <img src="${data['avatar']}" alt="Profile ${data['id']}" class="rounded-circle me-3" width="40" height="40">
                    <div class="text-truncate">
                        <h6 class="small">@${data['username']}</h6>
                        <div class="d-grid mx-auto">
                            <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-person-add me-1"></i>Follow
                            </button>
                        </div>
                    </div>
                </a>
                `
                area.append(html);
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            console.error("Error fetching users:", errorThrown);
        }
    });
    
});
