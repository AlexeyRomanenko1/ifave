// $(document).ready(function () {
//     $.ajax({
//         type: 'GET',
//         url: '/dashboard_questions',
//         data: { task: 'get_questions' },
//         success: function (data) {
//             let obj = JSON.parse(data);
//             let html = '';
//             let k = 1;
//             for (let j = 0; j < obj.data.length; j++) {
//                 html += '<tr><td>' + k + '</td><td>' + obj.data[j]['question'] + '</td><td>' + obj.data[j]['topic_name'] + '</td><td>' + obj.data[j]['question_category'] + '</td><td><i class="fa fa-bars text-success m-1 p-2" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#edit_question_modal" onclick="edit_modal_form(' + obj.data[j]['id'] + ')" ></i><i class="fa fa-trash m-1 text-danger p-2" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#delete_question_modal" onclick="delete_modal_form(' + obj.data[j]['id'] + ')"></i><a target="_blank" href="/answers/'+ obj.data[j]['question_category'] +'"><i class="fa fa-eye m-1 text-primary p-2" aria-hidden="true"></i></a></td></tr>';
//                 k = k + 1;
//             }
//             $('#questions_table_body').empty();
//             $('#questions_table_body').html(html);
//             // console.log(obj);
//             $('#questions').DataTable();
//         },
//         error: function (e) {
//             console.log(e)
//         }
//     });

// });


function edit_modal_form(x) {
    // console.log(x)
    $('#dashboard_question_id').val(x);
    $.ajax({
        type: 'GET',
        url: '/dashboard_question_details',
        data: { question: x },
        success: function (data) {
            let obj = JSON.parse(data);
            console.log(obj)
            let html = '';
            let k = 1;
            html += ' <option value="' + obj.data[0]['topic_id'] + '" selected>' + obj.data[0]['topic_name'] + '</option>';
            for (let j = 0; j < obj.topics.length; j++) {
                if (obj.topics[j]['id'] != obj.data[0]['topic_id'])
                    html += ' <option value="' + obj.topics[j]['id'] + '">' + obj.topics[j]['topic_name'] + '</option>';
            }
            $('#topic_name').empty();
            $('#topic_name').html(html);
            $('#question').val(obj.data[0]['question'])
        },
        error: function (e) {
            console.log(e)
        }
    });
}

function delete_modal_form(x){
    $('#delete_question_id').val(x);
}