<?php
setLocale(LC_ALL, "fr_FR.utf8");

require '../App/App.php';

$app = App::getInstance();

use \Core\Routing\Router;

Router::connect("commande/:id", "commande/view/id:([0-9]+)");

$app->load();