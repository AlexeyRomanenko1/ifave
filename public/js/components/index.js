// document.addEventListener('copy', function (e) {
//     e.preventDefault();
// });
var currentPage = 1;
var questionsPerPage = 40;
var allQuestions = []; // Variable to store all the questions retrieved from the API
$('.read-more').on('click', function (e) {
    e.preventDefault();
    $(this).hide();
    var halfCommentElement = $(this).closest('.comment-content').find('.half-comment');
    // $(this).siblings('.full-comment').show();
    var fullCommentElement = $(this).closest('.comment-content').find('.full-comment');
    halfCommentElement.hide();
    fullCommentElement.show();
});


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
                    html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.answers[j]['answer_id'] + ')"><b>' + obj.answers[j]['answers'] + ' (Faves: ' + obj.answers[j]['vote_count'] + ')</b></div>';
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
                        html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['answer_id'] + ')"><b>' + obj.data[j]['answers'] + ' (Faves: ' + obj.data[j]['vote_count'] + ')</b></div>';
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
            } else {
                toastr.warning(obj.data)
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
                        html += ' <div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['id'] + ')"><b>' + obj.data[j]['answers'] + ' (Faves: ' + obj.data[j]['vote_count'] + ')</b></div>';
                    }
                }
            } else {
                html += '';
            }
            $('.set_suggestion_height').removeClass('d-none');
            $('.set_suggestion_height').empty();
            $('.set_suggestion_height').html(html);
        },
        error: function (error) {

        }
    })
})


$('#search_questions').on('keyup', function () {
    let to_search = $(this).val();
    let id = $('#topic_id').val();
    let topicName = $('#topicName').val();
    $.ajax({
        type: 'GET',
        url: '/searchQuestions',
        data: { task: 'searchQuestions', search: to_search, id: id, topicName: topicName },
        success: function (data) {
            // console.log(data)
            //  var result = JSON.parse(data);
            $('#display_questions').html(data.searchResults);
            $('#pagination_links').html(data.paginationLinks);
        },
        error: function (error) {
            console.log(error)
        }
    })
})


$('#search_categories').on('keyup', function () {
    let to_search = $(this).val();
    let id = $('#topic_id').val();
    $.ajax({
        type: 'GET',
        url: '/searchcategories',
        data: { task: 'searchcategories', search: to_search, id: id },
        success: function (data) {
            let html = '';
            let obj = JSON.parse(data);
            if (obj.data.length > 0) {
                for (let j = 0; j < obj.data.length; j++) {
                    html += '<p><b>' + obj.data[j]['question_category'] + '</b></p>'
                    let questions = obj.data[j]['questions'].split('break_statement');
                    html += '<div class="container"><ul>';
                    for (var i = 0; i < questions.length; i++) {
                        // Trim any leading/trailing spaces from each value
                        let trimmedValue = questions[i].trim();

                        // Perform any desired operations on each value here
                        // For demonstration purposes, we'll just log each value to the console
                        html += '<li> <h6><a href="category/' + $('#topicName').val().replace(/ /g, "-") + '/' + trimmedValue.replace(/ /g, "-") + '">' + trimmedValue + '</a></h6></li>';
                    }
                    html += '</ul></div>';
                    // html += '<div class="col-md-6"><ul><li> <h6><a href="category/' + $('#topicName').val().replace(/ /g, "-") + '/' + obj.data[j]['question'].replace(/ /g, "-") + '">' + obj.data[j]['question'] + '</a></h6></li></ul></div>';
                }
            }
            $('#on_search_category').empty();
            $('#on_search_category').html(html);
        },
        error: function (error) {
            console.log(error)
        }
    })
})
// Add a new event handler for pagination link clicks
$(document).on('click', '#pagination_links a', function (e) {
    e.preventDefault(); // Prevent the default behavior of the link click

    // Extract the href attribute from the clicked pagination link
    var pageUrl = $(this).attr('href');

    // Send an AJAX request to the extracted URL
    $.ajax({
        type: 'GET',
        url: pageUrl, // Use the pagination link URL
        data: { task: 'searchQuestions', search: $('#search_questions').val(), id: $('#topic_id').val(), topicName: $('#topicName').val() },
        success: function (data) {
            // Update the search results and pagination links
            $('#display_questions').empty();
            $('#display_questions').html(data.searchResults);
            $('#pagination_links').html(data.paginationLinks); // Update the pagination container
            $("html, body").animate({ scrollTop: 0 }, "slow");
        },
        error: function (error) {
            console.log(error);
        }
    });
});

