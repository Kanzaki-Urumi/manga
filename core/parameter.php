<?php
date_default_timezone_set('Europe/Paris');

// ------------------- APPEL DES CLASS ------------------
require_once _API_."gettext/autoloader.php";
require_once _CLASS_."classLanguage.php";
require_once _CLASS_."classDatabase.php";


// ------------------- GESTION LANGUES ------------------
$obj_langue = new Language('fr_FR');
$obj_langue->getTad();

// ------------------- DATABASE -------------------------
$DB = new Db("localhost", "root", "", "manga");
