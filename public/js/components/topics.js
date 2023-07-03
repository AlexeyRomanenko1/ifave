document.addEventListener('copy', function (e) {
    e.preventDefault();
});
$('.read-more').on('click', function (e) {
    e.preventDefault();
    $(this).hide();
    $(this).siblings('.full-comment').show();
});
$(document).ready(function () {

    $.ajax({
        type: 'GET',
        url: '/indexonloadRequest',
        data: { task: 'get_questions', topic_name: $('#topic_name').val() },
        success: function (data) {
            let obj = JSON.parse(data);
            // console.group(user_selected_answers);
            let html = '';
            let questions_slider = '';
            if (obj.myfaves.length > 0) {
                for (let k = 0; k < obj.myfaves.length; k++) {
                    html += '<tr><td>' + obj.myfaves[k]['question'] + '</td><td>' + obj.myfaves[k]['answers'] + '</td></tr>';
                    // faves_index = faves_index + 1;
                }
            }
            $('#faves_table_body').empty();
            $('#faves_table_body').html(html);
            $('#faves_table').DataTable();
            $('#display_topic_name').empty();
            // $('#display_topic_name').text(obj.topic_name.toUpperCase())
            $('#display_topic_name').text('Best in ' + obj.topic_name.charAt(0).toUpperCase() + obj.topic_name.slice(1))
            for (let j = 0; j < obj.questions_slider.length; j++) {
                let m = j;
                if (m + 1 < obj.questions_slider.length) {
                    questions_slider += '<div class="inner-content d-flex flex-column me-2"><div class="line mb-2 me-2"><a href="/questions_details/' + obj.questions_slider[j]['id'] + '" class="text-decoration-none">' + obj.questions_slider[j]['question'] + '</a></div><div class="line me-2 mb-2"><a href="/questions_details/' + obj.questions_slider[j + 1]['id'] + '" class="text-decoration-none">' + obj.questions_slider[j + 1]['question'] + '</a></div></div>';
                } else {
                    questions_slider += '<div class="inner-content d-flex flex-column me-2"><div class="line mb-2 me-2"><a href="/questions_details/' + obj.questions_slider[j]['id'] + '" class="text-decoration-none">' + obj.questions_slider[j]['question'] + '</a></div></div>';
                }
                j = j + 1;
            }
            $('#scrollContainer').empty();
            $('#scrollContainer').html(questions_slider);
        },
        error: function (e) {
            console.log(e)
        }
    });
})
$('.hover').hover(function () {
    $(this).css({ "background-color": "#ACACAC" });
}, function () {
    $(this).css({ "background-color": "white" });
})

