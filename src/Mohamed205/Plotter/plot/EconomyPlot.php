<?php

namespace Mohamed205\Plotter\plot;

interface EconomyPlot
{

    public function setPrice(?int $price);

    public function getPrice();

    public function convertToBasicPlot();

    public function isBuyable();

    public function setBuyable(bool $isBuyable);



}