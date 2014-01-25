<div class="wrap">
    <div class="login">
        <div class="login_title">
            <h3>Zaboravili ste lozinku</h3>
            <div class="cnt_separator"></div>
        </div>
        <div class="login_form">
            <?= form_open('/user/forgot_password');?>
            <ul>
                <li>
                    <label>Vaša email adresa</label>
                    <input type="text" name="email" id="email">
                    <?=form_error('email');?>
                </li>
                <li>
                    <!--<div class="pass log_btn space js_submit">Pošalji</div>-->
                    <input type="submit" value="Pošalji"></div>
                </li>
                <li>
                    <span class="blue_txt"><?=$message?></span>
                </li>
            </ul>
            </form>
        </div>
    </div>
</div>