    <?php
 
      session_start();
 

 
      $db = new PDO("mysql:host=localhost;dbname=website","root","root");
 

 
      $articles = array();
 

 
      $req = $db->query("SELECT * FROM shop_articles");
 

 
      while($all = $req->fetch())
 
      {
 
        $articles[] = $all;
 
      }
 

 
      if(isset($_SESSION["cart"])){
 

 
        foreach($articles as $article)
 
        {
 
          if(!in_array($article["id"], $_SESSION["cart"])) continue;
 
          echo $article["name"]."<br>";
 
        }
 

 
      }
 
    ?>
 
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="style.css">
		<title>Shop</title>
	</head>
	<body>
		<div class="items-container"></div>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="app.js"></script>
	</body>
</html>