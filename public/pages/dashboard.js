// JavaScript Document
$(document).ready(function () {

    $(".btnUploadFile").click(function () {
        // var id = $(this).data('val');
        $.ajax({
            type: "GET",
            url: link + 'document/createFile',
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

    $(".btnAddFolder").click(function () {
        // var id = $(this).data('val');
        $.ajax({
            type: "GET",
            url: link + 'document/createFolder',
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

    $(document).on('submit', "#formCreateFile", function (e) {
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

    $(document).on('submit', "#formCreateFolder", function (e) {
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

    $('#example').on('click', '.btnEdit', function (e) {
        var id   = $(this).data('val');
        var type = $(this).data('type');
        var url  = '';

        if (type == 'FILE') {
            url = link + 'document/' + id + '/editFile';
        } else if (type == 'FOLDER') {
            url = link + 'document/' + id + '/editFolder';
        }
        console.log(url, type);
        $.ajax({
            type: "GET",
            url: url,
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

    $(document).on('submit', "#formEditFile", function (e) {
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

    $(document).on('submit', "#formEditFolder", function (e) {
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

    $('#example').on('click', '.btnCopy', function (e) {
        var id    = $(this).data('val');
        var type  = $(this).data('type');
        // var co_id = $(this).data('co');
        var url  = '';

        if (type == 'FILE') {
            url = link + 'document/copyFile/' + id;
        } else if (type == 'FOLDER') {
            url = link + 'document/copyFolder/' + id;
        }
        // console.log(url, type);
        $.ajax({
            type: "GET",
            url: url,
            success: function (html) {
                // console.log(html);
                // var folder_jsondata = JSON.parse($('#txt_folderjsondata').val());

                $('.modal-content').html(html);
                $('#modal').modal('show');

                $('#tree').jstree({
                    'core' : {
                        // 'multiple' : false,
        				'data' : {
                                   'url'  : link + 'document/getJtreeData/'+id,
                                   // 'data' : function (node) {
                                   //             return { 'id' : node.id, 'parent' : node.parent, 'text' : node.text };
                                   //          },
                                   "dataType" : "json"
                               },
                    }
                });

                $('#tree').on("select_node.jstree", function (e, data) {
                    var content = '<input type="hidden" name="target_id" value="'+data.instance.get_node(data.selected).id+'">';
                        content += '<label for="html">Target Folder : '+data.instance.get_node(data.selected).text+'</label>';
                    $('#targetFolder').html(content);
                    // console.log(data.instance.get_node(data.selected).id);
                    // if(data.selected.length) {
        			// 	alert('The selected node is: ' + data.instance.get_node(data.selected[0]).text);
        			// }
                });
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

    $(document).on('submit', "#formCopyFile", function (e) {
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

    $(document).on('submit', "#formCopyFolder", function (e) {
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

    $('#example').on('click', '.btnMove', function (e) {
        var id    = $(this).data('val');
        var type  = $(this).data('type');
        // var co_id = $(this).data('co');
        var url  = '';

        // console.log(url, type);
        $.ajax({
            type: "GET",
            url: link + 'document/moveDocument/' + id,
            success: function (html) {
                // console.log(html);
                // var folder_jsondata = JSON.parse($('#txt_folderjsondata').val());

                $('.modal-content').html(html);
                $('#modal').modal('show');

                $('#tree').jstree({
                    'core' : {
                        // 'multiple' : false,
        				'data' : {
                                   'url'  : link + 'document/getJtreeData/'+id,
                                   // 'data' : function (node) {
                                   //             return { 'id' : node.id, 'parent' : node.parent, 'text' : node.text };
                                   //          },
                                   "dataType" : "json"
                               },
                    }
                });

                $('#tree').on("select_node.jstree", function (e, data) {
                    var content = '<input type="hidden" name="target_id" value="'+data.instance.get_node(data.selected).id+'">';
                        content += '<label for="html">Target Folder : '+data.instance.get_node(data.selected).text+'</label>';
                    $('#targetFolder').html(content);
                    // console.log(data.instance.get_node(data.selected).id);
                    // if(data.selected.length) {
        			// 	alert('The selected node is: ' + data.instance.get_node(data.selected[0]).text);
        			// }
                });
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

    $(document).on('submit', "#formMoveDocument", function (e) {
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

});
