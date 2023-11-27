(function () {
    // Add the img-fluid class to all <img> tags within the #content div

    // $('#blog_content iframe').addClass('embed-responsive embed-responsive-4by3');


    let url = $('#to_share_link').val();
    let text = "Checkout my comment on ifave";
    $("#facebook_share_comment").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURI(url));
    $("#twitter_share_comment").attr("href", "https://twitter.com/intent/tweet?text=" + encodeURI(text) + "&url=" + encodeURI(url));
    // $('#edit').richText();
    var uploadUrl = $('#thoughts').data('upload-url');
    new FroalaEditor("#thoughts", {
        key: "OXC1lD4B3B14B10D6D6C5dNSWXa1c1MDe1CI1PLPFa1F1EESFKVlA6F6D5H5A1D3A11A3D5F4==",
        theme: 'dark',
        toolbarButtons: {
            // Customize the buttons you want to show in the toolbar.
            // Remove 'insertVideo' to exclude it. Keep 'insertImage'.
            moreText: {
                buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor']
            },
            moreParagraph: {
                buttons: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
            },
            moreRich: {
                buttons: ['insertImage', 'insertLink', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters']
            },
            moreMisc: {
                buttons: ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
            }
        },
        imageUploadURL: uploadUrl,
        imageUploadMethod: 'POST', // Set the method to POST
        imageUploadParams: {
            _token: $('meta[name="csrf-token"]').attr('content') // Pass the CSRF token if required
        }


    })
    // let screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    // if ($('.hidden-cotnent').length) {
    //     if (screenWidth <= 768) {
    //         // deviceSize = 'mobile';
    //         $('.for-mobile-screen').removeClass('d-none')
    //         let str = $('.hidden-cotnent').html();
    //         if (str.length > 450) {
    //             str = str.slice(0, 1000);
    //         }
    //         console.log(str)
    //         var el = document.implementation.createHTMLDocument().createElement('div');
    //         el.innerHTML = str;
    //         str = el.innerHTML;
    //         $('.half-thoughts-mobile-screen').html(str);
    //     } else {
    //         // deviceSize = 'desktop';
    //         $('.for-full-screen').removeClass('d-none')
    //         //    var str = "This <small>is <i>ONE</small> Messed up string</i><strong>.";
    //         let str = $('.hidden-cotnent').html();
    //         if (str.length > 1000) {
    //             str = str.slice(0, 1000);
    //         }
    //         console.log(str)
    //         var el = document.implementation.createHTMLDocument().createElement('div');
    //         el.innerHTML = str;
    //         str = el.innerHTML;
    //         $('.half-thoughts-full-screen').html(str);
    //         let start_content=0;
    //         let end_Content=2000;
    //         read_more_function(str,start_content,end_Content);
    //     }
    // }
    let screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    if ($('.hidden-cotnent').length) {
        let str = $('.hidden-cotnent').html();

        if (screenWidth <= 768) {
            $('.for-mobile-screen').removeClass('d-none');
            showPartialContent(str, 2500, '.half-thoughts-mobile-screen');
            $('.half-thoughts iframe').removeAttr('height');
            $('.half-thoughts iframe').removeAttr('width');
        } else {
            $('.for-full-screen').removeClass('d-none');
            showPartialContent(str, 9000, '.half-thoughts-full-screen');
        }
    }

    function showPartialContent(content, chunkSize, targetSelector) {
        let $target = $(targetSelector);
        let currentIndex = 0;

        function updateContent() {
            // let nextChunk = content.substr(currentIndex, chunkSize);
            let nextChunk = content.slice(0, chunkSize);
            // console.log(nextChunk)
            var el = document.implementation.createHTMLDocument().createElement('div');
            el.innerHTML = nextChunk;
            nextChunk = el.innerHTML;
            $target.empty();
            $target.append(nextChunk);
            // console.log(el)
            currentIndex += 4500;
            chunkSize += 4500;
        }

        function handleReadMoreClick() {
            updateContent();
            // console.log(el)
            $('.half-thoughts img').addClass('img-fluid');
            $('.half-thoughts span:has(img)').each(function () {
                // Remove the 'style' attribute from the <span> element
                $(this).removeAttr('style');
            });
            if (screenWidth <= 768) {
                let width_iframe = screenWidth - 50;
                $('.half-thoughts iframe').removeAttr('height');
                $('.half-thoughts iframe').removeAttr('width');
                $('.half-thoughts iframe').width(width_iframe);
            }
            if (currentIndex >= content.length) {
                $(this).hide(); // hide "Read More" button when all content is displayed
            }
        }

        updateContent();
        $(targetSelector).next('.read-more-thoughts').on('click', handleReadMoreClick);
    }

    if (screenWidth < 998) {
        $('.info-large-screen').hide();
        $('.info-small-screen').removeClass();
    }
})()

