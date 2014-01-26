<div class="wrap">
    <div class="login">
        <div class="login_form">
            <?php echo form_open('/user/forgot_password/change_password/'.$hash);
            ?>
            <ul>
                <li>
                    <label>Šifra</label>
                    <input type="password" name="password" id="password">
                    <?add_error_handler_input(form_error('password'), 'password')?>
                </li>
                <li>
                    <label>Ponovite šifru</label>
                    <input type="password" name="password_repeat" id="repeat_password">
                    <?add_error_handler_input(form_error('password_repeat'), 'repeat_password')?>
                </li>
                <li>
                    <div class="pass">
                        <input type="submit" name="submit" value="Submit"/>
                    </div>
                </li>
                <li>
                    <span class="blue"><?=$message?></span>
                </li>
            </ul>
            </form>
        </div>
    </div>
</div>