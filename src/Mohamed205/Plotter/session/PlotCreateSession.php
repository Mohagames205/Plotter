<?php


namespace Mohamed205\Plotter\session;


use Mohamed205\Plotter\util\Math;
use pocketmine\math\Vector3;
use pocketmine\Player;

class PlotCreateSession extends Session
{

    use Math;

    private $player;

    private $firstLocation;
    private $secondLocation;

    public function __construct(Player $player)
    {
        $this->player = $player;
        parent::registerSession($player, $this);
    }

    public function setFirstLocation(Vector3 $vector3)
    {
        $this->firstLocation = $vector3;
    }

    public function setSecondLocation(Vector3 $vector3)
    {
        $this->secondLocation = $vector3;
    }

    public function getMinLocation() : Vector3
    {
        return $this->calculateMinLocation($this->firstLocation, $this->secondLocation);
    }

    public function getMaxLocation() : Vector3
    {
        return $this->calculateMaxLocation($this->firstLocation, $this->secondLocation);
    }

    public function isReady() : bool
    {
        return isset($this->firstLocation) && isset($this->secondLocation);
    }

    public function getPlayer() : Player
    {
        return $this->player;
    }

    public function destroy()
    {
        self::destroySession($this->getPlayer());
    }


}
