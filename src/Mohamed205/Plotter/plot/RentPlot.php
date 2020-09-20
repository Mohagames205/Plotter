<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\member\Member;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class RentPlot extends Plot
{

    private ?int $price;
    private bool $isSold;

    public function __construct(string $name, ?Member $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ?int $price, bool $isSold, int $billingPeriod)
    {
        $this->price = $price;
        $this->isSold = $isSold;

        parent::__construct($name, $owner, $members, $minVector, $maxVector, $level);

    }


}