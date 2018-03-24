<?php
namespace App\Controller;

use Core\Controller\Controller;

class FormuleController extends Controller {

    protected $useTable = false;

    public function index() {
        $this->render("formules");
    }

    public function creation($params) {
        $this->render("formule.creation");
    }

}