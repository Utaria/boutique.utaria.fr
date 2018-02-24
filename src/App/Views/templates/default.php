<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="Utaria, les serveurs de demain ! Marre du survie classique de Minecraft ? Venez tester notre survie UNIQUE sur mc.utaria.fr !">
	<meta name="keywords" content="minecraft,serveur minecraft,serveur,survie unique,unique,original,nouveau,survival">
	<meta name="author" content="Utaria">
	<meta name="dcterms.rightsHolder" content="utaria">
	<meta name="Revisit-After" content="2 days">
	<meta name="Rating" content="general">
	<meta name="language" content="fr-FR" />
	<meta name="robots" content="all" />
	<meta charset="UTF-8">

	<title><?= $pageTitle ?></title>

	<meta name="viewport" content="width=device-width, initial-scale = 1, user-scalable = no">

	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@Utaria_FR">
	<meta name="twitter:title" content="Utaria, les serveurs de demain !">
	<meta name="twitter:description" content="Utaria, un serveur Minecraft innovant.">
	<meta property="og:title" content="Utaria">
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://utaria.fr/">

	<link rel="icon" type="image/png" href="<?= $Html->srcImg("favicon.png") ?>" />

	<?= $Html->css("https://fonts.googleapis.com/css?family=Lato:400,700,900|Open+Sans:400,700,900") ?>
	<?= $Html->css("fa") ?>
	<?= $Html->css("grid") ?>
	<?= $Html->css("style") ?>
	<?= $Html->css("boutique") ?>
</head>
<body>
	<header class="header">
		<section class="upper">
			<div class="wrap-inner">
				<div class="logo">
					<?= $Html->link("", $Html->img("logo.png")) ?>
				</div>
				<div class="players">
					Bienvenue dans notre <span>boutique</span> !
				</div>
				<nav class="navigation-header">
					<?php
                    $cart = App\Helper\SessionCart::getInstance();
                    $orders = App::getInstance()->getTable("commande")->findByUserId($cart->getUserId());
                    $nbOrders = is_null($orders) ? 0 : count($orders);
                    ?>
                    <?php if ($nbOrders > 0): ?>
                    <a href="<?= $Html->href("commande") ?>"><div class="btn btn-primary shop-order-summary" title="Mes commandes">
                        <div class="cart-header">
                            <i class="fa fa-cart-arrow-down m-symb"></i>Mes commandes
                            <span class="badge"><?= $nbOrders ?></span>
                        </div>
                    </div></a>
                    <?php endif; ?>
					<div class="btn btn-primary shop-cart-summary" title="Panier">
						<div class="cart-header">
							<i class="fa fa-shopping-cart m-symb"></i>Votre Panier
							<span class="badge"><?= $cart->getSize() ?></span>
							<span class="balance"><?= number_format($cart->getTotal(), 2) ?>€</span>
						</div>
						<div class="cart-content">
							<div class="cart-products">
								<?php foreach ($cart->getArticles() as $art): ?>
									<div class="product">
										<span class="name"><?= $art->name ?></span>
										<span class="qty">x<?= $art->qty ?></span>
										<span class="price"><?= number_format($art->cartPrice, 2) ?>€</span>
									</div>
								<?php endforeach ?>
							</div>

							<a href="<?= $Html->href("panier") ?>" title="Accéder au panier"><div class="button">Voir le panier</div></a>
						</div>
					</div>
				</nav>
			</div>
		</section>

		<section class="lower">
			<div class="wrap-inner">
				<div class="ip-container ip-copy" title="Clique pour copier l'IP !" data-clipboard-text="mc.utaria.fr">
					IP du serveur: <span>mc.utaria.fr</span>
				</div>
				<nav class="sub-nav">
					<a href="<?= $Html->href("survie") ?>" title="Survie">Survie</a>
                    <a href="<?= $Html->href("cosmetiques") ?>" title="Les cosmétiques">Cosmétiques</a>
                    <a href="<?= $Html->href("grades") ?>" title="Les grades">Grades</a>
                </nav>
			</div>
		</section>
	</header>

	<?= $content_for_layout; ?>

	<footer class="footer">
		<div class="col-group wrap-inner">
			<div class="col-3">
				<div class="container-description">
					<?= $Html->img("logo.png") ?>
					<p>
						Utaria est un serveur Minecraft non affilié a Mojang.
						<br>
						Ouvert depuis le 16 décembre 2016, notre but est
						<br>
						de vous proposer du contenu inédit et original.
					</p>
				</div>



				<div class="container-socialnetwork">
					<a href="https://www.facebook.com/utaria.fr/" target="_blank" class="social-network facebook">
						<?= $Html->img("icons/facebook.png") ?>
					</a>
					<a href="https://twitter.com/Utaria_FR" target="_blank" class="social-network twitter">
						<?= $Html->img("icons/twitter.png") ?>
					</a>
					<a href="mailto:contact@utaria.fr" class="social-network contact">
						<?= $Html->img("icons/contact.png") ?>
					</a>
				</div>
			</div>



			<div class="col-3">
				<ul class="container-pagination">
					<li><?= $Html->link("blog", "Suivre notre avancement") ?></li>
					<li><?= $Html->link("voter", "Votez pour nous") ?></li>
					<li><?= $Html->link("jouer", "Nous rejoindre") ?></li>
					<li><a href="#">Statistiques</a></li>
					<li><a href="#">Nous contacter</a></li>
				</ul>
			</div>

			<div class="col-3">
				<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Futaria.fr%2F&tabs=timeline&width=385&height=250&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="385" height="250" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
			</div>
		</div>

		<div class="wrap-inner">
			<div class="container-link">
				<?= $Html->link("a-propos/conditions", "Conditions générales d'utilisation") ?>
				<?= $Html->link("a-propos/reglement", "Règlement") ?>
				<?= $Html->link("a-propos/cgv", "Conditions de vente") ?>
			</div>
		</div>

	</footer>

	<?= $Html->js("clipboard") ?>
	<?= $Html->js("app") ?>
	<?= $Html->js("boutique") ?>
<?php if ($view_name == "panier"): ?>
	<?= $Html->js("panier") ?>
<?php endif; ?>

</body>
</html>