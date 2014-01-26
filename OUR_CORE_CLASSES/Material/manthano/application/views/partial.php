
<!---------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------->
<!--------------------------------------------NAVBAR-------------------------------------------->
<!---------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------->
<?session_start();?>




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
