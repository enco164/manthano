<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

<?php
include_once('Proposal.php');

$db = new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$db->exec("use manthanodb");

$pred = new Proposal(17, $db);
echo $pred."<br>";

echo $pred->idProposal()."<br>";
echo $pred->UserProposed()."<br>";
echo $pred->Name()."<br>";
echo $pred->Description()."<br>";

print_r($pred->getFullInfo());
print_r($pred->getSupport());
echo "<br>Podrzava: ".$pred->getSupportCount()."<br>";
print_r($pred->getOwners());
echo "<br>Owners: ".$pred->getOwnerCount()."<br>";

echo "*****UPDATE*****"."<br>";
print_r($pred->getFullInfo());
$pred->setDescription("Novi Descript2");
$pred->setName("NewName33333");
$pred->updateProposal();
print_r($pred->getFullInfo());

echo "<br>*****STATIC*****"."<br>";
print_r(Proposal::getSupportS($db, 17));
echo "<br>";
print_r(Proposal::getSupportCountS($db, 17));
echo "<br>";
print_r(Proposal::getOwnersS($db, 17));
echo "<br>";
print_r(Proposal::getOwnerCountS($db, 17));
echo "<br>";

//echo Proposal::deleteProposal($db, 21);
//Proposal::addProposal($db, 3, "Trece ime", "Predlog neki eeerrreererererer");

//$pred1 = new Proposal(18, $db);
//echo $pred1;

echo Proposal::deleteSupport($db, 23, 17);
echo Proposal::addSupport($db, 23, 17);
//Proposal::addSupport($db, 21, 17);

echo Proposal::deleteOwner($db, 1, 17, 1);
echo Proposal::addOwner($db, 1, 17, 1);
echo Proposal::deleteOwner($db, 1, 17, 3);
echo Proposal::addOwner($db, 1, 17, 3);
//Proposal::addOwner($db, 1, 17, 2);
?>
</body>
</html>