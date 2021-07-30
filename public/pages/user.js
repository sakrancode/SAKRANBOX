// JavaScript Document
$(document).ready(function () {

    $(".btnTambah").click(function () {
        // var id = $(this).data('val');
        $.ajax({
            type: "GET",
            url: link + 'user/create',
            success: function (html) {
                $('.modal-content').html(html);
                $('#modal').modal('show');
            },
            error: function (xhr, type, exception) {
                if(exception != "Unauthorized"){
                    toastr["error"]("Ajax Bermasalah !!!<br/>"+exception, "Exception :");
                }else{
                    toastr["error"]("Login Session Expired !!!", "Exception :");
                }
            },
        });
    });
    
    $('#tableData').on('click', '.btnEdit', function (e) {
        var id = $(this).data('val');
        $.ajax({
            url: link + 'user/'+id+'/edit',
            success: function (html) {
                $('.modal-content').html(html);
                $('#modal').modal('show');
            },
            error: function (xhr, type, exception) {
                if(exception != "Unauthorized"){
                    toastr["error"]("Ajax Bermasalah !!!<br/>"+exception, "Exception :");
                }else{
                    toastr["error"]("Login Session Expired !!!", "Exception :");
                }
            },
        });
    });

    $(document).on('submit', "#formCreate", function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (resp) {
                if(resp.msg == "success"){
                    window.setTimeout(function () {
                        window.location = window.location;
                    }, 100);
                }else{
                    toastr["warning"](resp.msg, "Warning :");
                    $('.button-prevent-multiple-submits').removeAttr('disabled');
                    $('.spinner').hide();
                }
            },
            error: function (xhr, type, exception) {
                if(exception != "Unauthorized"){
                    toastr["error"]("Ajax Bermasalah !!!<br/>"+exception, "Exception :");
                }else{
                    toastr["error"]("Login Session Expired !!!", "Exception :");
                }
            },
        });

    });

    $(document).on('submit', "#formEdit", function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (resp) {
                if(resp.msg == "success"){
                    window.setTimeout(function () {
                        window.location = window.location;
                    }, 100);
                }else{
                    toastr["warning"](resp.msg, "Warning :");
                    $('.button-prevent-multiple-submits').removeAttr('disabled');
                    $('.spinner').hide();
                }
            },
            error: function (xhr, type, exception) {
                if(exception != "Unauthorized"){
                    toastr["error"]("Ajax Bermasalah !!!<br/>"+exception, "Exception :");
                }else{
                    toastr["error"]("Login Session Expired !!!", "Exception :");
                }
            },
        });

    });

    $(document).on('submit', "#formDelete", function (e) {
        e.preventDefault();

        var r = confirm('ANDA YAKIN INGIN MENGHAPUS DATA INI ???');
        if (r == true) {
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (resp) {
                    if(resp.msg == "success"){
                        window.setTimeout(function () {
                            window.location = window.location;
                        }, 100);
                    }else{
                        toastr["warning"](resp.msg, "Warning :");
                    }
                },
                error: function (xhr, type, exception) {
                    if(exception != "Unauthorized"){
                        toastr["error"]("Ajax Bermasalah !!!<br/>"+exception, "Exception :");
                    }else{
                        toastr["error"]("Login Session Expired !!!", "Exception :");
                    }
                },
            });
        }

    });

});
