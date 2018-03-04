<div class="content-top-group">
    <div class="wrap-inner col-group">
        <h2><b>Commande #<?= $order->uid ?></b></h2>
        <p>Choisissez votre moyen de paiement pour payer !</p>
    </div>
</div>

<div class="wrap-inner payment-container content">

    <h2 class="center">Choisissez votre moyen de paiement</h2>

    <div class="payment-means col-group">
        <div class="col-3">
            <div class="btn payment-mean paypal" data-mean="paypal">
                <div class="image"></div>
            </div>
        </div>
        <div class="col-3">
            <div class="btn payment-mean cb" data-mean="cb">
                <div class="image"></div>
            </div>
        </div>
        <div class="col-3">
            <div class="btn payment-mean paysafecard" data-mean="paysafecard">
                <div class="image"></div>
            </div>
        </div>
        <div class="col-3">
            <div class="btn payment-mean youpass" data-mean="youpass">
                <div class="image"></div>
            </div>
        </div>
    </div>

    <br /><br />
    <div class="clear"></div>
    <h2 class="center">Récapitulatif du paiement</h2>

    <div class="col-6 col-offset-3">
        <div class="payment-recap" style="display:none">
            <table>
                <tr>
                    <td>Numéro de commande</td>
                    <td class="order_nb">-</td>
                </tr>
                <tr>
                    <td>Compte bénéficiaire</td>
                    <td class="account">-</td>
                </tr>
                <tr>
                    <td>Moyen de paiement</td>
                    <td class="payment_mean">-</td>
                </tr>
                <tr>
                    <td>Total du panier TTC</td>
                    <td class="total_cart">-</td>
                </tr>
                <tr class="promocode">
                    <td>Code promotionnel</td>
                    <td class="codep">-</td>
                </tr>
                <tr>
                    <td>Prix à payer TTC</td>
                    <td class="dueamount">-</td>
                </tr>
            </table>

            <br />
            <a class="btn-pay" href=""><button type="submit" class="btn submit"></button></a>
        </div>
        <div class="payment-recap-loader" style="display:none">
            <i class="fas fa-cog fa-spin"></i>
        </div>

        <p class="choose-mean center"><br />Choisissez votre moyen de paiement.<br /></p>
    </div>

    <div class="clear"></div>

</div>

<script type="text/javascript">
    window.addEventListener("load", function() {
        var means = document.querySelectorAll(".payment-mean");
        var loader = document.querySelector(".payment-recap-loader");
        var choose = document.querySelector(".choose-mean");
        var table = document.querySelector(".payment-recap table");

        var rl = debounce(reload, 500);
        var occ = false;

        for (var i = 0; i < means.length; i++) {
            means[i].addEventListener("click", function() {
                for (var j = 0; j < means.length; j++)
                    means[j].classList.remove("selected");

                this.classList.add("selected");

                rl(this.getAttribute("data-mean"));
            });
        }

        function reload(mean) {
            if (occ) return;

            // Loading...
            occ = true;
            choose.style.display = "none";
            table.parentNode.style.display = "none";
            loader.style.display = "block";

            var xhr = new XMLHttpRequest();

            xhr.addEventListener("readystatechange", function(event) {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var json = JSON.parse(xhr.responseText);

                    updateRecap(json);
                    occ = false;
                }
            });

            xhr.open('GET', '<?= $Html->href('paiement/recap/') ?>' + mean, true);
            xhr.send(null);
        }

        function updateRecap(json) {
            table.querySelector(".order_nb").innerHTML = "#" + json.order_uid;
            table.querySelector(".account").innerHTML = json.account;
            table.querySelector(".payment_mean").innerHTML = json.mean;
            table.querySelector(".total_cart").innerHTML = json.total.toFixed(2) + ' EUR';

            if (json.promocode === null)
                table.querySelector(".promocode").style.display = "none";
            else {
                table.querySelector(".promocode").style = "";
                table.querySelector(".codep").innerHTML = '-' + (json.total - json.dueamount).toFixed(2) + ' EUR';
            }

            table.querySelector(".dueamount").innerHTML = json.dueamount.toFixed(2) + ' EUR';

            var btn = document.querySelector(".btn-pay");

            if (json.pay_link === "") {
                btn.setAttribute("href", "#");
                btn.childNodes[0].innerHTML = '<i class="fas fa-times"></i> Indisponible';
                btn.classList.add("error");
            } else {
                btn.setAttribute("href", json.pay_link);
                btn.childNodes[0].innerHTML = '<i class="fas fa-check"></i> Payer';
                btn.classList.remove("error");
            }

            loader.style.display = "none";
            table.parentNode.style.display = "block";
        }
    });
</script>