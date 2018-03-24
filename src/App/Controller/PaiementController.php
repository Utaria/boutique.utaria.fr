<?php
namespace App\Controller;

use App;
use App\Helper\API;
use App\Helper\Cart\SessionCart;
use Core\Config;
use Core\Controller\Controller;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaiementController extends Controller {

	protected $useTable = false;

    public function login($params) {
        $error = null;
        $cart = SessionCart::getInstance();

        $returnUrl = (!empty($params)) ? $params[0] : "commande/create";

        if ($cart->isUserConnected())
            $this->redirect($returnUrl);

        $error = null;

        if (!empty($_POST)) {
            $playername = $_POST["playername"];
            $password   = sha1($_POST["password"]);

            $authObj = API::post("players/authenticate", array(
                "name"     => $playername,
                "password" => $password
            ));

            if ($authObj != null && !$authObj->error) {
                $cart->logUser($authObj);
                $this->redirect($returnUrl);
            } else {
                $prefix = "<i class='fa fa-arrow-right'></i>";
                $error = $prefix . "Pseudo ou mot de passe incorrect.";
            }
        }

        $this->render("paiement.login", compact("error"));
    }

    public function index() {
        $cart = SessionCart::getInstance();

        if (!$cart->isUserConnected())
            $this->redirect("paiement/login");

        $orderId = $cart->getLastCommandeId();
        if (empty($orderId)) $this->redirect("panier");

        $order = App::getInstance()->getTable("Commande")->find($orderId);
        if (empty($order)) $this->redirect("panier");

        $this->render("paiement", compact("order"));
    }

    public function recap($mean) {
        $this->setResponseType("application/json");

        if (empty($mean)) die("erreur");

        $mean = $mean[0];
        $cart = SessionCart::getInstance();

        if (!$cart->isUserConnected()) die("erreur");

        $orderId = $cart->getLastCommandeId();
        if (empty($orderId)) die("erreur");

        $order = App::getInstance()->getTable("Commande")->find($orderId);
        if (empty($order)) die("erreur");

        // ... et on génère le lien vers lequel sera redirigé l'utilisateur pour payer.
        $payLink = "";

        switch ($mean) {
            case "paypal":
                $apiContext = new ApiContext(new OAuthTokenCredential(
                    Config::getInstance()->getValue('paypal.id'),
                    Config::getInstance()->getValue('paypal.secret')
                ));
                $apiContext->setConfig(array(
                    "mode" => (boolval(Config::getInstance()->getValue('paypal.sandbox'))) ? "sandbox" : "live"
                ));

                $payment = new Payment();

                $redirectUrls = (new RedirectUrls())
                    ->setReturnUrl(\App::getInstance()->getBaseURL() . 'paiement/executer')
                    ->setCancelUrl(\App::getInstance()->getBaseURL() . 'paiement');

                $payment->setIntent('sale');
                $payment->setRedirectUrls($redirectUrls);
                $payment->setPayer((new Payer())->setPaymentMethod('paypal'));
                $payment->setNoteToPayer("L'équipe d'UTARIA vous remercie pour votre achat sur notre boutique !");
                $payment->addTransaction(App\Helper\PaypalTransactionFactory::fromOrder($order));

                try {
                    $payment->create($apiContext);
                    $payLink = $payment->getApprovalLink();
                } catch (PayPalConnectionException $e) {
                    header('HTTP 500 Internal Server Error', true, 500);

                    echo $e->getData();
                    return;
                }

                break;
        }

        $cart->setLastpaymentMean($mean);

        echo json_encode(array(
            "order_uid" => $order->uid,
            "account"   => $cart->getUser()->getPlayername(),
            "mean"      => ucfirst($mean),
            "total"     => floatval($order->total),
            "promocode" => $order->promocode,
            "dueamount" => $order->getFinalAmount(),
            "pay_link"  => $payLink
        ));
    }

    public function executer() {
        $cart = SessionCart::getInstance();
        if (!$cart->isUserConnected()) $this->redirect("panier");

        $orderId = $cart->getLastCommandeId();
        if (empty($orderId)) $this->redirect("panier");

        $order = App::getInstance()->getTable("Commande")->find($orderId);
        if (empty($order)) $this->redirect("panier");

        if ($order->status != "UNPAID") $this->redirect("");

        switch ($cart->getLastPaymentMean()) {
            case "paypal":
                if (!isset($_GET["paymentId"]) || !isset($_GET["PayerID"]))
                    $this->redirect("paiement/erreur");

                $apiContext = new ApiContext(new OAuthTokenCredential(
                    Config::getInstance()->getValue('paypal.id'),
                    Config::getInstance()->getValue('paypal.secret')
                ));

                $payment = Payment::get($_GET["paymentId"], $apiContext);

                $execution = (new PaymentExecution())
                    ->setPayerId($_GET["PayerID"])
                    ->addTransaction(App\Helper\PaypalTransactionFactory::fromOrder($order));

                try {
                    $payment->execute($execution, $apiContext);

                    if ($payment->getState() == "approved" &&
                        $payment->getTransactions()[0]->getCustom() == "PHx8mZR0ZDAnxpG6S2WDs5bq0N432H") {

                        // On valide le paiement en base de données
                        // en enregistrant les données intéressantes.
                        App::getInstance()->getTable("Commande")->update(array(
                            "status"     => "PAID",
                            "payer_id"   => $payment->getPayer()->getPayerInfo()->getPayerId(),
                            "payer_mail" => $payment->getPayer()->getPayerInfo()->getEmail(),
                            "transaction_id" => $payment->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId()
                        ), array(
                            "id" => $order->id
                        ));

                        $this->redirect("paiement/merci");
                    } else {
                        // Le paiement n'a pas pu être approuvé ...
                        App::getInstance()->getTable("Commande")->update(
                            array("status" => "ERROR"),
                            array("id" => $order->id)
                        );

                        $this->redirect("paiement/erreur");
                    }

                } catch (PayPalConnectionException $e) {
                    App::getInstance()->getTable("Commande")->update(
                        array("status" => "ERROR"),
                        array("id" => $order->id)
                    );


                    $this->redirect("paiement/erreur");
                }

                break;
        }
    }

    public function merci() {
        // On fait toutes les vérifications nécessaires ...
        $cart = SessionCart::getInstance();
        if (!$cart->isUserConnected()) $this->redirect("/");

        $comId = $cart->getLastCommandeId();
        if (is_null($comId)) $this->redirect("/");

        $com = App::getInstance()->getTable("commande")->find($comId);
        if (is_null($com) || $com->status != 'PAID')
            $this->redirect("/");

        // ... tout est ok donc on lance la distribution des produits ...
        foreach ($com->getArticleList() as $article)
            App\Helper\CommandeArticleDistributor::distribute($cart->getUser(), $article);

        // ... et on affiche la page de remerciements.
        $this->render("paiement.merci");
    }

}