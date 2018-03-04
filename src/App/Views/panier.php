<div class="content-top-group">
    <div class="wrap-inner col-group">
        <h2><b>Mon panier</b></h2>
        <p>N'oubliez pas de le reposez près de la caisse une fois utilisé.</p>
    </div>
</div>

<div class="wrap-inner basket-container col-group content">
    <h2 class="center">Récapitulatif</h2>

	<table class="products" data-qty-href="<?= $Html->href("panier/changeqty") ?>" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>Article</th>
				<th>Prix unitaire TTC</th>
				<th>Quantité</th>
				<th style="width:190px">Prix TTC</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($articles as $article): ?>
				<tr data-product-id="<?= $article->id ?>">
					<td>
						<span class="cat <?= $article->type ?>"><?= ucfirst($article->type) ?></span>
						<a href="#" class="name"><?= $article->name ?></a>
					</td>
					<td class="u-price c">
						<span><?= $article->price ?></span> €
					</td>
					<td class="qty c"<?php if (!$article->single) echo " qty-selector" ?>>
						<span><?= $article->qty ?></span>
					</td>
					<td class="c-price c">
						<span><?= $article->cartPrice ?></span> €
					</td>
					<td>
						<i class="fas fa-times-circle remove"></i>
					</td>
				</tr>
			<?php endforeach ?>
			<tr class="noproduct" <?= (count($articles) > 0) ? ' style="display:none"' : ' style="display:block"' ?>>
				<td><p>
					<i class="fa fa-times"></i> Aucun produit dans votre panier.
				</p></td>
			</tr>
		</tbody>
	</table>

	<div class="basket-action-container">
		<div class="col-group">
			<div class="col-4">
				<form action="<?= $Html->href("panier/promotionalcode") ?>" method="POST" class="promo-form"<?php if (count($articles) == 0) echo ' style="display:none"' ?>>
					<input type="text" placeholder="Code promotionnel"<?php if ($promoCode != null): echo " value='$promoCode'"; endif; ?>>
					<button type="submit"><i class="fa fa-angle-right"></i></button>
				</form>
				<span class="error"></span>
			</div>
			<div class="col-8">
				<table class="total">
					<tr class="b">
						<td>Total de vos achats TTC</td>
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
						<td>Total avec remise TTC</td>
						<td>
							<span></span> €
						</td>
					</tr>
					<tr><td></td><td></td></tr>
				</table>
				<div class="clear"></div>
			</div>
		</div>
    </div>

    <!-- TODO: informations supplémentaires requises pour chaque commande.
    <br /><br />
    <h2 class="center">Vos informations</h2>

    <div class="col-group">
        <div class="col-6">

        </div>
        <div class="col-6">

        </div>
    </div>
    -->

    <div class="confirm-container"<?php if (count($articles) == 0) echo ' style="display:none"' ?>>
        <br /><br />
        <h2 class="center" style="font-size:1.7em">Confirmer</h2>

        <blockquote class="warn-message">
            <i class="fas fa-exclamation-triangle"></i> Tout achat effectué sur la boutique <b>est définitif et ne peut pas être remboursé</b>. En cas de fraude ou de litige, le joueur concerné sera <b>définitivement banni</b>.
        </blockquote>

        <input type="checkbox" id="confirm-box" /> <label for="confirm-box" class="confirm-label">
            J'ai lu et j'accepte les conditions générales d'utilisation et les conditions générales de vente.
        </label>

        <a href="<?= $Html->href("commande/create") ?>" onclick="if (!verifyConfirmation()) {event.preventDefault();return false;}"><button type="submit" class="btn submit">
                <i class="fa fa-check"></i> Commander
            </button></a>
    </div>
</div>

<script type="text/javascript">
    function verifyConfirmation() {
        var box = document.getElementById("confirm-box");

        if (!box.checked) {
            alert("Merci de confirmer votre commande !");
            return false;
        }

        return true;
    }
</script>