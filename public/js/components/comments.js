function top_comments_modal_body_for_comments(){
    $.ajax({
        type: 'GET',
        url: '/get_comments_list_all',
        data: { task: 'comments_list'},
        success: function (data) {
            // console.log(data);
            let html = '<ol>';
            let obj = JSON.parse(data);
            for (let j = 0; j < obj.data.length; j++) {
                html += '<li><a class="link-secondary" ref="nofollow" href="/comments/'+ obj.data[j]['name'].replace(/ /g, '-') +'">' + obj.data[j]['name'] + ' (' + obj.data[j]['upvotes'] + ' upvotes)</a></li>';
            }
            html += '</ol>';
            $('#top_comments_modal_body_for_comments').empty();
            $('#top_comments_modal_body_for_comments').html(html);

        },
        error: function (e) {
            console.log(e)
        }
    })
}
$('#search_users_comments_on_comments_page').on('keyup', function (e) {
    $.ajax({
        type: 'GET',
        url: '/get_comments_list_by_username_all',
        data: {task:'search_users_comments', user_name: $(this).val()},
        success: function (data) {
            // console.log(data);
            let html = '<ol>';
            let obj = JSON.parse(data);
            for (let j = 0; j < obj.data.length; j++) {
                html += '<li><a class="link-secondary" ref="nofollow" href="/comments/'+ obj.data[j]['name'].replace(/ /g, '-') +'">' + obj.data[j]['name'] + ' (' + obj.data[j]['upvotes'] + ' upvotes)</a></li>';
            }
            html += '</ol>';
            $('#top_comments_modal_body_for_comments').empty();
            $('#top_comments_modal_body_for_comments').html(html);

        },
        error: function (e) {
            console.log(e)
        }
    })
})