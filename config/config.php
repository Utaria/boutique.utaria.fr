<?php
$config = array(
    "databases" => array(
        "dev" => array(
            "db_name" => "website",
            "db_user" => "root",
            "db_pass" => "root",
            "db_host" => "utaria.dev"
        )
    ),

    "database" => "dev",
    "template" => "default",

    // Modifiable depuis l'API
    "api_token" => "r8huBnSFK8qc4Kuq5Ke3ehgBDcm9HjAB8RLUuPkyqypzXGupbz",

    "title" => array(
        "template" => "Utaria | %s",
        "default"  => "Serveur minecraft innovant"
    ),

    "devMode"  => false,
    "useURI"   => true
);