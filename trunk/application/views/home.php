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
    <link rel="stylesheet" href="/assets/scripts/ui/jquery-ui-1.10.3.custom.css" type="text/css" media="screen" />
    <link rel="canonical" href="" />
    <script src="/assets/scripts/jq.js"></script>
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
    <script type="text/javascript">
        var globalUID = <?=$this->session->userdata('user_id')?>;
        var globalAccType = <?=$this->session->userdata('acc_type')?>;
    </script>

</head>
<body>

    <?// ovo ti je klasa navbara al me zezalo nesto pa
    // pa sam ga zakomentarisao
    //class="navbar navbar-default navbar-fixed-top" ?>
<nav  role="navigation">
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

                <!-- Jupi ovde setujes link do liste predloga :) -->
                <li><a href="#/proposals/"> Predlozi </a></li>
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


                <li><a href="#/user/<?=$this->session->userdata('user_id')?>"><span class="glyphicon glyphicon-user"></span> <?=$this->session->userdata('name');?> <?=$this->session->userdata('surname');?> </a></li>
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
    <br/>
        <h2> Manthano je trenutno u fazi razvoja! Hvala na razumevanju! </h2>
    <br/>

<div ng-view></div>

<?$stefan="STEFAN!"?>
<body>
</html>
