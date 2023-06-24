let user_selected_answers = [];
let top_comments = [];
// var path = window.location.pathname;
// var page = path.split("/").pop();
document.addEventListener('copy', function (e) {
    e.preventDefault();
});
var currentPage = 1;
var questionsPerPage = 40;
var allQuestions = []; // Variable to store all the questions retrieved from the API
$('.read-more').on('click', function (e) {
    e.preventDefault();
    $(this).hide();
    $(this).siblings('.full-comment').show();
});
$(document).ready(function () {
    // Function to display questions for the current page

    function displayQuestions(page) {
        var startIndex = (page - 1) * questionsPerPage;
        var endIndex = startIndex + questionsPerPage;
        var questionsToDisplay = allQuestions.slice(startIndex, endIndex);
        var html = '';

        for (var j = 0; j < questionsToDisplay.length; j++) {
            let answers = questionsToDisplay[j]['top_answers'];
            let answersArr = answers.split('}');

            // answersArr = answersArr.sort();
            answersArr = answersArr.sort((a, b) => {
                const votesA = parseInt(a.match(/\d+/));
                const votesB = parseInt(b.match(/\d+/));
                return votesB - votesA;
            });
            // console.log(answersArr)
            if (j == 2) {
                // html += '<div class="col-md-4"><div class="container border border-blue mt-1 p-2 m-2"><p><b>Best comments in this topic</b></p><ol><li>Lena85 (295 upvotes)</li><li>Dansky (285 upvotes)</li><li>Supermind (275 upvotes)</li><li>Quatorze14 (265 upvotes)</li><li>Supermind (265 upvotes)</li></ol></div></div>';
                if (top_comments.length > 0) {
                    html += top_comments;
                }
            }
            html += '  <div class="col-md-4 mb-4"><div class="container border border-blue mt-1" ><div class="question"><div class="h-fixed-30 border-bottom"><h5 class="p-3 ">' + questionsToDisplay[j]['question'] + ' (' + questionsToDisplay[j]['total_votes'] + ' Faves)</h5></div><div class="suggestions p-1"></div>';
            if (user_selected_answers.includes(questionsToDisplay[j]['question_id'])) {
                let p = 1;
                for (let i = 0; i < answersArr.length; i++) {
                    let votes_split = answersArr[i].split('( Faves: ');
                    if (votes_split[0].length > 12) {
                        votes_split[0] = votes_split[0].slice(0, 12) + '... ';
                    }
                    if (i == 0) {
                        html += '<div class="hover p-1"> ' + p + ' ' + votes_split[0] + '(Faves: ' + votes_split[1] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-clone float-end" onclick="copy_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')" aria-hidden="true"></i></div>';
                    } else if (i == 1) {
                        html += '<div class="hover p-1"> ' + p + ' ' + votes_split[0] + '(Faves: ' + votes_split[1] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-share float-end" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')"></i></div>';
                    } else if (i == 2) {
                        html += '<div class="hover p-1"> ' + p + ' ' + votes_split[0] + '(Faves: ' + votes_split[1] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-code float-end" aria-hidden="true" onclick="generate_embeded_code(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\',\'' + questionsToDisplay[j]['question'] + '\')"></i></div>';
                    }

                    p++;
                }

            } else {
                answersArr.map((str, index) => {
                    //let places = `${index + 1} Place (Faves: ${str.match(/\d+/)})`;
                    let new_spl = str.split('( Faves: ')
                    let places = 'Place (Faves:' + new_spl[1];
                    //  html += '<div class="hover p-1"><b> ' + places + '</b></div>';
                    if (index == 0) {
                        html += '<div class="hover p-1"> ' + places + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')" aria-hidden="true"></i></div>';
                    } else if (index == 1) {
                        html += '<div class="hover p-1"> ' + places + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')"></i></div>';
                    }
                    else if (index == 2) {
                        html += '<div class="hover p-1"> ' + places + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" aria-hidden="true" onclick="generate_embeded_code(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\',\'' + questionsToDisplay[j]['question'] + '\')"></i></div>';
                    }
                });

            }

            html += '<div class="text-center"><a href="/questions_details/' + questionsToDisplay[j]['question_id'] + '" class="btn btn-primary m-2">Show me more</a></div></div></div></div>';
        }

        $('#display_questions').html(html);
    }
    // Function to generate pagination links
    function generatePaginationLinks(totalPages) {
        var html = '';

        for (var i = 1; i <= totalPages; i++) {
            html += '<span class="pagination-link btn btn-small btn-primary m-1 custom-page" data-page="' + i + '">' + i + '</span>';
        }

        $('#pagination').html(html);
    }

    // Event handler for pagination links
    $(document).on('click', '.pagination-link', function () {
        var page = parseInt($(this).data('page'));
        $('.custom-page').removeClass('active-page');
        $(this).addClass('active-page');
        if (page !== currentPage) {
            currentPage = page;
            displayQuestions(currentPage);
        }
    });
    $.ajax({
        type: 'GET',
        url: '/indexonloadRequest',
        data: { task: 'get_questions' },
        success: function (data) {
            let obj = JSON.parse(data);
            // console.log(obj.top_comments)
            let html = '';
            let questions_slider = '';
            // let faves_index = 1;
            if (obj.top_comments.length > 0) {
                top_comments += '<div class="col-md-4"><div class="container border border-blue mt-1 p-2 m-2"><p><b>Best comments in this topic</b></p><ol>';
                for (let j = 0; j < obj.top_comments.length; j++) {
                    top_comments += '<li>' + obj.top_comments[j]['name'] + ' (' + obj.top_comments[j]['upvotes'] + ' upvotes)</li>';
                }
                top_comments += '</ol></div></div>';
            }
            if (obj.myfaves.length > 0) {
                for (let k = 0; k < obj.myfaves.length; k++) {
                    html += '<tr><td>' + obj.myfaves[k]['question'] + '</td><td>' + obj.myfaves[k]['answers'] + '</td></tr>';
                    // faves_index = faves_index + 1;
                }
            }
            $('#faves_table_body').empty();
            $('#faves_table_body').html(html);
            $('#faves_table').DataTable();
            $('#display_topic_name').empty()
            // $('#display_topic_name').text('Best In The ' + obj.topic_name.toUpperCase())str.charAt(0).toUpperCase() + str.slice(1)
            $('#display_topic_name').text('Best in ' + obj.topic_name.charAt(0).toUpperCase() + obj.topic_name.slice(1))
            let m = 1;
            for (let j = 0; j < obj.this_user_answers.length; j++) {
                user_selected_answers.push(obj.this_user_answers[j]['question_id'])
            }
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
            obj.data = obj.data.sort((a, b) => parseInt(b.total_votes) - parseInt(a.total_votes));
            allQuestions = obj.data; // Store all questions in the variable
            // console.log(obj.data)
            var totalPages = Math.ceil(allQuestions.length / questionsPerPage);
            generatePaginationLinks(totalPages);

            displayQuestions(currentPage);

            // $('#display_questions').empty()
            // $('#display_questions').html(html)
        },
        error: function (e) {
            console.log(e);
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
                //$("exampleModal1").attr("role","dialog");
                // location.reload(true)
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

            let obj = JSON.parse(data);
            let m = 1;
            // Function to display questions for the current page
            function displayQuestions(page) {
                var startIndex = (page - 1) * questionsPerPage;
                var endIndex = startIndex + questionsPerPage;
                var questionsToDisplay = allQuestions.slice(startIndex, endIndex);
                var html = '';

                for (var j = 0; j < questionsToDisplay.length; j++) {
                    let answers = questionsToDisplay[j]['top_answers'];
                    let answersArr = answers.split('}');
                    // answersArr = answersArr.sort();
                    answersArr = answersArr.sort((a, b) => {
                        const votesA = parseInt(a.match(/\d+/));
                        const votesB = parseInt(b.match(/\d+/));
                        return votesB - votesA;
                    });
                    if (j == 2) {
                        //html += '<div class="col-md-4"><div class="container border border-blue mt-1 p-2 m-2"><p><b>Best comments in this topic</b></p><ol><li>Lena85 (295 upvotes)</li><li>Dansky (285 upvotes)</li><li>Supermind (275 upvotes)</li><li>Quatorze14 (265 upvotes)</li><li>Supermind (265 upvotes)</li></ol></div></div>';
                        if (top_comments.length > 0) {
                            html += top_comments;
                        }
                    }
                    html += '  <div class="col-md-4 mb-4"><div class="container border border-blue mt-1" ><div class="question"><div class="h-fixed-30 border-bottom"><h5 class="p-3 ">' + questionsToDisplay[j]['question'] + ' (' + questionsToDisplay[j]['total_votes'] + ' Faves)</h5></div><div class="suggestions p-1"></div>';
                    if (user_selected_answers.includes(questionsToDisplay[j]['question_id'])) {
                        let p = 1;
                        for (let i = 0; i < answersArr.length; i++) {
                            let votes_split = answersArr[i].split('( Faves: ');
                            if (votes_split[0].length > 12) {
                                votes_split[0] = votes_split[0].slice(0, 12) + '... ';
                            }
                            if (i == 0) {
                                html += '<div class="hover p-1"> ' + p + ' ' + votes_split[0] + '(Faves: ' + votes_split[1] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-clone float-end" onclick="copy_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')" aria-hidden="true"></i></div>';
                            } else if (i == 1) {
                                html += '<div class="hover p-1"> ' + p + ' ' + votes_split[0] + '(Faves: ' + votes_split[1] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-share float-end" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')"></i></div>';
                            } else if (i == 2) {
                                html += '<div class="hover p-1"> ' + p + ' ' + votes_split[0] + '(Faves: ' + votes_split[1] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <i class="fa fa-code float-end" aria-hidden="true" onclick="generate_embeded_code(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\',\'' + questionsToDisplay[j]['question'] + '\')"></i></div>';
                            }

                            p++;
                        }

                    } else {
                        answersArr.map((str, index) => {
                            //let places = `${index + 1} Place (Faves: ${str.match(/\d+/)})`;
                            //  html += '<div class="hover p-1"><b> ' + places + '</b></div>';
                            let new_spl = str.split('( Faves: ')
                            let places = 'Place (Faves:' + new_spl[1];
                            if (index == 0) {
                                html += '<div class="hover p-1"> ' + places + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-clone float-end" onclick="copy_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')" aria-hidden="true"></i></div>';
                            } else if (index == 1) {
                                html += '<div class="hover p-1"> ' + places + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-share float-end" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#sharemodal" onclick="share_url(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\')"></i></div>';
                            }
                            else if (index == 2) {
                                html += '<div class="hover p-1"> ' + places + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-code float-end" aria-hidden="true" onclick="generate_embeded_code(\'' + 'https://ifave.com/questions_details/' + questionsToDisplay[j]['question_id'] + '\',\'' + questionsToDisplay[j]['question'] + '\')"></i></div>';
                            }
                        });

                    }

                    html += '<div class="text-center"><a href="/questions_details/' + questionsToDisplay[j]['question_id'] + '" class="btn btn-primary m-2">Show me more</a></div></div></div></div>';
                }

                $('#display_questions').html(html);
            }
            // Function to generate pagination links
            function generatePaginationLinks(totalPages) {
                var html = '';

                for (var i = 1; i <= totalPages; i++) {
                    html += '<span class="pagination-link btn btn-small btn-primary m-1 custom-page" data-page="' + i + '">' + i + '</span>';
                }

                $('#pagination').html(html);
            }
            allQuestions = [];
            obj.data = obj.data.sort((a, b) => parseInt(b.total_votes) - parseInt(a.total_votes));
            allQuestions = obj.data;

            console.log(allQuestions)
            var totalPages = Math.ceil(allQuestions.length / questionsPerPage);
            generatePaginationLinks(totalPages);
            displayQuestions(currentPage);
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
                html += '<div class="hover p-2 bg-light" oncopy="return false" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['id'] + ')"><b> ' + counter + ' ' + obj.data[j]['answers'] + ' (Faves: ' + obj.data[j]['vote_count'] + ')</b></div>';
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

function share_url(url) {
    let text = "Check this question on ifave";
    $("#facebook_share").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURI(url));
    $("#twitter_share").attr("href", "https://twitter.com/intent/tweet?text=" + encodeURI(text) + "&url=" + encodeURI(url));
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




