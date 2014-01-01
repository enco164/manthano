<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

    <?php
        include_once('Material.php');

        $db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $db->exec("use manthanodb");
        $kurs = new Material(200,$db);
        echo $kurs->Name();
        $kurs->Name = "Table";
        echo $kurs->update();



    ?>

</body>
</html>