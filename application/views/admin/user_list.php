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
    <link rel="stylesheet" href="/assets/scripts/footable/css/footable.core.css?t=0.1" type="text/css" />
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

<?
    $user_statuses=$this->config->item('user_statuses');
    $user_accounts=$this->config->item('user_accounts');
    $user_statuses_classes=array('0'=>'yellow_txt','1'=>'green_txt','2'=>'red_txt');
?>
<div class="wrap">
    <!--<div class="admin_nav">
        <?php
/*            $this->load->view('admin/admin_nav');
        */?>
    </div>-->
    <div class="list_view">
        <div class="my_user_list">
            <?=form_open("")?>
            <div class="above_table">
                <!--<div class="above_select">
                    <select name="" id="sort_users">
                        <option value="">Sortiraj po</option>
                    </select>
                </div>
                <div class="modify_btn">Izmeni</div>
                <div class="erase_btn">Obriši</div>
                <div class="clear"></div>-->
            </div>

            <div class="admin_user_list">
                <div class="input_filter">
                    <input type='text' id="filter" placeholder="Pretraga">
                </div>
                <table class="footable" data-filter="#filter" data-page-size="30">
                    <thead>
                        <tr>
                            <!--<th class="check">
                                <input type="checkbox" class="check_all">
                            </th>-->
                            <th>
                                Ime i prezime
                            </th>
                            <th>
                                Korisničko ime
                            </th>
                            <th>
                                Email
                            </th>

                            <!--<th>
                                Potvrđeno
                            </th>-->
                            <th>
                                Tip korisnika
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Izmeni
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                            foreach($users as $user){
                            //echo json_encode($user);
                            echo "<tr>";
                                /*echo "<td class='check'><input type='checkbox' class='check'></td>"*/
                                echo "<td>";
                                echo $user['Name']." ".$user['Surname'];
                                echo "</td>";
                                echo "<td>";
                                echo $user['username'];
                                echo "</td>";
                                echo "<td>";
                                echo $user['mail'];
                                echo "</td>";

                                /*echo "<td>";
                                echo $user['email_status'];
                                echo "</td>";*/
                                echo "<td>";
                                echo $user_accounts[$user['acc_type']]['name'];
                                echo "</td>";
                                echo "<td class=".$user_statuses_classes[$user['status']].">";
                                echo $user_statuses[$user['status']];
                                echo "</td>";
                                echo "<td class='blue_txt2'>";
                                //echo '<a href="/admin/users/edit_user/'.$user['user_id'].'" >Izmeni</a>';
                                echo '<a href="/#/user/'.$user['user_id'].'" >Izmeni</a>';
                                echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="pagination pagination-centered" data-page-navigation=".pagination">

                                <div class="clear">
                                </div>
                            </div>

                        </td>
                    </tr>

                    </tfoot>
                </table>

            </div>
            <div class="clear">

            </div>
            <div class="above_table">
                <!--<div class="above_select">
                    <select name="" id="sort_users">
                        <option value="">Sortiraj po</option>
                    </select>
                </div>
                <div class="modify_btn">Izmeni</div>
                <div class="erase_btn">Obriši</div>
                <div class="clear"></div>-->
            </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/scripts/footable/footable.js?t=0.1"></script>
<script src="/assets/scripts/footable/footable.sort.js?t=0.1" type="text/javascript"></script>
<script src="/assets/scripts/footable/footable.filter.js?t=0.1" type="text/javascript"></script>
<script src="/assets/scripts/footable/footable.paginate.js?t=0.1" type="text/javascript"></script>

<script type="text/javascript">
    $(function () {
        $('.footable').footable();
    });
</script>
</body>
</html>