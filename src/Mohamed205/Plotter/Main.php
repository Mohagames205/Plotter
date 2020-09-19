<?php

declare(strict_types=1);

namespace Mohamed205\Plotter;

use CortexPE\Commando\PacketHooker;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\database\DatabaseManager;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    private static Main $instance;

    public function onEnable()
    {
        self::$instance = $this;

        if(!PacketHooker::isRegistered())
        {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->register("plotter", new PlotCommand($this, "plot", "De Plot basecommand"));

        (new DatabaseManager($this))->initDatabase();


    }

    public static function getInstance() : Main
    {
        return self::$instance;
    }


}
