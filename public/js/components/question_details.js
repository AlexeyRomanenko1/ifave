(function () {
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
    let screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    if (screenWidth <= 768) {
        // deviceSize = 'mobile';
        $('.for-mobile-screen').removeClass('d-none')
        let str = $('.hidden-cotnent').html();
        if (str.length > 450) {
            str = str.slice(0, 450);
        }
        //console.log(str)
        var el = document.implementation.createHTMLDocument().createElement('div');
        el.innerHTML = str;
        str = el.innerHTML;
        $('.half-thoughts-full-screen').html(str);
    } else {
        // deviceSize = 'desktop';
        $('.for-full-screen').removeClass('d-none')
        //    var str = "This <small>is <i>ONE</small> Messed up string</i><strong>.";
        let str = $('.hidden-cotnent').html();
        if (str.length > 1000) {
            str = str.slice(0, 1000);
        }
        //console.log(str)
        var el = document.implementation.createHTMLDocument().createElement('div');
        el.innerHTML = str;
        str = el.innerHTML;
        $('.half-thoughts-full-screen').html(str);
    }
})()


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
