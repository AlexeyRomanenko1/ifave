$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: '/get_topics',
        data: { task: 'get_topics' },
        success: function (data) {
            // console.log(data);
            let html = '';
            let obj = JSON.parse(data);
            // console.log(obj)
            for (let j = 0; j < obj.data.length; j++) {
                if (obj.data[j]['topic_name'] != 'The World') {
                    html += '<a rel="nofollow" href="/location/' + obj.data[j]['topic_name'].replace(/ /g, "-") + '" class="hover p-2 bg-light nav-link link-dark" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"><b>' + obj.data[j]['topic_name'] + '</b></a>';
                } else {
                    html += '<a href="/location/' + obj.data[j]['topic_name'].replace(/ /g, "-") + '" class="hover p-2 bg-light nav-link link-dark" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"><b>' + obj.data[j]['topic_name'] + '</b></a>';
                }
            }
            $('.set_suggestion_height_topics').empty();
            $('.set_suggestion_height_topics').html(html);
        },
        error: function (e) {
            console.log(e)
        }
    })
})

$('#search_topics').on('keyup', function () {
    let tosearch_topic = $(this).val();
    $.ajax({
        type: 'GET',
        url: '/search_topics',
        data: { task: 'search_topics', to_search: tosearch_topic },
        success: function (data) {
            // console.log(data);
            let html = '';
            let obj = JSON.parse(data);
            // console.log(obj)
            for (let j = 0; j < obj.data.length; j++) {
                if (obj.data[j]['topic_name'] != 'The World') {
                    html += '<a rel="nofollow" href="/location/' + obj.data[j]['topic_name'].replace(/ /g, "-") + '" class="hover p-2 bg-light nav-link link-dark" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"><b>' + obj.data[j]['topic_name'] + '</b></a>';
                } else {
                    html += '<a href="/location/' + obj.data[j]['topic_name'].replace(/ /g, "-") + '" class="hover p-2 bg-light nav-link link-dark" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"><b>' + obj.data[j]['topic_name'] + '</b></a>';
                }
            }
            $('.set_suggestion_height_topics').empty();
            $('.set_suggestion_height_topics').html(html);
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