// JavaScript Document
$(document).ready(function () {
    $('.formlogin-prevent-multiple-submits').on('submit', function(){
        $('.button-prevent-multiple-submits').attr('disabled', 'true');
        $('.logo-in').hide();
        $('.spinner').show();
    });

    $('.form-prevent-multiple-submits').on('submit', function(){
        $('.button-prevent-multiple-submits').attr('disabled', 'true');
        // $('.spinner').show();
        $('.button-prevent-multiple-submits .spinner').attr('style', 'display:inline-block');        
    });
});
