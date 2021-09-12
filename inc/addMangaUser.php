<?php
require_once('../core/define.php');
// check if user connected !!!

if(!isset($_GET['id']) || !is_numeric($_GET['id']))
    return false;

if(!isset($_GET['action']) || ($_GET['action'] != 'add' && $_GET['action'] != 'remove'))
    return false;

$id = $_GET['id'];
$action = ($_GET['action'] == 'add')?true:false;

$userObj = new User(6);

echo json_encode($userObj->addMangaLibrary($id, $action));