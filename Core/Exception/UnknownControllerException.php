<?php
namespace Core\Exception;

class UnknownControllerException extends Exception {

    protected $_message = "Le Controlleur %s n'a pas été trouvé.";

}