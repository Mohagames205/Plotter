<?php


namespace Mohamed205\Plotter\economy;


interface EconomyProvider
{
    public function addToBalance(string $player, float $balance);

    public function removeFromBalance(string $player, float $balance);

    public function getBalance(string $player) : float;
}