<?php


namespace Mohamed205\Plotter\plot;


use pocketmine\level\Level;
use pocketmine\math\Vector3;

class RentPlot extends Plot
{

    private $price;
    private $isBuyable;


    public function __construct(string $name, ?string $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ?string $category, int $maxMembers, ?int $price, bool $isBuyable)
    {
        $this->price = $price;
        $this->isBuyable = $isBuyable;

        parent::__construct($name, $owner, $members, $minVector, $maxVector, $level, $category, $maxMembers);

    }

    public function isBuyable() : bool
    {
        return $this->isBuyable;
    }

    public function getPrice() : ?int
    {
        return $this->price;
    }
    


}