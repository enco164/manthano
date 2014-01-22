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

    <div class="panel panel-default">
        <div class="panel-body">
            
            <?php 
            $val_err = validation_errors();
            if ($val_err){
            ?>
            <div class="alert alert-danger" style="text-align:center"> <?php echo $val_err; ?> </div>
            <?php
            }
            ?> 

            <div class="row">

                <div class="col-md-6 form-group">

                    <legend>Logovanje</legend><!--
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <a class="btn btn-block btn-social btn-facebook">
                    <i class="fa fa-facebook"></i> Facebook logovanje
                </a>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <a class="btn btn-block btn-social btn-google-plus">
                    <i class="fa fa-google-plus"></i> Google logovanje
                </a>

            </div>
        </div>

        <div class="login-or">
            <hr class="hr-or">
            <span class="span-or">ili</span>
        </div>
    -->
    <?=$this->load->helper('form');?>

    <?=form_open('login');?>
    <div class="form-group">
        <label for="inputUsernameEmail">email</label>
        <div class="input-group">
            <span class="input-group-addon">@</span>

            <input type="username" name="name" class="form-control" id="inputUsernameEmail" required>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword">Lozinka</label>
        <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            <input type="password" name="pass" class="form-control" id="inputPassword" required>
        </div>
    </div>
        <button type="submit" class="btn btn-primary js_submit">
            Uloguj me
        </button>
        <a class="" href="#">Zaboravili ste lozinku?</a>
		<div class="checkbox pull-right">
			<label>
            <input type="checkbox"> Zapamti me 
			</label>
		</div>

    </form>


</div>

<div class="col-md-6">
    <!--<form action="onRegister.html" method="POST" role="form" class="form-group">-->
    <?=form_open('register/test_register');?>
    <legend>Registracija</legend>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name-input">Ime</label>
                <input type="text" class="form-control" name="name" id="name-input" placeholder="npr. Pera" value="<?=set_value('name');?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="surname-input">Prezime</label>
                <input type="text" class="form-control" name="surname" id="surname-input" placeholder="npr. Perić" value="<?=set_value('surname');?>" required>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="username-input">Username</label>
        <input type="text" class="form-control" name="username" id="username-input" placeholder="jedinstveno korisničko ime" value="<?=set_value('username');?>" required>
    </div>
    
    <div class="form-group">
        <label for="email-input">email</label>
        <input type="email" class="form-control" name="mail" id="email-input" placeholder="npr. pera.peric@matf.bg.ac.rs" value="<?=set_value('mail');?>" required>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="password-input">Lozinka</label>
                <input type="password" class="form-control" name="password" id="password-input" placeholder="min 6 karaktera" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group ">
                <label for="password-confirm-input">Ponovi lozinku</label>
                <input type="password" class="form-control" name="password_repeat" id="password-confirm-input" placeholder="ponovi lozinku" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="proffession-input">Zanimanje</label>
        <input type="text" class="form-control" name="proffession" id="proffession-input" placeholder="Zanimanje" value="<?=set_value('profession');?>" >
    </div>
    <div class="form-group">
        <label for="school-input">Škola</label>
        <input type="text" class="form-control" name="school" id="school-input" placeholder="OS Sveti Sava" value="<?=set_value('school');?>" >
    </div>
    <div class="form-group">
        <label for="www-input">Sajt</label>
        <input type="text" class="form-control" name="www" id="www-input" placeholder="www.mojsajt.com" value="<?=set_value('www');?>" >
    </div>
    <button type="submit" class="btn btn-primary pull-left">Registruj me</button>
</form>

</div>

</div>
</div>

<div class="panel-footer">
    &copy; Matematički fakultet u Beogradu 2013
</div>
</div>

</div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery.js"></script>
<!-- Bootstrap JavaScript -->
<script src="/assets/scripts/bootstrap.min.js"></script>
</body>
</html>
