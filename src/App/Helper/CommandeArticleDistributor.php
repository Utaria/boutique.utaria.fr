<?php
namespace App\Helper;

use App\Entity\CommandeArticleEntity;
use App\Helper\Cart\SessionUser;

class CommandeArticleDistributor {

    private function __construct() {

    }

    /**
     * Distribue un article à un joueur en particulier
     * @param $user SessionUser
     * @param $article CommandeArticleEntity
     */
    public static function distribute($user, $article) {
        /*var_dump($user);
        var_dump($article);
        die();*/
    }

}