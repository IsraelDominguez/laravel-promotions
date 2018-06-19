<?php namespace Genetsis\Promotions\Contracts;

/**
 * Interface PromoUserInterface Users promo must implement this for work as Promo User Entity
 * @package Genetsis\Promotions\Contracts
 */
interface PromoUserInterface {

    public function getId();

    public function getName();

}