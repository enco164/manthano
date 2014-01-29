<?php

    class Convert {
        public static function to_date($time) {
            return @date('d.m.Y.',$time);
        }
        public static function to_number($num){
            return number_format($num, 0, '.', '.');
        }



        public static function int2bin($num){

            $str="";
            $br=0;
            while($br<64){
                $c=$num%2;
                $num=(int)($num/2);
                $str=$str.$c;
                $br++;
            }
            return $str;


        }
        public static function bin2int($str){
            $num=0;
            for($br=63;$br>=0;$br--){
                $num=$num+pow(2,$br)*$str[$br];
            }

            return $num;
        }
    }

    function tool_redirect($pic,$pic_ext,$tool_type){
    return "<script>window.parent.open_crop('".$pic."','".$pic_ext."','".$tool_type."');</script>";
}

function addNotification($idParent, $body, $flag, $tip, $db){
    //activity
    $naslov = "";
    if($tip == 0){
        $temp = new Activity($idParent,$db);
        if($temp->exists()){
            $naslov = $naslov.$temp->Name()."  ";
        }
    }
    //event
    if($tip == 1){
        $temp = new Event($idParent,$db);
        if($temp->exists()){
            $naslov = $naslov.$temp->Name()."  ";
        }
    }
    $title = $naslov.$body;
    $stmt = $db->prepare("INSERT INTO notification(idParent, body, flag) values(:idp, :body, :flag)");
    $stmt->bindParam(":idp", $idParent, PDO::PARAM_INT);
    $stmt->bindParam(":body", $title, PDO::PARAM_STR);
    $stmt->bindParam(":flag", $flag, PDO::PARAM_INT);
    $stmt->execute();
    return ($stmt->rowCount() ? true : false);
}

function encode($text){
    $text=addcslashes($text,"%_");
    $text=htmlentities($text);
    $text=htmlspecialchars($text);
    $text=addslashes($text);
    $pattern=array(
        "/http(.*?)\/\/(.*?) /i",
    );
    $replace=array(
        "",

    );
    $text=preg_replace($pattern,$replace,$text);

    return $text;
}
function wrap($str, $width=35, $break="\n") {
    return preg_replace('#(\S{'.$width.',})#e', "chunk_split('$1', ".$width.", '".$break."')", $str);
}
function decode($text){
    $text=stripcslashes($text);
    $text=stripslashes($text);
    $text=htmlspecialchars_decode($text);
    $text=html_entity_decode($text);
    $text=wrap($text,50,"<br />");
    $pattern=array(
        "\n",
        "\r\n",
        "\r",
    );
    $replace=array(
        "",
        "",
        "",
    );

    $text=str_replace($pattern,$replace,$text);
    $pattern=array(
        "/\<(.*?)\>/i",
    );
    $replace=array(
        "",
    );

    $text=preg_replace($pattern,$replace,$text);

    return $text;
}
function create_salt(){
    $combos=array('$salt.=chr(mt_rand(48,57));','$salt.=chr(mt_rand(65,90));','$salt.=chr(mt_rand(97,122));');
    $salt=NULL;
    for($i=0;$i<5;$i++){
        eval($combos[array_rand($combos)]);
    }
    return $salt;
}

function is_explicit(){
    $CI=&get_instance();

    if($CI->post->post_data['exp']=='1'){ return TRUE;}
}
function is_home(){
    $CI=&get_instance();
    $sub_types=explode('|',config_item('allowed_sub_types'));

    if($CI->uri->rsegment(1)=='home' || $CI->uri->rsegment(1)=='home_paginate'){return TRUE;}
}

function is_banned(){
    $CI=&get_instance();
    return $CI->user->is_banned();
}


function logged_in($type='normal'){
    $CI=&get_instance();
    $logged_in=$CI->logged_in;

    if($type=='js'){$logged_in=$logged_in?'true':'false';}

    return $logged_in;
}

function is_admin(){
    if(logged_in()){
        $CI=&get_instance();
        $acc_type=$CI->user->user_data['acc_type'];
        if($acc_type==99 || $acc_type==100){return 1;}
    }
    return 0;
}

/*******************************/

/*******************************/
function get_userdata($what){
    $CI=&get_instance();
    return $CI->user->user_data[$what];
}

function cache_delete($key){
    $CI=&get_instance();
    $cache_prefix=config_item('cache_prefix');

    return $CI->cache->delete($cache_prefix.$key);
}

function cache_add($key,$value,$timespan){
    $CI=&get_instance();
    $cache_prefix=config_item('cache_prefix');

    return $CI->cache->save($cache_prefix.$key,$value,$timespan);
}

function cache_get($key){
    $CI=&get_instance();
    $cache_prefix=config_item('cache_prefix');

    return $CI->cache->get($cache_prefix.$key);
}

