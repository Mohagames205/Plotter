<?php


namespace Mohamed205\Plotter\session;


use Mohamed205\Plotter\util\Math;
use pocketmine\Player;

abstract class Session
{
    use Math;

    public static array $sessions;

    protected static function registerSession(Player $player, Session $session)
    {
        self::$sessions[$player->getLowerCaseName()] ??= $session;
    }

    public static function getSession(Player $player) : Session
    {
        return self::$sessions[$player->getLowerCaseName()] ?? new PlotCreateSession($player);
    }

}