<?php
setLocale(LC_ALL, "fr_FR.utf8");

require '../../vendor/autoload.php';
require '../App/App.php';

$app = App::getInstance();

use Core\Routing\Router;

// Redirections d'articles
Router::connect("survie", "article/survie");

Router::connect("commande/:id", "commande/view/id:([0-9]+)");

$app->load();