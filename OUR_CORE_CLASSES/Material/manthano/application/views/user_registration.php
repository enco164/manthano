<div class="pop_up">
    <div class="pop_up_header">
        <h3>Registracija</h3>
        <div class="close_btn"></div>
    </div>
    <?=form_open('register/validate_user_registration')?>
    <ul>
        <li>
            <label>Ime</label>
            <input type="text" name="first_name" value="<?=set_value('first_name');?>"><?if(form_error('first_name')){echo('<span class="red">*</span>
                <div class="clear"></div>
                <p class="error_txt">'.form_error('first_name').'</p>');}?>
            <? if(form_error('first_name')){add_error_handler_input(form_error('first_name'), 'first_name');}?>

        </li>
        <li>
            <label>Prezime</label>
            <input type="text" name="last_name" value="<?=set_value('last_name');?>"><?if(form_error('last_name')){echo('<span class="red">*</span>
                <div class="clear"></div>
                <p class="error_txt">'.form_error('last_name').'</p>');}?>
            <? if(form_error('last_name')){add_error_handler_input(form_error('last_name'), 'last_name');}?>

        </li>
        <div class="clear"></div>
        <li>
            <label>Korisničko ime</label>
            <input type="text" name="username" value="<?=set_value('username');?>"><?if(form_error('username')){echo('<span class="red">*</span>
                <div class="clear"></div>
                <p class="error_txt">'.form_error('username').'</p>');}?>
            <? if(form_error('username')){add_error_handler_input(form_error('username'), 'username');}?>

        </li>
        <li>
            <label>Email</label>
            <input type="text" name="email" value="<?=set_value('email');?>"><?if(form_error('email')){echo('<span class="red">*</span>
                <div class="clear"></div>
                <p class="error_txt">'.form_error('email').'</p>');}?>
            <? if(form_error('email')){add_error_handler_input(form_error('email'), 'email');}?>

        </li>
        <div class="clear"></div>
        <li>
            <label>Lozinka</label>
            <input type="password" name="password"><?if(form_error('password')){echo('<span class="red">*</span>
                <div class="clear"></div>
                <p class="error_txt">'.form_error('password').'</p>');}?>
            <? if(form_error('password')){add_error_handler_input(form_error('password'), 'password');}?>

        </li>
        <li>
            <label>Potvrda lozinke</label>
            <input type="password" name="password_repeat"><?if(form_error('password_repeat')){echo('<span class="red">*</span>
                <div class="clear"></div>
                <p class="error_txt">'.form_error('password_repeat').'</p>');}?>
            <? if(form_error('password_repeat')){add_error_handler_input(form_error('password_repeat'), 'password_repeat');}?>

        </li>
        <div class="clear"></div>
    </ul>
    <div class="capcha_box" id="recapcha"><script></script></div>
    <? if(form_error('recaptcha_response_field')){ echo ('<p class="error_txt_captcha">'.form_error('recaptcha_response_field').'</p>');}?>
    <? if(form_error('val_recaptcha')){ echo ('<p class="error_txt_captcha">'.form_error('val_recaptcha').'</p>');}?>
    </form>
    <?//echo validation_errors();
    //echo form_error('recaptcha_response_field');
    //echo form_error('val_recaptcha');
    ?>
    <div id="user_reg" class="register_btn">Registruj se</div>
</div>


<script type="text/javascript" src="/assets/scripts/recaptcha_ajax.js"></script>
<script>
    $(document).ready(function () {
        function showRecaptcha(element) {
            Recaptcha.create("<?=$this->config->item('recaptcha_public_key');?>", element, {
                theme: "clean",
                custom_translations : { instructions_visual : "Popunite polje rečima sa slike" },
                callback: Recaptcha.focus_response_field});
        }

        showRecaptcha('recapcha');
    });
</script>