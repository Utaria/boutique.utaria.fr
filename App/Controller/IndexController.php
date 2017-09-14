<?php
namespace app\Controller;

use Core\Controller\Controller;

class IndexController extends Controller {

    protected $useTable = false;


    public function index() {
        $this->render("index");
    }

}