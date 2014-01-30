<!DOCTYPE html>
<html ng-app="manthanoApp">
<head>
	<title>Manthano</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="icon" href="/assets/img/favicon.png" type="image/png" sizes="16x16" />
	<!-- Bootstrap CSS -->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="/assets/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/assets/css/popup.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/assets/css/alertify.bootstrap.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/assets/css/alertify.core.css" type="text/css" media="screen" />

	<link rel="stylesheet" href="/assets/scripts/ui/jquery-ui-1.10.3.custom.css" type="text/css" media="screen" />
	<link rel="canonical" href="" />
	<script src="/assets/scripts/jq.js"></script>
	<script src="/assets/scripts/bootstrap.min.js"></script>
    <script src="/assets/scripts/socket.io.js"></script>
    <script src="/assets/scripts/alertify.js"></script>
	<script src="/assets/scripts/ui/jquery-ui-1.10.3.custom.js"></script>
	<script src="/assets/scripts/main.js"></script>
	<script src="/assets/scripts/angular.min.js"></script>
	<script src="/assets/scripts/angular-route.min.js"></script>
	<script src="/assets/scripts/routeModule.js"></script>
	<script src="/assets/scripts/userModule.js"></script>
	<script src="/assets/scripts/eventModule.js"></script>
	<script src="/assets/scripts/proposalModule.js"></script>
	<script src="/assets/scripts/materialModule.js"></script>
	<script src="/assets/scripts/activityModule.js"></script>
    <script src="/assets/scripts/media_upload.js?t=0.1"></script>
	<script type="text/javascript">
	var globalUID = <?=$this->session->userdata('user_id')?>;
	var globalAccType = <?=$this->session->userdata('acc_type')?>;
    var globalPrljavo = 0;
	</script>

</head>
<body>
<?//var_dump(is_admin());?>

    <?// ovo ti je klasa navbara al me zezalo nesto pa
    // pa sam ga zakomentarisao
    //class="navbar navbar-default navbar-fixed-top" ?>
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Manthano</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
				<ul class="nav navbar-nav">
					<li><a href="/home">Home</a></li>
					<!-- Jupi ovde setujes link do liste predloga :) -->
    				
					<li><a href="#/proposals"> Predlozi </a></li>
					<?php
						if (is_admin()){
							?>
					<li class="active"><a href="/admin/home/user_list">Upravljanje korisnicima</a></li>
							<?php
						}
					?>

				</ul>

				<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>

				<ul class="nav navbar-nav navbar-right">

					<li><a href="#/user/<?=$this->session->userdata('user_id')?>">
						<span class="glyphicon glyphicon-user"></span> 
	    					<?=$this->session->userdata('name');?> 
	    					<?=$this->session->userdata('surname');?> 
						</a>
					</li>	
					<li>
						<a href="/logout">
							<span class="glyphicon glyphicon-off"></span> 
							Izloguj se
						</a>
					</li>
					<!--
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">External login <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">
								<span class="glyphicon glyphicon-off"></span> 
								Izloguj se
							</a>
						</li>
					</ul>
				</li>
				-->
				</ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>

<br/><br/><br/><br/>
    <div ng-controller="test123">
<!--      ostaviti ovaj div, ne dirati ni pod tackom razno-->
    </div>

<!-- Ova cetri br iznad su brzi hack da bi se video sadrzaj lepo, ne dirati ih!!! -->

<div class="container">
	<div class="row">
		<div class="alert alert-warning alert-dismissable">
			<strong>Obaveštenje!</strong> Manthano je trenutno u fazi razvoja! Hvala na razumevanju!
		</div>
	</div>
</div>

<div ng-view>
  

</div> 

<?$stefan="STEFAN!"?>
</body>
</html>
