<div class="content-top-group">
    <div class="wrap-inner col-group">
        <h2><b>Formules uniques</b></h2>
        <p>Choisissez une formule et personnalisez votre expérience de jeu !</p>
    </div>
</div>

<?php
$colors = ["#ffb300", "#3399FF", "#ff00e9"];
$cosmetiques = [3, 10, 50];
?>

<div class="wrap-inner formules-container col-group content">
    <div class="pricing-wrapper">
        <?php $i = 0; foreach ($formules as $formule): ?>
        <div class="pricing-table<?= ($i == 1) ? " recommended" : "" ?>">
            <h3 class="pricing-title"><?= $formule->name ?></h3>
            <div class="price">€<?= $formule->price ?><sup>/ mois</sup></div>

            <ul class="table-list">
                <li>
                    Personnalise <span>ta formule</span><br>
                    <?php for ($j = 0; $j <= $i; $j++): ?>
                        <i class="em em---1"></i>
                    <?php endfor; ?>
                </li>
                <li><?= $cosmetiques[$i] ?> <span>cosmétiques exclusifs</span></li>
                <li><span>Slots</span> réservés</li>
                <li>Réduction -<?= $formule->reduction ?>%<br><span>sur les produits</span></li>
                <li style="color:#d9534f">Soutien à UTARIA<br/>
                    <?php for ($j = 0; $j <= $i; $j++): ?>
                        <i class="fas fa-heart"></i>
                    <?php endfor; ?>
                </li>
                <li>Titre <span class="ptitle" style="color:<?= $colors[$i] ?>">[<?= strtoupper($formule->title_unlock) ?>]</span></li>
            </ul>
            <div class="table-buy">
                <p>€<?= $formule->price ?><sup>/ mois</sup></p>
                <a href="<?= $Html->href("formule/creation"); ?>" class="pricing-action">Prendre</a>
            </div>
        </div>
        <?php $i++; endforeach; ?>
    </div>
</div>