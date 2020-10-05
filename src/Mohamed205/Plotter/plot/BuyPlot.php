<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\member\Member;
use pocketmine\level\Level;
use pocketmine\math\Vector3;

class BuyPlot extends Plot
{

    private ?int $price;
    private bool $isSold;

    public function __construct(string $name, ?string $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ?string $category, int $maxMembers, ?int $price, bool $isSold)
    {
        $this->price = $price;
        $this->isSold = $isSold;

        parent::__construct($name, $owner, $members, $minVector, $maxVector, $level, $category, $maxMembers);

    }

}