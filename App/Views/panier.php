<div class="wrap-inner col-group content">
	<h1 class="title">Votre panier</h1>

	<table class="products" data-qty-href="<?= $Html->href("panier/changeqty") ?>" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>Article</th>
				<th>Prix unitaire TTC</th>
				<th>Quantité</th>
				<th>Prix TTC</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($articles as $article): ?>
				<tr data-product-id="<?= $article->id ?>">
					<td>
						<!-- <span class="cat booster">Booster</span> -->
						<a href="#"><?= $article->name ?></a>
					</td>
					<td class="u-price c">
						<span><?= $article->price ?></span> €
					</td>
					<td class="c" qty-selector>
						<span><?= $article->qty ?></span>
					</td>
					<td class="c-price c">
						<span><?= $article->cartPrice ?></span> €
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<div class="basket-action-container">
		<div class="col-group">
			<div class="col-4">
				<form action="<?= $Html->href("panier/promotionalcode") ?>" method="POST" class="promo-form">
					<input type="text" placeholder="Code promotionnel"<?php if ($promoCode != null): echo " value='$promoCode'"; endif; ?>>
					<button type="submit"><i class="fa fa-angle-right"></i></button>
				</form>
				<span class="error"></span>
				<div class="links">
					<a href="#">Conditions de ventes</a>
				</div>
			</div>
			<div class="col-8">
				<table class="total">
					<tr class="b">
						<td>Total de vos achats</td>
						<td>
							<span class="total-products-price"><?= $cartTotal ?></span> €
						</td>
					</tr>
					<tr class="promo hidden">
						<td>
							Code promotionnel <span class="code"></span> <span class="rm-code"><i class="fa fa-times"></i></span>
						</td>
						<td class="reduc"></td>
					</tr>
					<tr class="b">
						<td>Total avec remise</td>
						<td>
							<span></span> €
						</td>
					</tr>
					<tr><td></td><td></td></tr>
				</table>
				<div class="clear"></div>

				<button type="submit" class="submit">
					<i class="fa fa-check"></i> Commander
				</button>
			</div>
		</div>
		
	</div>
</div>