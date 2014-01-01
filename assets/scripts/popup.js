if(typeof(open_popup) != "function"){
    function open_popup(option1,option2,callback){
        $.open_popup(option1,option2,callback);
    }
}
(function($){
    $.fn.disableSelection = function() {
        return this
            .attr('unselectable', 'on')
            .css('user-select', 'none')
            .on('selectstart', false);
    };
})(jQuery);
(function($){
    $.fn.enableSelection = function() {
        return this
            .attr('unselectable', 'off')
            .css('user-select', 'all')
            .off('selectstart');
    };
})(jQuery);
(function($,window,document,undefined){
    var defaults	=	{
        confirm		:	false,		//display confirm dialog
        time		:	0,			//autoclose time param
        autoclose	:	false,		//if defined window will autoclose using time param
        ask			:	false,		//if defined window will ask for an answer
        title		:	null,		//title - can be empty
        text		:	null,		//content - must be present
        callback	:	null,		//callback function
        class_ext	:	null,		//extra class name
        close_alt   :   true,       //alternative close button top-right
        ok_text		:	'OK',		//ok button text
        cancel_text	:	'Cancel',	//cancel button text
        close_text	:	'Close',	//close button text
        drag		:	false,		//make popup draggable
        dim_screen	:	false,		//make screen go dim/black
    };

    var curr_el=0;
    var class_name='popup';
    var class_name_dim='popup_dim';
    var options;

    $.open_popup = function(option_base,option_adv,call) {
        var q_check,q_ch;
        var callback;
        var data;
        var popup;
        var title;
        var dim_wrap;
        var content;
        var actions;
        var ok;
        var close;
        var cancel;
        var ask;
        ///////////			OPTIONS				///////////////////////
        if(typeof(option_base)=='object'){
            if(typeof(option_adv)=='object'){
                option_base	= $.extend({},option_base,option_adv);
            }else if(typeof(option_adv)=='function'){
                callback=option_adv;
            }
            if(typeof(call)=='function'){
                callback=call;
            }
            set_options(option_base);
        }
        if(options.ask){
            options.text=options.ask;
        }
        //////////////////////////////////////////////////////////////
        if(typeof(options)=='undefined' || typeof(options.text)=='undefined' || options.text==null || options.text.length==0){return this;}
        if(typeof(callback)=='function'){
            options.confirm=true;
        }else if(typeof(callback)!='function' && options.ask){
            options.text=options.ask;
            options.ask=false;
        }
        if(options.confirm){//set misplaced autoclose to false when confirm is called
            options.autoclose=false;
        }
        $(document).on( "closePopup",function(e,el) {
            var check_class=class_name;
            if(options.dim_screen){check_class=class_name_dim;}
            var ch=$(el),check=ch.is('.'+check_class);
            while(!check){
                ch		=	ch.parent();
                check	=	ch.is('.'+check_class);
            }
            $(ch).fadeOut('fast',function(){
                $(this).remove();
                if($('.'+check_class).size()==0){
                    curr_el=0;
                }

            });
        });


        var dim={};
        popup=$('<div>');

        popup.addClass(class_name);

        if(options.class_ext!=null){
            popup.addClass(options.class_ext);
        }

        popup.on('click',function(e){
            //e.stopPropagation();
            $('.'+class_name).css({zIndex:'auto'});
            $(this).css({zIndex:1000});
        });

        if(options.title!=null){
            title=$('<div>');
            title.addClass('title');
            title.html(options.title.toUpperCase());
            popup.append(title);
        }
        if(options.text!=null){
            content=$('<div>');
            content.addClass('text');
            content.html(options.text);
            if(options.ask){
                ask=$('<input type="text" />');
                ask.addClass('ask');
                content.append(ask);
            }
            popup.append(content);
        }

        if(!options.autoclose){
            actions=$('<div>');
            actions.addClass('actions');
            if(options.confirm){
                ok=$('<div>');
                ok.addClass('ok');
                ok.html(options.ok_text);
                ok.on('click',function(){
                    if(options.ask){
                        q_ch=$(this);
                        q_check=q_ch.is('.'+class_name);
                        while(!q_check){
                            q_ch=q_ch.parent();
                            q_check=q_ch.is('.'+class_name);
                        }
                        data=q_ch.find('input[type="text"]').val();
                    }
                    if(callback!=null){
                        var call_val=callback(data);
                    }

                    if(call_val!==false){
                        ok.trigger('closePopup',ok);
                    }
                });
                actions.append(ok);

                cancel=$('<div>');
                cancel.addClass('cancel');
                cancel.html(options.cancel_text);
                cancel.on('click',function(){
                    cancel.trigger('closePopup',cancel);
                });
                actions.append(cancel);
            }else{
                close=$('<div>');
                if(!options.close_alt){
                    close.addClass('close');
                    close.html(options.close_text);
                    close.on('click',function(){
                        if(callback!=null){
                            callback(data);
                        }
                        close.trigger('closePopup',close);
                    });
                    actions.append(close);
                }else{
                    close.addClass('close_alt');
                    close.on('click',function(){
                        if(callback!=null){
                            callback(data);
                        }
                        close.trigger('closePopup',close);
                    });
                    popup.append(close);
                }
            }
            $(document).on('keyup',function(e){
                if(e.keyCode==27){
                    close.trigger('click');
                }
            });
            popup.append(actions);
        }else{
            setTimeout(function(){
                popup.trigger('closePopup',popup);
            },options.time);
        }


        //main DOM addition
        popup.hide();//set to hide to mask addition to body and get dimensions after

        $(document).find('body').append(popup);//add popup to body
        dim.w			=	popup.outerWidth();//get total width
        dim.h			=	popup.outerHeight();//get total height
        dim.marginTop	=	-Math.round(dim.h/2)+curr_el*5;//position from top point
        dim.marginLeft	=	-Math.round(dim.w/2)+curr_el*5;//position from left point
        dim.t			=	'50%';//absolute position from top
        dim.l			=	'50%';//absolute position from left

        popup.css({width:dim.w,height:dim.h,top:dim.t,left:dim.l,marginTop:dim.marginTop,marginLeft:dim.marginLeft});
        popup.fadeIn('fast');

        if(options.dim_screen){
            dim_wrap=$('<div>');
            dim_wrap.addClass(class_name_dim);
            dim_wrap.on('click',function(e){
                console.log(e.target);
                //popup.trigger('closePopup',popup);
            });
            popup.wrap(dim_wrap);
        }

        //////// make popup draggable
        if(options.drag){
            title.css({cursor:'move'});
            title.on('mousedown',function(e){
                e.stopPropagation();
                e.preventDefault();
                var curr=popup,pos,loc={};
                pos=curr.offset();
                loc.x=pos.left-e.pageX;
                loc.y=pos.top-e.pageY;
                popup.disableSelection();

                $(document).on('mousemove',function(e){
                    e.stopPropagation();
                    e.preventDefault();
                    var curr_pos={};
                    var sTop=$(document).scrollTop()||$(document).scrollTop();
                    //alert(sTop);
                    curr_pos.x=e.pageX+loc.x;
                    curr_pos.y=e.pageY+loc.y-sTop;

                    if(curr!==true){
                        curr.css({left:curr_pos.x,top:curr_pos.y,marginTop:0,marginLeft:0});
                    }
                });
            });
            $(document).on('mouseup',function(e){
                e.stopPropagation();
                e.preventDefault();
                var curr=$(this);
                $(document).off('mousemove');
                popup.enableSelection();
            });
        }
        ////////////////////////

        curr_el++;//increment curr el number
        return options;
    };

    //functions
    function set_options(new_options) {
        options = $.extend({}, defaults, options, new_options);
    }
    return this;
})(jQuery,window,document);