// $('.read-more-thoughts').on('click', function (e) {
//     e.preventDefault();
//     $(this).hide();
//     let halfCommentElement = $(this).closest('.thoughts-content').find('.half-comment');
//     // $(this).siblings('.full-comment').show();
//     let fullCommentElement = $(this).closest('.thoughts-content').find('.full-comment');
//     halfCommentElement.hide();
//     fullCommentElement.show();
// });
$('#submit_thoughts').on('submit', function (e) {
    e.preventDefault();
    let error_count = 0;
    if ($('#thoughts').val() == '' || $('#thoughts').val().length < 100) {
        $('#content_error').removeClass('d-none');
        error_count = error_count + 1;
    } else {
        $('#content_error').addClass('d-none');
    }
    if (error_count > 0) {
        return;
    }
    $.ajax({
        type: 'POST',
        url: '/add_thoughts',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            // $("#msg").html(data.msg);
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data)
            }
            if (obj.success == 0) {
                toastr.error(obj.data);
            }
        },
        error: function (e) {
            console.log(e)
        }
    });
})

// function get_selected_image(x) {
$('.content_images').on('change', function (e) {
    var formData = new FormData();

    // Get all the files so we easily can get the length etc.
    // Not necessary, but it will make the code easier to read.
    formData.append('content_images', $(this)[0].files[0]);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    console.log(formData);
    // return;
    $.ajax({
        url: "/upload_content_image",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            console.log(data);
            var obj = JSON.parse(data);
            let html = '';
            // invalid file format.
            if (obj.success == 1) {
                html += '<div class="row mt-5"><div class="col-md-3"><img src="' + obj.path + '" heigth="100px" width="100px"></img></div><div class="col-md-9"><p><b>URL:</b></p><p onclick="copy_image_path(\'' + obj.path + '\')">' + obj.path + '</p></div></div>';
                $('.images_div').append(html);
            } else {
                toastr.error(obj.data);
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
})


$('.reply-btn').click(function (e) {
    e.preventDefault();
    const commentId = $(this).data('comment-id');
    $(`.reply-form-${commentId}`).toggle();
});

$('#add_comments').on('submit', function (e) {

    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: '/add_user_comments_ajax',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            let obj = JSON.parse(data);
            if (obj.success == 1) {
                toastr.success(obj.data)
            }
            if (obj.success == 2) {
                toastr.success(obj.data)
                // $('#sharecommentmodal').show();
                $('#sharecommentmodal').addClass('show');
                $('#sharecommentmodal').show();

            }
            if (obj.success == 0) {
                toastr.error(obj.data)
            }
            $('#add_comments').trigger("reset");
        },
        error: function (error) {

        }
    })
})
$('.closesharecommentmodal').on('click', function (e) {
    $('#sharecommentmodal').removeClass('show');
    $('#sharecommentmodal').hide();
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

function generate_embeded_code(url, questionName) {
    // Customize the embedded code template with the question link and name
    var embeddedCode = '<a href="' + url + '"><img src="https://ifave.com/images/question_images/user_images/IFAVE_PNG.png" height="30px" width="30px">' + questionName + '</a>';
    navigator.clipboard.writeText(embeddedCode);
    toastr.success('Embed code copied! This embed code is a link back to the category on iFave that you can place on your website. By doing so you can invite your website visitors to upvote your entry on iFave or just share it. Links to quality content enrich your website and offer added value to your users.')
    // Display or use the generated embed code as needed
    // console.log(embeddedCode);
}
function share_url(url) {
    let text = "Check this question on ifave";
    $("#facebook_share").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURI(url));
    $("#twitter_share").attr("href", "https://twitter.com/intent/tweet?text=" + encodeURI(text) + "&url=" + encodeURI(url));
    // $("#instagram_share").attr("href", "https://www.instagram.com/share?url=" + encodeURI(url));
}
function copy_url(x) {
    navigator.clipboard.writeText(x);
    toastr.success('Link copied!')
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