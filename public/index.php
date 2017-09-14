<?php
setLocale(LC_ALL, "fr_FR.utf8");

require '../App/App.php';

$app = App::getInstance();
$app->load();