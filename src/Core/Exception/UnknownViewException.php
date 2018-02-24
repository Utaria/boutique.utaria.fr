<?php
namespace Core\Exception;

class UnknownViewException extends Exception {

    protected $_message = "La Vue %s n'a pas été trouvée.";

}