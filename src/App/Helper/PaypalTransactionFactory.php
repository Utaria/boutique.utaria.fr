<?php
namespace App\Helper;

use App\Entity\CommandeEntity;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;

class PaypalTransactionFactory {

    private function __construct() {

    }

    /**
     * @param $order CommandeEntity
     * @return Transaction
     */
    public static function fromOrder($order) {
        $items = new ItemList();

        foreach ($order->getArticleList() as $article) {
            $item = (new Item())
                ->setName($article->name)
                ->setQuantity($article->qty)
                ->setCurrency('EUR')
                ->setPrice($article->buyed_at);

            $items->addItem($item);
        }

        if (!is_null($order->promocode)) {
            $item = (new Item())
                ->setName("Code promotionnel")
                ->setPrice(str_replace(',', '.', -$order->getPromoReduction()))
                ->setCurrency('EUR')
                ->setSku($order->promocode)
                ->setQuantity(1);

            $items->addItem($item);
        }

        $details = (new Details())
            ->setSubtotal(str_replace(',', '.', $order->getFinalAmount()));

        $amountP = (new Amount())
            ->setTotal(str_replace(',', '.', $order->getFinalAmount()))
            ->setCurrency("EUR")
            ->setDetails($details);

        $transaction = (new Transaction())
            ->setItemList($items)
            ->setDescription("Achat sur boutique.utaria.fr. Commande #" . $order->uid)
            ->setAmount($amountP)
            ->setInvoiceNumber($order->uid)
            ->setCustom("PHx8mZR0ZDAnxpG6S2WDs5bq0N432H");

        return $transaction;
    }

}