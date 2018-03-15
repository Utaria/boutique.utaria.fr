<?php
namespace App\Helper\Cart;

class SessionUser {

    /**
     * @var integer Identifiant du joueur
     */
    private $id;

    /**
     * @var string Nom du joueur
     */
    private $playername;

    /**
     * @var string Identifiant unique du joueur en jeu
     */
    private $uuid;

    public function __construct($id, $playername, $uuid) {
        $this->id = $id;
        $this->playername = $playername;
        $this->uuid = $uuid;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPlayername()
    {
        return $this->playername;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

}