function questions_modal(x) {
    $.ajax({
        type: 'GET',
        url: '/getquestionanswers',
        data: { task: 'get_question_answers', question_id: x },
        success: function (data) {
            let obj = JSON.parse(data);
            console.log(obj)
            $('.question_modal_heading').empty();
            $('.question_modal_heading').html(obj.question[0].question)
            // let html = '<input type="text" class="form-control mb-1 questions_answer_search" onkeyup="answers_search(this,' + x + ')" placeholder="Search options">';
            let html = '';
            $('.questions_answer_search').attr('data-info', x);
            if (user_selected_answers.includes(x)) {
                for (let j = 0; j < obj.answers.length; j++) {
                    html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.answers[j]['answer_id'] + ')"><b>' + obj.answers[j]['answers'] + ' (Votes: ' + obj.answers[j]['vote_count'] + ')</b></div>';
                }
            } else {
                for (let j = 0; j < obj.answers.length; j++) {
                    html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.answers[j]['answer_id'] + ')"><b>' + obj.answers[j]['answers'] + '</b></div>';
                }
            }
            //html += '</ol>';
            $('.modal-suggestions').empty();
            $('.modal-suggestions').html(html);
        },
        error: function (error) {

        }
    })
}
$('.questions_answer_search').on('keyup', function () {
    let search = $(this).val();
    let id = $('.questions_answer_search').data('info');
    // console.log(id);
    // if (search.length >= 3) {
    $.ajax({
        type: 'GET',
        url: '/searchAnswers',
        data: { task: 'searchAnswers', question_id: id, search: search },
        success: function (data) {
            // console.log(data)
            let obj = JSON.parse(data);
            // console.log(obj)
            // html = '<input type="text" class="form-control mb-1 questions_answer_search" value="' + search + '" onkeyup="answers_search(this,' + id + ')" placeholder="Search options">';
            let html = '';
            if (obj.data.length > 0) {
                if (user_selected_answers.includes(id)) {
                    for (let j = 0; j < obj.data.length; j++) {
                        html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['answer_id'] + ')"><b>' + obj.data[j]['answers'] + ' (Votes: ' + obj.data[j]['vote_count'] + ')</b></div>';
                    }
                } else {
                    for (let j = 0; j < obj.data.length; j++) {
                        html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['answer_id'] + ')"><b>' + obj.data[j]['answers'] + '</b></div>';
                    }
                }
            } else {
                html += '<div class="hover p-1" ><b>No answer found</b></div>';
            }
            // html += '</ol>';
            $('.modal-suggestions').empty();
            $('.modal-suggestions').html(html);
        },
        error: function (error) {

        }
    })
})
// functions to change boats suggestions div background
function highlight_sug(x) {
    $(x).removeClass('bg-light')
    $(x).addClass('bg-dark text-white')
}
function nohighlight_sug(x) {
    $(x).removeClass('bg-dark text-white')
    // $(x).removeClass('text-whit')
    $(x).addClass('bg-light')
}

function add_vote(x) {
    $.ajax({
        type: 'POST',
        url: '/entervote',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), answer_id: x },
        success: function (data) {
            // $("#msg").html(data.msg);
            // console.log(data)
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data);
                $('#staticBackdrop').addClass('show');
                $('#staticBackdrop').fadeIn();
                //$("exampleModal1").attr("role","dialog");
                // location.reload(true)
            } else {
                toastr.error(obj.data)
            }
        }
    });
}
$('#search_question_topics').on('keyup', function () {
    let to_search = $(this).val();
    let id = $('#hidden_question_id').val();
    $.ajax({
        type: 'GET',
        url: '/searchQuestionsTopics',
        data: { task: 'searchQuestionsTopics', search: to_search, id: id },
        success: function (data) {
            // console.log(data)
            let obj = JSON.parse(data);
            console.log(obj)
            // html = '<input type="text" class="form-control mb-1 questions_answer_search" value="' + search + '" onkeyup="answers_search(this,' + id + ')" placeholder="Search options">';
            let html = '';
            // if (to_search.length > 0) {
            if (obj.data.length > 0) {
                // html += '<div class="p-2"><b>Questions</b></div>';
                if ($('#hidden_to_be').val() == 1) {
                    for (let j = 0; j < obj.data.length; j++) {
                        html += ' <div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['id'] + ')"><b>' + obj.data[j]['answers'] + '</b></div>';
                    }
                } else {
                    for (let j = 0; j < obj.data.length; j++) {
                        html += ' <div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['id'] + ')"><b>' + obj.data[j]['answers'] + ' (votes: ' + obj.data[j]['vote_count'] + ')</b></div>';
                    }
                }
            } else {
                html += '';
            }
            // if (obj.topics.length > 0) {
            //     html += '<div class="p-2"><b>Topics</b></div>';
            //     for (let j = 0; j < obj.topics.length; j++) {
            //         html += '<div class="hover p-1 m-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)"">' + obj.topics[j]['topic_name'] + '</div>';
            //     }
            // }
            $('.set_suggestion_height').removeClass('d-none');
            $('.set_suggestion_height').empty();
            $('.set_suggestion_height').html(html);
            // } else {
            //     $('.set_suggestion_height').addClass('d-none');
            //     $('.set_suggestion_height').empty();
            // }
        },
        error: function (error) {

        }
    })
})