function add_error_handler_input($error, $id){
    if(!$error) return;

    echo '<script type="text/javascript">

            $("#'.$id.'").closest("li").addClass("error");

            $("#'.$id.'").on("mouseover", function(){

                 $(function(){
                    tooltip = $.pnotify({
                        text: "'.$error.'",
                        hide: false,
                        closer: false,
                        sticker: false,
                        history: false,
                        animate_speed: 100,
                        opacity: 1,
                        stack: false,
                        after_init: function(pnotify) {
                            pnotify.mouseout(function() {
                                pnotify.pnotify_remove();
                            });
                        },
                        before_open: function(pnotify) {
                            pnotify.pnotify({
                                before_open: null
                            });
                            return false;
                        }
                    });
                });

                tooltip.pnotify_display();


            });

            $("#'.$id.'").on("mousemove", function(event){
                tooltip.css({"top": event.clientY+12, "left": event.clientX-100});
            });

            $("#'.$id.'").on("mouseout", function(){
                tooltip.removeClass("down_arrow1");

                tooltip.pnotify_remove();
            });

        </script>';
}

function create_select_box($name,$options, $string="", $data=""){

    //$CI=&get_instance();
    if($string){
        $please_select['0']=$string;
        $options=$please_select+ $options;
    }

    echo form_dropdown($name, $options, set_value($name),"id=\"$name\" class=\"$name\" data-data=\"$data\"");
}

function create_select_box_repopulated($name,$options,$string="", $old_value=null){

    //$CI=&get_instance();
    if($string){
        $please_select['0']=$string;
        
        $options=$please_select+ $options;
    }
        if(set_value($name)!==""){
            $old_value=set_value($name);
        }
        echo "<select name=\"$name\" id=\"$name\">";
        if($options){
            foreach($options as $key=>$value){
                echo "<option value=\"$key\"".(($key==$old_value)?" selected":"").">$value</option>";
            }
        }
        echo "</select>";

}


function generate_search_mask_number($options_selected){
    $mask=str_repeat('0',64);
    if($options_selected) foreach($options_selected as $value){
        $mask[$value]=1;
    }
    //$mask[0]='0';
    return (int)Convert::bin2int($mask);
    //return $mask;
    //return base_convert($mask,2,10);
}

function base_base2dec($sNum, $iBase=0, $iScale=0) {
    $sChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $sResult = '';

    $iBase = intval($iBase); // incase it is a string or some weird decimal

    // special case for Base64 encoding
    if ($iBase == 64)
        $sChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    // clean up the input string if it uses particular input formats
    switch ($iBase) {
        case 16: // remove 0x from start of string
            if (strtolower(substr($sNum, 0, 2)) == '0x') $sNum = substr($sNum, 2);
            break;
        case 8: // remove the 0 from the start if it exists - not really required
            if (strpos($sNum, '0')===0) $sNum = substr($sNum, 1);
            break;
        case 2: // remove an 0b from the start if it exists
            if (strtolower(substr($sNum, 0, 2)) == '0b') $sNum = substr($sNum, 2);
            break;
        case 64: // remove padding chars: =
            $sNum = str_replace('=', '', $sNum);
            break;
        default: // Look for numbers in the format base#number,
            // if so split it up and use the base from it
            if (strpos($sNum, '#') !== false) {
                list ($sBase, $sNum) = explode('#', $sNum, 2);
                $iBase = intval($sBase);  // take the new base
            }
            if ($iBase == 0) {
                print("base_base2dec called without a base value and not in base#number format");
                return '';
            }
            break;
    }

    // Convert string to upper case since base36 or less is case insensitive
    if ($iBase < 37) $sNum = strtoupper($sNum);

    // Check to see if we are an integer or real number
    if (strpos($sNum, '.') !== FALSE) {
        list ($sNum, $sReal) = explode('.', $sNum, 2);
        $sReal = '0.' . $sReal;
    } else
        $sReal = '0';


    // By now we know we have a correct base and number
    $iLen = strlen($sNum);

    // Now loop through each digit in the number
    for ($i=$iLen-1; $i>=0; $i--) {
        $sChar = $sNum[$i]; // extract the last char from the number
        $iValue = strpos($sChars, $sChar); // get the decimal value
        if ($iValue > $iBase) {
            print("base_base2dec: $sNum is not a valid base $iBase number");
            return '';
        }
        // Now convert the value+position to decimal
        $sResult = bcadd($sResult, bcmul( $iValue, bcpow($iBase, ($iLen-$i-1))) );
    }

    // Now append the real part
    if (strcmp($sReal, '0') != 0) {
        $sReal = substr($sReal, 2); // Chop off the '0.' characters
        $iLen = strlen($sReal);
        for ($i=0; $i<$iLen; $i++) {
            $sChar = $sReal[$i]; // extract the first, second, third, etc char
            $iValue = strpos($sChars, $sChar); // get the decimal value
            if ($iValue > $iBase) {
                print("base_base2dec: $sNum is not a valid base $iBase number");
                return '';
            }
            $sResult = bcadd($sResult, bcdiv($iValue, bcpow($iBase, ($i+1)), $iScale), $iScale);
        }
    }
    return $sResult;
}

