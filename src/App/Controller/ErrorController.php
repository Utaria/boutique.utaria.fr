<?php
namespace App\Controller;

use Core\Controller\AbstractErrorController;

class ErrorController extends AbstractErrorController {

    public function error404(){
        $this->render("error.404");
    }

    public function error500(){
        // On ne fait rien.
        // Pour l'instant la méthode n'est jamais appelée.
    }

}