$('#search_questions').on('keyup', function () {
    let to_search = $(this).val();
    let id = $('#topic_id').val();
    $.ajax({
        type: 'GET',
        url: '/searchQuestions',
        data: { task: 'searchQuestions', search: to_search, id: id },
        success: function (data) {
            $('#display_questions').empty();
            $('#display_questions').html(data);
        },
        error: function (error) {
            console.log(error)
        }
    })
})

function copy_url(x) {
    navigator.clipboard.writeText(x);
    toastr.success('Link copied!')
}

function delete_answer(x, y) {
    $.ajax({
        type: 'POST',
        url: '/delete_vote',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), user_answer_id: x, answer_id: y },
        success: function (data) {
            // $("#msg").html(data.msg);
            // console.log(data)
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data)
                location.reload(true)
            } else {
                toastr.error(obj.data)
            }
        }
    });
}

function upvote_count(x, y) {
    $.ajax({
        type: 'POST',
        url: '/upvote_comment',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), comment_id: x, upvote: y },
        success: function (data) {
            // $("#msg").html(data.msg);
            // console.log(data)
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data)
                location.reload(true)
            } else {
                toastr.error(obj.data)
            }
        },
        error: function (e) {
            console.log(e)
        }
    });
}

function downvote_count(x, y) {
    $.ajax({
        type: 'POST',
        url: '/downvote_comment',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), comment_id: x, upvote: y },
        success: function (data) {
            // $("#msg").html(data.msg);
            // console.log(data)
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data)
                location.reload(true)
            } else {
                toastr.error(obj.data)
            }
        },
        error: function (e) {
            console.log(e)
        }
    });
}
$('.skip').on('click', function () {
    location.reload(true)
})


// function to uncover votes 
function un_cover(x) {
    $.ajax({
        type: 'GET',
        url: '/uncover_answers',
        data: { task: 'uncover_answers', question_id: x },
        success: function (data) {
            // console.log(data);
            let html = '';
            let obj = JSON.parse(data);
            console.log(obj)
            for (let j = 0; j < obj.data.length; j++) {
                html += '<div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['id'] + ')"><b>' + obj.data[j]['answers'] + ' (Votes: ' + obj.data[j]['vote_count'] + ')</b></div>';
            }
            $('.set_suggestion_height').empty();
            $('.set_suggestion_height').html(html);
        },
        error: function (e) {
            console.log(e)
        }
    })
}

function share_url(url) {
    let text = "Check this question on ifave";
    $("#facebook_share").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURI(url));
    $("#twitter_share").attr("href", "https://twitter.com/intent/tweet?text=" + encodeURI(text) + "&url=" + encodeURI(url));
}

function top_comments_modal(x) {
    $.ajax({
        type: 'GET',
        url: '/get_comments_list',
        data: { task: 'comments_list', topic_id: x },
        success: function (data) {
            // console.log(data);
            let html = '<ol>';
            let obj = JSON.parse(data);
            for (let j = 0; j < obj.data.length; j++) {
                html += '<li>' + obj.data[j]['name'] + ' (' + obj.data[j]['upvotes'] + ' upvotes)</li>';
            }
            html += '</ol>';
            $('#top_comments_modal_body').empty();
            $('#top_comments_modal_body').html(html);

        },
        error: function (e) {
            console.log(e)
        }
    })
}

function redirect_url(x) {
    window.location.replace("/questions_details/" + x);
}

function scrollRight() {
    const scrollContainer = document.getElementById("scrollContainer");
    scrollContainer.scrollBy({ left: 100, behavior: "smooth" });
}

function scrollLeftcont() {
    const scrollContainer = document.getElementById("scrollContainer");
    scrollContainer.scrollBy({ left: -100, behavior: "smooth" });
}

function generate_embeded_code(url, questionName) {
    // Customize the embedded code template with the question link and name
    var embeddedCode = '<a href="' + url + '">' + questionName + '</a>';
    navigator.clipboard.writeText(embeddedCode);
    toastr.success('Embeded code copied!')
    // Display or use the generated embed code as needed
    // console.log(embeddedCode);
}