$(document).ready(function(){
    $('#questions').DataTable();
})

function edit_modal_form(x) {
    // console.log(x)
    $('#dashboard_answer_id').val(x);
    $.ajax({
        type: 'GET',
        url: '/dashboard_answer_details',
        data: { answer: x },
        success: function (data) {
            let obj = JSON.parse(data);
            console.log(obj)
            let html = '';
            $('#answer').val(obj.data[0]['answers'])
        },
        error: function (e) {
            console.log(e)
        }
    });
}

function delete_modal_form(x){
    $('#delete_answer_id').val(x);
}