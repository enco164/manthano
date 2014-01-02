
<!---------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------->
<!--------------------------------------------NAVBAR-------------------------------------------->
<!---------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------->
<?session_start();?>
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
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="/home">Home</a></li>
				<li class="active"><a href="news feed.html">News feed - logged</a></li>

			</ul>

			<form class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>

			<ul class="nav navbar-nav navbar-right">
				<li><a href="/logout">Log out</a></li>


				<li><a href="profile.html"><span class="glyphicon glyphicon-user"></span> <?=$_SESSION['name']?> <?=$_SESSION['surname']?> </a></li>
				<!--
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">External login <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#">Google</a></li>
						<li><a href="#">Facebook</a></li>
					</ul>
				</li>
			-->
		</ul>
	</div><!-- /.navbar-collapse -->
</div>
</nav>



<div class="container" >


	<div class="row" >

		<div class="col-md-2" style="top: 75px;">
			<ul class="nav nav-pills nav-stacked">
				<li class=""><a href="#">Opcija 1</a></li>
				<li><a href="#">Opcija 2</a></li>
				<li><a href="#">Opcija 2</a></li>
			</ul>
		</div>

		<!--CONTENT-->
		<div class="col-md-10" role="main" style="top: 75px;">
			<!--path-->
			<div class="row">
				<ol class="breadcrumb">
					<li ng-repeat="location in activity.path"><a href="#/activity/{{location.idActivity}}">{{location.Name}}</a></li>
				</ol>
			</div>


			<div class="row">
				<div class="jumbotron">
					
					<h1>{{activity.name}}</h1>
					<img ng-src="{{activity.cover}}"  alt="{{activity.cover}}">
					<p>
						{{activity.description}}
					</p>
					<p>Nastalo: {{activity.date}}</p>
					<p>
						<a class="btn btn-primary btn-large">Prijavi me</a>
					</p>
					
				</div>	
			</div>




			<div class="row">

				<div class="col-md-4 col-lg-4" ng-repeat="son in activity.sons">
					<div class="thumbnail">
						<img ng-src="{{son.cover}}" alt="{{son.cover}}">
						<div class="caption">
							<a  href="#/activity/{{son.idActivity}}"><h3>{{son.Name}}</h3></a>
						</div>
					</div>
				</div>
			</div>

		</div><!--content-->
	</div><!--row-->
</div><!--container-->








