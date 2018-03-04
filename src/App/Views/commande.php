<div class="content-top-group">
    <div class="wrap-inner col-group">
        <h2><b>Mes commandes</b></h2>
        <p>Retrouvez ci-dessous toutes les commandes que vous avez passées</p>
    </div>
</div>

<div class="wrap-inner col-group content">

    <div class="orders">
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <i class="fa fa-shopping-cart icon"></i>
                <div class="meta">
                    <h3 class="title">Commande #<?= $order->uid ?></h3>
                    <span class="order-status <?= strtolower($order->status) ?>"><?= $order->statusMsg ?></span>
                </div>
                <div class="date">Passée le <?= date('d/m/Y à H:i', strtotime($order->date)) ?></div>
                <p class="description">

                </p>
                <div class="price">
                    <div class="amount"><?= $order->total ?> €</div>
                    <?php if (!empty($order->promocode)): ?>
                        <div class="promocode">Code promo: <b><?= $order->promocode ?></b></div>
                    <?php endif; ?>
                </div>
                <?php if ($order->status == "UNPAID"): ?>
                    <?= $Html->link("commande/" . $order->id, '<div class="btn btn-primary pay-button">Payer</div>') ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>