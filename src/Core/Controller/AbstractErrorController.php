<?php
namespace Core\Controller;

abstract class AbstractErrorController extends Controller {

    protected $useTable = false;


    public abstract function error404();
    public abstract function error500();

}