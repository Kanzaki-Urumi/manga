<?php
date_default_timezone_set('Europe/Paris');

// ------------------- APPEL DES CLASS ------------------
require_once "functions/function.php";
require_once _API_."gettext/autoloader.php";
require_once _CLASS_."classLanguage.php";
require_once _CLASS_."classDatabase.php";
require_once _CLASS_."classReference.php";
require_once _CLASS_."classMangaTome.php";
require_once _CLASS_."classManga.php";
require_once _CLASS_."classUser.php";


// ------------------- GESTION LANGUES ------------------
$obj_langue = new Language('fr_FR');
$obj_langue->getTad();

// ------------------- DATABASE -------------------------
$PDO = new Database();

