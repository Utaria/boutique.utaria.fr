<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Code Promo</title>
  </head>
  <body>
    <form action="" method="post">
      <input type="text" name="promo" placeholder="ENTREZ VOTE CODE PROMO">
      <input type="submit" name="check" value="Valider">
    </form>
  </body>
</html>
<?php

$db = new PDO("mysql:host=localhost;dbname=website","root","root");

if(isset($_POST["check"])){
  if(!empty($_POST["promo"])){
    $promo_code = strtoupper($_POST["promo"]);

    $req = $db->prepare("SELECT * FROM shop_promos WHERE code=?");
    $req->execute(array($promo_code));
    $values = $req->fetch();

    if(strtotime($values["expiration_date"]) >= time()){
      echo "Good !";
    }else{
      echo "Euh...? :(";
    }

  }
}

?>
