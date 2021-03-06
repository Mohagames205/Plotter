<?php


namespace Mohamed205\Plotter\member;


use Exception;
use pocketmine\Server;


/**
 * Class Member
 * @package Mohamed205\Plotter\member
 * Dit is een wrapper voor een Plot member of plot owner, maar dit wordt nog niet gebruikt!
 */
class Member
{

    private $player;

    public function __construct(string $player)
    {
        if (!Server::getInstance()->hasOfflinePlayerData($player)) {
            throw new Exception("Trying to create Member object from non-existing player");
        }
        $this->player = $player;
    }

    public function getPlots(): array
    {

    }

    public static function exists(string $player)
    {
        return Server::getInstance()->hasOfflinePlayerData($player);
    }

    public function getOfflinePlayer()
    {
        return Server::getInstance()->getOfflinePlayer($this->player);
    }


}