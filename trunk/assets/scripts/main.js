jQuery.cachedScript = function( url, options ) {

    // Allow user to set any option except for dataType, cache, and url
    options = $.extend( options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });

    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return jQuery.ajax( options );
};

function createCookie(name,value,days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+Math.ceil(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    document.cookie = name+"="+value+expires+"; path=/;";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return false;
}
function eraseCookie(name) {
    createCookie(name,"",-1);
}

$(document).ready(function(){


    $(document).on('click','.btn_choose_register_or_login', function(){

        $.post('/async/register_login/',function(data){
            div=document.createElement('div');

            $(div).addClass('pop_up_dim');
            $(div).css({position:'absolute',background:'rgba(0,0,0,0.5)',top:0,left:0,width:'100%',height:height,zIndex:10});
            $(div).append(data).fadeIn();
            $('body').append($(div));

            $(div).on('click',function(e){
                if($(e.target).hasClass('pop_up_dim')){
                    $(div).fadeOut(function(){
                        $(this).remove();
                    });
                }
            });
            $(document).on('click', '.pop_up_div', function(){
                $(div).fadeOut(function(){
                    $(div).remove();
                });
            });
            $(document).on('keyup',function(e){
                if(e.keyCode==27){
                    $(div).fadeOut(function(){
                        $(this).remove();
                    });
                }
            });
            $(document).on('click','.close_btn',function(e){
                $(div).fadeOut(function(){
                    $(this).remove();
                });
            });
            $('.pop_up').on('click',function(e){
                //e.stopPropagation();
            });
        });
    });


    $(document).on('click','#user_reg', function(e){
        $.post('/register/validate_user_registration',$("#user_reg").parent().find('form').serialize(), function(data){
            $(div).empty();
            $(div).append(data);
            //alert("cao, zdravo!!!!!");
        });
    });


    $(document).on('click','.btn_login_drop',function(e){
        e.preventDefault();
        $('.login_container').toggle('slow');
        $('.close_btn').trigger('click');
    });

    var hash = window.location.hash;


    $(document).on('keyup','.js_submit_field', function (e) {
        var page = parseInt($(this).val());
        if (e.keyCode == 13) {
            $(this).closest('form').find('.js_submit_button').trigger('click');
        }
    });

    $(document).on('click','.js_submit', function(){
        $(this).closest('form').submit();
    });



    $('.js_remove_my_parent').on('click',function(e){
            $(this).parent().remove();
            $('.btn_vehicle_search_submit').trigger('click');
        });
});

function show_alert(type,message, disappear){
    var alert=$('.popup_alert').clone();
    alert.addClass(type);
    alert.find('.popup_txt').text(message);
    $('body').append(alert);
    alert.show();
    alert.fadeIn('slow');

    if(disappear==true){
    setTimeout(function(){alert.fadeOut('slow', function(){alert.remove()})},3000);

    }

}




