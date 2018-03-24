<div class="content-top-group">
    <div class="wrap-inner col-group">
        <h2><b>Je personnalise ma formule</b></h2>
        <p>Bienvenue dans notre studio de création !</p>
    </div>
</div>

<div class="wrap-inner creation-container col-group content">

    <div class="col-3 item-selector">
        <div class="category">Survie</div>

        <div class="item-wrapper i--droppable draggable-droppable--occupied">
            <div class="item i--draggable" data-price="0.25" data-name="Maison"></div>
        </div>
        <div class="item-wrapper i--droppable draggable-droppable--occupied">
            <div class="item i--draggable"></div>
        </div>
        <div class="item-wrapper i--droppable draggable-droppable--occupied">
            <div class="item i--draggable"></div>
        </div>
        <div class="item-wrapper i--droppable draggable-droppable--occupied">
            <div class="item i--draggable"></div>
        </div>

        <div class="category">Kits</div>



        <div class="category">Cosmétiques</div>

    </div>

    <div class="col-8 col-offset-1 selection-container">
        <div class="deposit-container i--droppable"> Déposer l'item ici pour l'ajouter</div>

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
        <table class="summary">
            <tr>
                <td>Coût total du panier</td>
                <td><span class="tprice">25</span> €</td>
            </tr>
            <tr>
                <td>Réduction (<span class="preduc">15%</span>)</td>
                <td><span class="vreduc">-3</span> €</td>
            </tr>
            <tr>
                <td>Coût total à payer</td>
                <td><span class="fprice">22</span> €</td>
            </tr>
        </table>
    </div>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.5/lib/draggable.bundle.js"></script>
<?= $Html->js("studiocreator") ?>