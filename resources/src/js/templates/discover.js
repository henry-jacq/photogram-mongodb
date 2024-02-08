$('.btn-search').on('click', function() {
    var contentArea = $('.content-area');

    var queryText = $('.search-input').val();
    contentArea.append("<p>" + queryText + "</p>")

    $.ajax({
        url: '/api/users/search',
        type: 'GET',
        data: {
            query: queryText
        },
        success: function (data, textStatus) {
            console.log(data);
            console.log(data.data);
            console.log(textStatus);
        },
        error: function (xhr, textStatus, errorThrown) {
            console.error("Error fetching users:", errorThrown);
        }
    });
    
});
