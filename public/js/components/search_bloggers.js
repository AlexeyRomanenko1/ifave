$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: '/get_blogger',
        data: { task: 'get_blogger' },
        success: function (data) {
            let html = '';
            let obj = JSON.parse(data);
            // console.log(obj)
            for (let j = 0; j < obj.data.length; j++) {
                let user_slug = obj.data[j]['username'].replace(/\s+/g, '-');
                html += '<a href="/blogger/' + user_slug + '" class="hover p-2 bg-light nav-link link-dark" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"><b>' + obj.data[j]['username'] + '</b></a>';
            } 
            $('.set_suggestion_height_bloggers').empty();
            $('.set_suggestion_height_bloggers').html(html);
        },
        error: function (e) {
            console.log(e)
        }
    })
})

$('#tosearch_blogger').on('keyup', function () {
    let tosearch_blogger = $(this).val();
    $.ajax({
        type: 'GET',
        url: '/search_bloggers',
        data: { task: 'search_bloggers', to_search: tosearch_blogger },
        success: function (data) {
            //  console.log(data);
            let html = '';
            let obj = JSON.parse(data);
             console.log(obj)
            for (let j = 0; j < obj.data.length; j++) {
                let user_slug = obj.data[j]['username'].replace(/\s+/g, '-');
                html += '<a href="/blogger/' + user_slug + '" class="hover p-2 bg-light nav-link link-dark" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"><b>' + obj.data[j]['username'] + '</b></a>';
            }
            $('.set_suggestion_height_bloggers').empty();
            $('.set_suggestion_height_bloggers').html(html);
        },
        error: function (e) {
            console.log(e)
        }
    })
})

$('.underline').hover(
    function () {
        $(this).addClass('hover');
    },
    function () {
        $(this).removeClass('hover');
    }
);
$('.zoom-block').hover(
    function () {
        $(this).addClass('hover');
    },
    function () {
        $(this).removeClass('hover');
    }
);