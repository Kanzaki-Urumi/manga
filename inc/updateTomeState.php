<?php
require_once('../core/define.php');

// Check if user connected !!!

$userObj = new User(6);
$idTome = clean_for_bdd($_GET['id']);
$state = clean_for_bdd($_GET['state']);
var_dump($userObj->changeStateTome($idTome, $state));



