<?php


namespace Mohamed205\Plotter\economy;


class EconomyApi implements EconomyProvider
{

    private $api;

    public function __construct(\onebone\economyapi\EconomyAPI $api)
    {
        $this->api = $api;
    }

    public function addToBalance(string $player, float $balance)
    {
        $this->api->addMoney($player, $balance);
    }

    public function removeFromBalance(string $player, float $balance)
    {
        $this->api->reduceMoney($player, $balance);
    }

    public function getBalance(string $player): float
    {
        return $this->api->myMoney($player);
    }
}