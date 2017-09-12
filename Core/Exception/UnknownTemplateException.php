<?php
namespace Core\Exception;

class UnknownTemplateException extends Exception {

    protected $_message = "Le template %s n'a pas été trouvé.";

}