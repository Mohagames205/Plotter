<?php

namespace Mohamed205\Plotter;

use CortexPE\Commando\PacketHooker;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\economy\EconomyApi;
use Mohamed205\Plotter\economy\EconomyProvider;
use Mohamed205\Plotter\listener\EventListener;
use Mohamed205\Plotter\listener\LevelEventListener;
use Mohamed205\Plotter\listener\PlotEventListener;
use Mohamed205\Plotter\plot\Plot;
use Mohamed205\Plotter\util\ItemHelper;
use Mohamed205\Plotter\util\Module;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    private static $instance;
    private static $itemHelper;

    public function onEnable()
    {
        self::$instance = $this;
        self::$itemHelper = new ItemHelper($this);

        if(!PacketHooker::isRegistered())
        {
            PacketHooker::register($this);
        }

        $this->saveDefaultConfig();

        $plotCommand = new PlotCommand($this, "plot", "De Plot basecommand");
        $this->getServer()->getCommandMap()->register("plotter", $plotCommand);
        Module::setCommandMap($plotCommand);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlotEventListener(), $this);

        /*
        if($this->getServer()->getPluginManager()->getPlugin("LevelAPI"))
        {
            $this->getServer()->getPluginManager()->registerEvents(new LevelEventListener(), $this);
        }
        */


        (new DatabaseManager($this))->initDatabase();
        Plot::init();
        
    }

    public static function getInstance() : Main
    {
        return self::$instance;
    }

    public static function getItemHelper() : ItemHelper
    {
        return self::$itemHelper;
    }

    public static function getEco() : EconomyProvider
    {
        return new EconomyApi(\onebone\economyapi\EconomyAPI::getInstance());
    }


}
