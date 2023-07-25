$('.post-thumbs-up').on('click', function (e) {
    $.ajax({
        type: 'POST',
        url: '/upvote_post',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), post_id:$('#post_id').val(), upvote: $('#post_vote_count').val() },
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
})
$('.post-thumbs-down').on('click',function(e){
    $.ajax({
        type: 'POST',
        url: '/downvote_post',
        data: { '_token': $('meta[name="csrf-token"]').attr('content'), post_id: $('#post_id').val(), down_vote: $('#post_down_votes').val() },
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
})