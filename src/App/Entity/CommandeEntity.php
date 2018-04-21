<?php
namespace App\Entity;

use App;
use Core\Entity\Entity;

/**
 * @property string uid
 * @property string promocode
 */
class CommandeEntity extends Entity {

    public function getStatusMsg() {
        switch ($this->status) {
            case       "UNPAID": return "non payée";
            case      "WAITING": return "en attente";
            case "CONFIRMATION": return "en attente de confirmation";
            case         "PAID": return "payée";
            case    "DELIVERED": return "payée et livrée";

            default: return "";
        }
    }

    public function getArticleList() {
        return App::getInstance()->getTable("CommandeArticle")->findByCommandeId($this->id);
    }

    public function getPromoReduction() {
        $reduction = 0;

        if (!is_null($this->promocode)) {
            $promo = App::getInstance()->getTable("ShopPromo")->findByCode($this->promocode);

            if (!is_null($promo)) {
                // Conditions d'application du code promo
                if ((is_null($promo->expiration_date) || time() < strtotime($promo->expiration_date)) && $this->total >= $promo->min_price) {

                    if ($promo->type == "percent")
                        $reduction = (floatval($promo->value) / 100) * $this->total;
                    if ($promo->type == "euro")
                        $reduction = floatval($promo->value);
                }
            }
        }

        return $reduction;
    }

    public function getFinalAmount() {
        return $this->total - $this->getPromoReduction();
    }

}