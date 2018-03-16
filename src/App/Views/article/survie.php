<div class="wrap-inner articles-container col-group content">

    <?php foreach($articles as $article): ?>

        <div class="col-3">
            <div class="article">
                <div class="image">
                    <?= $Html->img("articles/{$article->id}.png") ?>
                </div>
                <div class="meta" data-id="<?= $article->id ?>">
                    <div class="info">
                        <h3 class="name"><?= $article->name ?></h3>
                        <div class="price"><?= number_format($article->price, 2, ',', ' ') ?> EUR</div>
                    </div>
                    <div class="addtocart">
                        <div class="btn btn-primary shop-cart-add" title="Panier">
                            <i class="fa fa-shopping-cart m-symb"></i>Ajouter au panier
                        </div>
                        <i class="fas fa-cog fa-spin"></i>
                        <span class="label"></span>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

    <input type="hidden" class="action_url" value="<?= $Html->href('article/:id/addtocart') ?>">

</div>

<?= $Html->js("articles") ?>