<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

    <?php
        include_once('DB.php');

    $db = DB::singleton();

    $db1 = DB::singleton();
    echo $db1->execQuery("Select * from Proposal");

    ?>

</body>
</html>