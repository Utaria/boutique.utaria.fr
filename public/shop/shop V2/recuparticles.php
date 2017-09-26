<?php

$db = new PDO("mysql:host=localhost;dbname=website","root","root");

$articles = array();

$req = $db->query("SELECT * FROM shop_articles ORDER by id DESC");

while($all = $req->fetch())
{
	$articles[] = $all;
}

foreach($articles as $article)
{
	?>
		<div class="item">
			<div class="top">
				<div class="price"><?php echo number_format($article["price"],2); ?></div>
				<div class="add" data-id="<?php echo $article["id"]; ?>"><i class="fa fa-shopping-cart"></i></div>
			</div>
			<div class="content"><img src="<?php echo $article["img"]; ?>"></div>
			<div class="bottom"><?php echo $article["name"]; ?></div>
		</div>
	<?php
}

?>