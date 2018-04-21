<div class="content-top-group">
    <div class="wrap-inner col-group">
        <h2><b>Je personnalise ma formule</b></h2>
        <p>Bienvenue dans notre studio de création !</p>
    </div>
</div>

<?php function item($article) { ?>
    <div class="item-wrapper i--droppable draggable-droppable--occupied" data-id="<?= $article->id ?>">
        <div class="item i--draggable" data-id="<?= $article->id ?>" data-price="<?= $article->price ?>" data-name="<?= $article->name ?>"></div>
    </div>
<?php }
function items($articles, $category) {
    foreach($articles as $article)
        if ($article->type == $category)
            item($article);
}
?>

<div class="wrap-inner creation-container col-group content">

    <div class="col-3 item-selector">
        <div class="category">Survie</div>
        <?php items($articles, "survie") ?>

        <div class="category">Kits</div>
        <?php items($articles, "kits") ?>

        <div class="category">Cosmétiques</div>
        <?php items($articles, "cosmetiques") ?>
    </div>

    <div class="col-8 col-offset-1 selection-container">
        <div class="deposit-container i--droppable"> Déposer l'item ici pour l'ajouter</div>
        <div class="intro-container"> <i class="fa fa-arrow-left"></i> Glissez-déposez les items à ajouter</div>

        <div class="items">

        </div>

        <div class="my-item template" data-unity-price="X">
            <span class="name"></span>
            <div class="qty-selector">
                <span>1</span>
            </div>
            <div class="price">
                <span></span> €
            </div>
            <span class="remove-ctn">
                <i class="fas fa-times-circle remove"></i>
            </span>
        </div>
    </div>

    <div class="col-12 summary-container">
        <div class="progress-formule">
            <div class="progress-bar-container">
                <div class="progress-bar"></div>
            </div>
            <div class="meta">
                <div class="step">
                    <span class="price">5€</span>
                    <span class="name">Baron</span>
                </div>
                <div class="step">
                    <span class="price">10€</span>
                    <span class="name">Compte</span>
                </div>
                <div class="step">
                    <span class="price">20€</span>
                    <span class="name">Duc</span>
                </div>
            </div>
        </div>
        <table class="summary">
            <tr>
                <td>Coût total du panier</td>
                <td><span class="tprice">0</span> €</td>
            </tr>
            <tr>
                <td>Réduction (<span class="preduc">-</span>)</td>
                <td><span class="vreduc">0</span> €</td>
            </tr>
            <tr>
                <td>Coût total à payer</td>
                <td><span class="fprice">0</span> €</td>
            </tr>
        </table>
    </div>

    <form action="<?= $Html->href("formule/addtocart") ?>" method="POST" id="items-form"></form>

    <p class="col-12" style="text-align:right;font-size:.8em"><i class="fas fa-exclamation-triangle" style="color:red"></i> En ajoutant la formule au panier, vous n'aurez plus la possibilité de la modifier.</p>

    <div class="col-3 col-offset-9">
        <div class="btn submit btn-primary">
            Ajouter au panier
        </div>
    </div>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.5/lib/draggable.bundle.js"></script>
<?= $Html->js("studiocreator") ?>