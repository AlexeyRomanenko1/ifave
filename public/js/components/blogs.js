$(document).on('ready', function () {
    $("#location").customselect();
    $(".regular").slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 3,
        lazyLoad: true
    });
    $('.regular').removeClass('d-none');
});

$('#location').on('change', function () {
    let topic_id = $(this).val();
    topic_id = topic_id.split("-");
    $.ajax({
        type: 'POST',
        url: '/get_categories_onchange',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), topic_id: topic_id[0] },
        success: function (data) {
            // $("#msg").html(data.msg);
            let obj = JSON.parse(data);
            // console.log(obj)
            let html = '';
            for (let j = 0; j < obj.data.length; j++) {
                let question_slug = obj.data[j]['question'].replace(/\s/g, "-");
                html += '<option value="' + question_slug + '">' + obj.data[j]['question'] + '</option>';
            }
            $('#select_category').empty();
            $("#select_category").attr("disabled", false);
            $('#select_category').html(html);
            // $("#select_category").customselect();
        },
        error: function (e) {
            console.log(e)
        }
    });
})


$('.filter_blogs').on('click', function (e) {
    e.preventDefault();
    let topic_name = $('#location').val();
    topic_name = topic_name.split("-");
    let topic_slug = topic_name[1].replace(/\s/g, "-");
    let question_slug = $('#select_category').val();
    window.location.replace("/blog/" + topic_slug + "/" + question_slug);
})

function blogger_route(x) {
    let user_name = x.replace(/\s/g, "-");
    let topic_slug = $('#topic_slug').val();
    let question_slug = $('#question_slug').val();
    if (topic_slug !== undefined && question_slug !== undefined) {
        window.location.replace("/blogger/" + user_name + "/" + topic_slug + "/" + question_slug);
    } else {
        window.location.replace("/blogger/" + user_name);
    }
}

function blogger_bio(x,y){
    $('#blogger_bio_content').empty();
    $('#blogger_bio_content').html(x);
    $('.blogger-bio-title').empty();
    $('.blogger-bio-title').html(y+" Bio");
}