$(function(){
    $('#plus_icon_container').click(function(e){
        e.preventDefault();
        $('#file_upload_input').trigger('click');

       // $('.progress .total').text(files.length);
      //  $('.done').hide();
      //  $('.progress').show();
       // upload_file(files);
    });
    $(document).on('change','#file_upload_input',function(){
        //console.log('uploadujemo sliku');
        var ad_id=$(this).attr('data-id');
        //if(ad_id){}
        var files=$('#file_upload_input')[0].files;
        seq_num=0;
        //console.log(files);
        upload_file(files, 1,0,ad_id);
    });
    $('#js_add_featured_image_news').click(function(e){
        e.preventDefault();
        $('#thumbnail_upload_input').trigger('click');

        // $('.progress .total').text(files.length);
        //  $('.done').hide();
        //  $('.progress').show();
        // upload_file(files);
    });
    $('#thumbnail_upload_input').on('change', function(){
        seq_num=0;
        var files=$('#thumbnail_upload_input')[0].files;
        upload_file(files);
        //console.log(files);
    });
});
function upload_file(files,flag, seq_num,user_id){        // upload vise fajlova
    if(typeof(seq_num)!=='undefined' && seq_num!=null){
        //sequence number arrived
    }else{
        seq_num=0;
    }
    var formData = new FormData();
    if(flag==1){
        formData.append('file',$('#file_upload_input').val());
        formData.append('limit_width',1280);
        formData.append('file_upload_input[]',files[seq_num]);
        $('.progress .curr').text(seq_num+1);
        var url_suffix="";
        if(user_id){
            url_suffix=user_id;
        }
        $.ajax({
            url: '/async/upload_user_image/'+url_suffix,
            type: 'POST',
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',function(e){console.log(e);
                        if(e.lengthComputable){
                            $('#media_form progress').attr({value:e.loaded,max:e.total});
                        }
                    }, false);
                }
                //console.log('/async/upload_ad_image/'+url_suffix);
                return myXhr;
            },
            success: function(data, t1, t2){
                console.log(data);
                seq_num++;
                //parse_upl_data(data);
                //console.log(files.length);
                if(seq_num<files.length){
                    upload_file(files,1,seq_num, url_suffix);
                }else{
                    $('.progress').hide();
                    $('.done').show();
                }
                if(data){
                    //console.log(data);
                    var response = null;
                    try{
                        response= JSON.parse(data);
                        console.log(data);
                        //console.log('dasdadsasdasd');
                        //console.log(response.error);
                        //console.log(jQuery.type(response), t1, t2);
                        if(jQuery.type(response)=='object' && response.error==""){
                            //alert('upload slika je dao ispravan odgovor');
                            var image_box='<img class="img-responsive img-circle js_user_logo_image" alt="Nema slike" src="'+response.file+'" alt="">';
                            $('.js_user_logo_image').remove();
                            $('.js_user_logo_image_container').prepend(image_box);

                        } else {
                            //show_alert('warning', response.error);
                            alert(response.error);
                        }
                    } catch(e){
                        //show_alert('warning', "Prekoračili ste dozvoljenu veličinu slike. Maksimalna veličina je 5MB.");
                        alert(response.error);
                    }
                }
                $('.loader').closest('.img_box').remove();

            },
            //Ajax events
            error: function(){$('.loader').parent().remove();},
            // Form data
            data: formData,
            progress:function(e){console.log(e);
                if(e.lengthComputable){
                    $('#media_form progress').attr({value:e.loaded,max:e.total});
                }
            },
            //Options to tell JQuery not to process data or worry about content-type
            cache: false,
            contentType: false,
            processData: false
        });
    }
}

function parseSize(size){
    var div=1024,MB,KB,B,out;

    MB=Math.floor(size/div/div);
    size=size-MB*div*div;
    KB=Math.floor(size/div);
    size=size-KB*div;
    B=size;
    if(MB==0){out='~'+KB+' K';}else{out='~'+MB+' M';}
    return  out;
}



///////////////////
function parse_upl_data(data){

    //$('.progress').text('Media uploaded');
    $('#media_form progress').attr({value:100,max:100});
    var dec= $.parseJSON(data);
    for(var i=0;i<dec['file'].length;i++){
        create_media(dec['file'][i]);
    }

}
function create_media(file){
    code='<div class="post-mimg" link="'+file+'">';
    code+='<img src="'+create_thumb_link(create_media_link(file),{w:'120',h:'90'})+'" />';
    code+='<div class="post-mimgm">';
    code+='<span class="post-feat"></span>';
    code+='<span class="post-del"></span>';
    code+='<span class="post-url"></span>';
    code+='</div>';
    code+='</div>';
    $('.post-mlist .list-cont').append(code);
}
function create_media_link(link){
    return link.replace(/\.\.\//g,TXT_SITE_URL+'/');
}
function create_attach_link(link){
    return link.replace(/\.\.\//g,TXT_SITE_URL+'/').replace(/\/media/g,'').replace(/\.(jpg|jpeg|gif|png)/g,'/');
}
function create_thumb_link(link,thumb){
    var w=parseInt(thumb['w']);
    var h=parseInt(thumb['h']);
    var add_link=[],i=0;
    add_link[i++]=encodeURIComponent(link);
    if(!isNaN(w)){add_link[i++]='w='+w;}
    if(!isNaN(h)){add_link[i++]='h='+h;}

    return TXT_SITE_URL+'/thumb/?src='+add_link.join('&amp;');
}
function parse_load_data(e){
    if(e.lengthComputable){
        $('.load_data').text($('.load_data').text()+'<br />'+e.loaded+'-'+e.total);
    }
}
