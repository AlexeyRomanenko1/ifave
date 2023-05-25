$(document).ready(function () {
    // console.log('I am ready');
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    $.ajax({
        type: 'GET',
        url: '/indexonloadRequest',
        data: { task: 'get_questions' },
        success: function (data) {
            // alert(data.success);
            //console.log(data);
            let obj = JSON.parse(data);
            // console.group(obj);
            let html = '';
            for (let j = 0; j < obj.data.length; j++) {
                let answers = obj.data[j]['top_answers'];
                let answersArr = answers.split(',');
                // answersArr = answersArr.sort();
                answersArr = answersArr.sort((a, b) => {
                    const votesA = parseInt(a.match(/\d+/));
                    const votesB = parseInt(b.match(/\d+/));
                    return votesB - votesA;
                });
                //console.log(answersArr)
                html += '<div class="container border mt-1" ><div class="question"><h6 class="p-3 border-bottom">Q: ' + obj.data[j]['question'] + ' (' + obj.data[j]['question_votes'] + ' votes)</h6><div class="suggestions">';
                for (let i = 0; i < answersArr.length; i++) {
                    html += '<div class="hover p-1"><b>' + answersArr[i] + '</b></div>';
                }
                html += '<button type="button" class="btn btn-primary mb-1" onclick="questions_modal(' + obj.data[j]['question_id'] + ')" data-bs-toggle="modal" data-bs-target="#exampleModal">Show More Answers</button></div></div></div>';
            }
            $('#display_questions').empty()
            $('#display_questions').html(html)
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
            //console.log(obj.question[0].question)
            $('.question_modal_heading').empty();
            $('.question_modal_heading').html(obj.question[0].question)
            // let html = '<input type="text" class="form-control mb-1 questions_answer_search" onkeyup="answers_search(this,' + x + ')" placeholder="Search options">';
            let html = '';
            $('.questions_answer_search').attr('data-info', x);
            for (let j = 0; j < obj.answers.length; j++) {
                html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.answers[j]['answer_id'] + ')"><b>' + obj.answers[j]['answers'] + '</b></div>';
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
    console.log(id);
    // if (search.length >= 3) {
    $.ajax({
        type: 'GET',
        url: '/searchAnswers',
        data: { task: 'searchAnswers', question_id: id, search: search },
        success: function (data) {
            // console.log(data)
            let obj = JSON.parse(data);
            console.log(obj)
            // html = '<input type="text" class="form-control mb-1 questions_answer_search" value="' + search + '" onkeyup="answers_search(this,' + id + ')" placeholder="Search options">';
            let html = '';
            if (obj.data.length > 0) {
                for (let j = 0; j < obj.data.length; j++) {
                    html += '<div class="hover p-1" onmouseover="highlight_sug(this)" onmouseout="nohighlight_sug(this)" onclick="add_vote(' + obj.data[j]['answer_id'] + ')"><b>' + obj.data[j]['answers'] + '</b></div>';
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
    // } else {
    //     $.ajax({
    //         type: 'GET',
    //         url: '/searchAnswers',
    //         data: { task: 'searchAnswers', question_id: id, search: search },
    //         success: function (data) {
    //             // console.log(data)
    //             let obj = JSON.parse(data);
    //             console.log(obj)
    //            // html = '<input type="text" class="form-control mb-1 questions_answer_search" value="' + search + '" onkeyup="answers_search(this,' + id + ')" placeholder="Search options">';
    //            let html=''; 
    //            if (obj.data.length > 0) {
    //                 for (let j = 0; j < obj.data.length; j++) {
    //                     html += '<li class="hover"><b>' + obj.data[j]['answers'] + '</b></li>';
    //                 }

    //             } else {
    //                 html += '<li class="hover"><b>No answer found</b></li>';
    //             }
    //             html += '</ol>';
    //             $('.modal-suggestions').empty();
    //             $('.modal-suggestions').html(html);
    //         },
    //         error: function (error) {

    //         }
    //     })
    // }
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
            console.log(data)
        }
    });
}