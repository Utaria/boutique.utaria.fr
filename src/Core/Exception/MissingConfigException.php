<?php
namespace Core\Exception;

class MissingConfigException extends Exception {

    protected $_message = "Le fichier de configuration config/config.php n'existe pas !";

}