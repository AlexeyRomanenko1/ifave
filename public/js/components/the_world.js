$(document).ready(function () {
    //load questions
    let to_search = '';
    let id = $('#topic_id').val();
    let topicName = $('#topicName').val();
    $.ajax({
        type: 'GET',
        url: '/searchQuestions',
        data: { task: 'searchQuestions', search: to_search, id: id, topicName: topicName },
        success: function (data) {
            // console.log(data)
            //  var result = JSON.parse(data);
            $('#onpageload-loader').addClass('d-none');
            $('#display_questions').html(data.searchResults);
            $('#pagination_links').html(data.paginationLinks);
        },
        error: function (error) {
            console.log(error)
        }
    })
    $.ajax({
        type: 'GET',
        url: '/indexonloadRequest',
        data: { task: 'get_questions' },
        success: function (data) {
            let obj = JSON.parse(data);
            // console.log(obj.top_comments)
            let html = '';
            // let questions_slider = '';
            if (obj.personality != '' || obj.personality != null) {
                $('.personality_headding').html('My personality portrait based on my faves');
                $('.personality_content').html(obj.personality)
            }
            if (obj.myfaves.length > 0) {
                for (let k = 0; k < obj.myfaves.length; k++) {
                    html += '<tr><td class="fs-5"><b>' + obj.myfaves[k]['answers'] + '</b></td><td>' + obj.myfaves[k]['question'] + '</td><td>' + obj.myfaves[k]['topic_name'] + '</td></tr>';
                    // faves_index = faves_index + 1;
                }
            }
            $('#faves_table_body').empty();
            $('#faves_table_body').html(html);
            $('#faves_table').DataTable({
                "lengthMenu": [50, 100],
                searching: true,
            });
            $('#display_topic_name').empty()
            $('#display_topic_name').html('<img class="mb-3" src="/images/question_images/ifave_page.jpg" height="50px" width="50px" alt=""> Best in ' + obj.topic_name.charAt(0).toUpperCase() + obj.topic_name.slice(1))
            // for (let j = 0; j < obj.questions_slider.length; j++) {
            //     let m = j;
            //     if (m + 1 < obj.questions_slider.length) {
            //         questions_slider += '<div class="inner-content d-flex flex-column me-2"><div class="line mb-2 me-2"><a href="/questions_details/' + obj.questions_slider[j]['id'] + '" class="text-decoration-none">' + obj.questions_slider[j]['question'] + '</a></div><div class="line me-2 mb-2"><a href="/questions_details/' + obj.questions_slider[j + 1]['id'] + '" class="text-decoration-none">' + obj.questions_slider[j + 1]['question'] + '</a></div></div>';
            //     } else {
            //         questions_slider += '<div class="inner-content d-flex flex-column me-2"><div class="line mb-2 me-2"><a href="/questions_details/' + obj.questions_slider[j]['id'] + '" class="text-decoration-none">' + obj.questions_slider[j]['question'] + '</a></div></div>';
            //     }
            //     j = j + 1;
            // }
            // $('#scrollContainer').empty();
            // $('#scrollContainer').html(questions_slider);
            $('meta[name=description]').attr('content', obj.meta_description);
            $('meta[name=keywords]').attr('content',obj.keywords);
            $("#meta-property").attr('content', obj.meta_description);
        },
        error: function (e) {
            console.log(e);
        }

    });

})