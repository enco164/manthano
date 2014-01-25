<div class="jumbotron">
    <div class="container">
        <h1>
            <ul class="list-inline">
                <a href="index.php">

                    <img src="/assets/img/manthano-header.png" class="img-responsive " alt="Manthano">
                    <!--Manthano -->
                </a>
            </ul>
        </h1>

    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Zaboravili ste lozinku?</h3>
                </div>
                <div class="panel-body">
                 <div class="login_form">
                    <?= form_open('/user/forgot_password');?>

                    <div class="form-group">
                        <label for="Mail">Vaša email adresa</label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>

                            <input type="email" class="form-control" name="email" required />
                        </div>
                    </div>
                    <?=form_error('email');?>
                    <!--<div class="pass log_btn space js_submit">Pošalji</div>-->
                        <input type="submit" class="btn btn-success pull-right" value="Pošalji"></div>
                        <span class="blue_txt"><?=$message?></span>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!--
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
                -->
                    <!--<div class="pass log_btn space js_submit">Pošalji</div>-->
                <!--
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
-->