$('#open_search_category_modal').on('click', function () {
    let to_search = $(this).val();
    let id = $('#topic_id').val();
    $.ajax({
        type: 'GET',
        url: '/searchcategories',
        data: { task: 'searchcategories', search: to_search, id: id },
        success: function (data) {
            let html = '';
            let obj = JSON.parse(data);
            if (obj.data.length > 0) {
                for (let j = 0; j < obj.data.length; j++) {
                    html += '<p><b>' + obj.data[j]['question_category'] + '</b></p>'
                    let questions = obj.data[j]['questions'].split('break_statement');
                    html += '<div class="container"><ul>';
                    for (var i = 0; i < questions.length; i++) {
                        // Trim any leading/trailing spaces from each value
                        let trimmedValue = questions[i].trim();

                        // Perform any desired operations on each value here
                        // For demonstration purposes, we'll just log each value to the console
                        html += '<li> <h6><a href="category/' + $('#topicName').val().replace(/ /g, "-") + '/' + trimmedValue.replace(/ /g, "-") + '">' + trimmedValue + '</a></h6></li>';
                    }
                    html += '</ul></div>';
                    // html += '<div class="col-md-6"><ul><li> <h6><a href="category/' + $('#topicName').val().replace(/ /g, "-") + '/' + obj.data[j]['question'].replace(/ /g, "-") + '">' + obj.data[j]['question'] + '</a></h6></li></ul></div>';
                }
            }
            $('#on_search_category').empty();
            $('#on_search_category').html(html);
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
            // console.log(obj)
            let counter = 1;
            for (let j = 0; j < obj.data.length; j++) {
                html += '<div class="hover p-2 bg-light unselect" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['id'] + ')"><b> ' + counter + '. ' + obj.data[j]['answers'] + ' (faves: ' + obj.data[j]['vote_count'] + ')</b></div>';
                counter = counter + 1;
            }
            $('.set_suggestion_height').empty();
            $('.set_suggestion_height').html(html);
        },
        error: function (e) {
            console.log(e)
        }
    })
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
                if (obj.data[j]['upvotes'] < 0) {
                    obj.data[j]['upvotes'] = 0;
                }
                html += '<li><a "nofollow" class="link-secondary" href="/comments/' + obj.data[j]['name'].replace(/ /g, '-') + '">' + obj.data[j]['name'] + ' (' + obj.data[j]['upvotes'] + ' upvotes)</a></li>';
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

$('#search_users_comments').on('keyup', function (e) {
    $.ajax({
        type: 'GET',
        url: '/get_comments_list_by_name',
        data: { task: 'comments_list', topic_id: $('#topic_id').val(), user_name: $(this).val() },
        success: function (data) {
            // console.log(data);
            let html = '<ol>';
            let obj = JSON.parse(data);
            for (let j = 0; j < obj.data.length; j++) {
                if (obj.data[j]['upvotes'] < 0) {
                    obj.data[j]['upvotes'] = 0;
                }
                html += '<li><a "nofollow" class="link-secondary" href="/comments/' + obj.data[j]['name'].replace(/ /g, '-') + '">' + obj.data[j]['name'] + ' (' + obj.data[j]['upvotes'] + ' upvotes)</a></li>';
            }
            html += '</ol>';
            $('#top_comments_modal_body').empty();
            $('#top_comments_modal_body').html(html);

        },
        error: function (e) {
            console.log(e)
        }
    })
})

function top_comments_modal_body_for_comments() {
    $.ajax({
        type: 'GET',
        url: '/get_comments_list_all',
        data: { task: 'comments_list' },
        success: function (data) {
            // console.log(data);
            let html = '<ol>';
            let obj = JSON.parse(data);
            for (let j = 0; j < obj.data.length; j++) {
                if (obj.data[j]['upvotes'] < 0) {
                    obj.data[j]['upvotes'] = 0;
                }
                html += '<li><a rel="nofollow" class="link-secondary" href="/comments/' + obj.data[j]['name'].replace(/ /g, '-') + '">' + obj.data[j]['name'] + ' (' + obj.data[j]['upvotes'] + ' upvotes)</a></li>';
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
function share_url(url) {
    let text = "Check this question on ifave";
    $("#facebook_share").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURI(url));
    $("#twitter_share").attr("href", "https://twitter.com/intent/tweet?text=" + encodeURI(text) + "&url=" + encodeURI(url));
    // $("#instagram_share").attr("href", "https://www.instagram.com/share?url=" + encodeURI(url));
}


function redirect_url(x) {
    window.location.replace("/" + x);
}
function scrollRight() {
    const scrollContainer = document.getElementById("scrollContainer");
    scrollContainer.scrollBy({ left: 1000, behavior: "smooth" });
}

function scrollLeftcont() {
    const scrollContainer = document.getElementById("scrollContainer");
    scrollContainer.scrollBy({ left: -1000, behavior: "smooth" });
}

function generate_embeded_code(url, questionName) {
    // Customize the embedded code template with the question link and name
    var embeddedCode = '<a href="' + url + '"><img src="https://ifave.com/images/question_images/user_images/IFAVE_PNG.png" height="30px" width="30px" alt="iFave Logo">' + questionName + '</a>';
    navigator.clipboard.writeText(embeddedCode);
    toastr.success('Embed code copied! This embed code is a link back to the category on iFave that you can place on your website. By doing so you can invite your website visitors to upvote your entry on iFave or just share it. Links to quality content enrich your website and offer added value to your users.')
    // Display or use the generated embed code as needed
    // console.log(embeddedCode);
}


$(document).on('click', '.ajax-pagination .page-link', function (event) {
    event.preventDefault(); // Prevent default link behavior
    let pageUrl = $(this).attr('href'); // Get the URL of the clicked page
    let to_search = $('#search_questions').val();
    let id = $('#topic_id').val();
    $.ajax({
        type: 'GET',
        url: pageUrl,
        data: { task: 'searchQuestions', search: to_search, id: id, topicName: $('#topicName').val() },
        success: function (data) {
            $('#display_questions').empty();
            $('#display_questions').html(data);
        },
        error: function (error) {
            console.log(error)
        }
    })

});
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


$('.personality-potrait').on('click', function (e) {
    $.ajax({
        type: 'GET',
        url: '/personality-potrait',
        data: { task: 'personality_potrait' },
        success: function (data) {
            // console.log(data);
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                if (obj.data != '' || obj.data != null) {
                    $('.personality_headding').empty();
                    $('.personality_content').empty();
                    $('.personality_headding').html('My personality portrait based on my faves');
                    $('.personality_content').html(obj.data);
                }
            }
            if (obj.success == 0) {
                toastr.error(obj.data)
            }
        },
        error: function (error) {

        }
    })
})