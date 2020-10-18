<?php


namespace Mohamed205\Plotter\util;


use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\plugin\Plugin;

class Module
{

    private static BaseCommand $commandMap;
    private static array $loadedModules = [];
    private Plugin $module;
    private array $subcommands = [];

    public function __construct(Plugin $module)
    {
        $this->module = $module;
    }

    public static function setCommandMap(BaseCommand $command)
    {
        self::$commandMap = $command;
    }

    public static function registerModule(Plugin $module)
    {
        self::$loadedModules[strtolower($module->getName())] = new Module($module);
        return new Module($module);
    }

    public static function isLoaded(string $moduleName)
    {
        return in_array(strtolower($moduleName), array_keys(self::getLoadedModules()));
    }

    /**
     * @return Module[]
     */
    public static function getLoadedModules() : array
    {
        return self::$loadedModules;
    }

    public function registerCommand(BaseSubCommand $subCommand)
    {
        self::$commandMap->registerSubCommand($subCommand);
    }

    /**
     * @return BaseSubCommand[]
     */
    public function getCommands() : array
    {
        return $this->subcommands;
    }



}