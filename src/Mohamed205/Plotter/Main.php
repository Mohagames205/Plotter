<?php

declare(strict_types=1);

namespace Mohamed205\Plotter;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    private static Main $instance;

    public function onEnable()
    {
        self::$instance = $this;

    }

    public static function getInstance() : Main
    {
        return self::$instance;
    }

}
