$(document).on('ready', function () {
    // $("#location").customselect();
    $("#location").select2({
        ajax: {
            url: '/search_blog_cat', // Replace with your Laravel route URL
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // Search term from user input
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // console.log(data)
                var options = [];

                // Assuming data.items is an array of objects with "id" and "text" properties
                if (data) {
                    options = data.map(function (item) {
                        return {
                            id: item.id + '-' + item.topic_name,
                            text: item.topic_name
                        };
                    });
                }

                return {
                    results: options
                };
            },

            cache: true,
            error: function (error) {
                // console.error(error);
            }
        },
        placeholder: 'Search...',
    });
    $("#select_category").select2();
    // let slider_slick = $(".regular").slick({
    //     dots: true,
    //     infinite: true,
    //     slidesToShow: 4,
    //     slidesToScroll: 3,
    //     lazyLoad: true
    // });
    // if (slider_slick) {
    //     call_slider_onload();
    // }
    // setTimeout(()=>{
    //     $('.regular').removeClass('d-none')
    // },5000);
    function setSliderSettings() {
        const windowWidth = $(window).width();
        let slidesToShow = 4;
        let slidesToScroll = 3;

        if (windowWidth < 1200) {
            slidesToShow = 3;
            slidesToScroll = 2;
        }

        if (windowWidth < 992) {
            slidesToShow = 2;
            slidesToScroll = 1;
        }

        $(".regular").slick("slickSetOption", "slidesToShow", slidesToShow, true);
        $(".regular").slick("slickSetOption", "slidesToScroll", slidesToScroll, true);
    }

    let slider_slick = $(".regular").slick({
        dots: true,
        infinite: true,
        lazyLoad: true
    });

    if (slider_slick) {
        call_slider_onload();
    }

    // Call the function when the window is resized
    $(window).on("resize", function () {
        setSliderSettings();
    });

    // Set initial slider settings
    setSliderSettings();

});
function call_slider_onload() {
    $('.regular').removeClass('d-none');
}
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
            let html = '<select class="custom-select custom-select-category" id="select_category" name="question_id" aria-label="Select Category"><option selected value="All Categories">All Categories</option>';

            // let ul = '<li class="active">Select Category</li>';
            for (let j = 0; j < obj.data.length; j++) {
                html += '<option value="' + obj.data[j]['question'] + '">' + obj.data[j]['question'] + '</option>';
                // ul = '<li class="active" data-value="' + obj.data[j]['id'] + '">' + obj.data[j]['question'] + '</li>';
            }
            html += '</select>';
            $("#select_category").attr("disabled", false);
            $('#custom-select-category').empty();
            $('#custom-select-category').html(html);
            // $("#select_category").customselect();
            $("#select_category").select2();
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
    let question_slug = $('#select_category').val().replace(/\s/g, "-");
    window.location.replace("/blogs/" + topic_slug + "/" + question_slug);
})

$('#search_blogs').on('keyup', function () {
    let to_search = $(this).val();
    let topic_slug = $('#topic_slug').val();
    let question_slug = $('#question_slug').val();
    if (topic_slug !== undefined && question_slug !== undefined) {
        $.ajax({
            type: 'GET',
            url: '/searchBlogs',
            data: { task: 'searchBlogs', search: to_search, topic_slug: topic_slug, question_slug: question_slug },
            success: function (data) {

                $('#posts_list').empty();
                $('#posts_list').html(data);
            },
            error: function (error) {
                console.log(error)
            }
        })
    } else {
        $.ajax({
            type: 'GET',
            url: '/searchBlogs',
            data: { task: 'searchBlogs', search: to_search },
            success: function (data) {
                $('#posts_list').empty();
                $('#posts_list').html(data);
            },
            error: function (error) {
                console.log(error)
            }
        })
    }
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

function blogger_bio(x, y) {
    $('#blogger_bio_content').empty();
    $('#blogger_bio_content').html(x);
    $('.blogger-bio-title').empty();
    $('.blogger-bio-title').html(y + " Bio");
}


