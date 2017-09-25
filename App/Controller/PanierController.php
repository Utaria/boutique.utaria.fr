<?php
namespace App\Controller;

use Core\Controller\Controller;

class PanierController extends Controller {

	protected $useTable = false;


	public function index(){
		$this->render("panier");
	}

}