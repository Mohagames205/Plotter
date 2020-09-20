<?php

declare(strict_types=1);

namespace Mohamed205\Plotter;

use CortexPE\Commando\PacketHooker;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\listener\EventListener;
use Mohamed205\Plotter\plot\Plot;
use Mohamed205\Plotter\util\ItemHelper;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    private static Main $instance;
    private static ItemHelper $itemHelper;

    public function onEnable()
    {
        self::$instance = $this;
        self::$itemHelper = new ItemHelper($this);

        if(!PacketHooker::isRegistered())
        {
            PacketHooker::register($this);
        }

        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->register("plotter", new PlotCommand($this, "plot", "De Plot basecommand"));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        (new DatabaseManager($this))->initDatabase();
        
    }

    public static function getInstance() : Main
    {
        return self::$instance;
    }

    public static function getItemHelper() : ItemHelper
    {
        return self::$itemHelper;
    }


}