function resize_img($img,$ext,$max=600,$qua=100){
    $create_func['jpg'] = 'imagecreatefromjpeg';
    $create_func['jpeg']= 'imagecreatefromjpeg';
    $create_func['png'] = 'imagecreatefromjpeg';

    $output['jpg'] = 'imagejpeg';
    $output['jpeg'] = 'imagejpeg';
    $output['png'] = 'imagepng';

    $quality['jpg'] = round(100*$qua/100);
    $quality['jpeg'] = round(100*$qua/100);
    $quality['png'] = round(10*$qua/100);

    list($width,$height)=getimagesize($img);
    if($width>$max){
        $new_width=$max;
        $new_height=round(($new_width/$width)*$height);
        $image=$create_func[$ext]($img);
        $sample=imagecreatetruecolor($new_width,$new_height);
        imagecopyresampled($sample,$image,0,0,0,0,$new_width,$new_height,$width,$height);
        $output[$ext]($sample,$img,$quality[$ext]);
    }
}

function get_all_option_names(){
    $CI=&get_instance();
    $all_inputs['vehicle_location']=$CI->data_model->get_locations();
    $conf=config_item('input_options');
    $option_groups=$conf['option_groups'];
    foreach($option_groups as $group_name){

        $group=$conf['global_'.$group_name];
        foreach($group as $input){
            $all_inputs[$input['name']]=$input['value'];
        }
    }
return $all_inputs;
}

function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}


function sort_results(&$list,$sorting=NULL){
    if($sorting==5){
        $scale=config_item('credits_scale_lower_bound');
        //print_r($list);
        if($list)foreach($list as $key=>$value){
            $list[$key]['promotion']=ceil($list[$key]['promotion']*$scale);
        }
        @usort($list, function($a, $b) {
            return $b['promotion'] - $a['promotion'];
        });
    }
    return $list;

}

    function parse_permalink($name,$req=false){
        $pattern=array(" ","\t","\r\n","ž","š","đ","č","ć");
        $replace=array("+","+","+","z","s","d","c","c");

        $name=str_replace($pattern,$replace,strtolower($name));
        if($req){
            $pattern=array("-");
            $replace=array("+");
            $name=str_replace($pattern,$replace,strtolower($name));
        }else{
            $pattern=array("-");
            $replace=array("");
            //echo '['.$name.']';
            $name=str_replace($pattern,$replace,strtolower($name));
            //echo '['.$name.']';
        }
        //echo '['.$name.']';
        $exp=explode("+",$name);
        $exp=array_filter($exp,function($val){
            if(strlen(trim($val))==0){return FALSE;}else{return TRUE;}
        });
        $name=implode('+',$exp);
        //echo '['.$name.']';
        $pattern=array(
            "/[^a-zA-Z0-9\+\s]/i",
            "/\+/i",
        );
        $replace=array(
            "",
            "-",
        );

        return preg_replace($pattern,$replace,strtolower($name));
    }

    function limit_text($data,$limit=160){
        $max=$limit;$num=0;
        $data=strip_tags($data);
        if(strlen($data)<=$max){return $data;}
        $exp=explode(" ",$data);
        foreach($exp as $word){
            $desc[]=$word;
            if(strlen(implode(" ",$desc))>$max){unset($desc[count($desc)-1]);break;}
        }
        //if(!preg_match("[^a-zA-Z0-9\+\s]",$desc[count($desc)-1][strlen($desc[count($desc)-1])-1])){}
        if(in_array($desc[count($desc)-1][strlen($desc[count($desc)-1])-1],array(',','.'))){$desc[count($desc)-1][strlen($desc[count($desc)-1])-1]='';}
        //if(!preg_match("[^a-zA-Z0-9\+\s]",$desc[count($desc)-1])){unset($desc[count($desc)-1]);}
        return trim(implode(" ",$desc)).'...';
    }


    class Encryption {
        public $skey = "GBy3ki12hUrscnYuTqGVnDt2BOFKZCyH"; // change this

        public  function safe_b64encode($string) {
            $data = base64_encode($string);
            $data = str_replace(array('+','/','='),array('-','_',''),$data);
            return $data;
        }

        public function safe_b64decode($string) {
            $data = str_replace(array('-','_'),array('+','/'),$string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }
            return base64_decode($data);
        }

        public  function encrypt($value){
            if(!$value){return false;}
            $text = $value;
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
            return trim($this->safe_b64encode($crypttext));
        }

        public function decrypt($value){
            if(!$value){return false;}
            $crypttext = $this->safe_b64decode($value);
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
            return trim($decrypttext);
        }
    }

