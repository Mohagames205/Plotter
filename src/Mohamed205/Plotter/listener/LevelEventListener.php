<?php

namespace Mohamed205\Plotter\listener;

use Hebbinkpro\LevelAPI\UserController;
use Mohamed205\Plotter\event\PlotSetOwnerEvent;
use Mohamed205\Plotter\Main;
use pocketmine\event\Listener;

class LevelEventListener implements Listener
{

    public function onSetOwner(PlotSetOwnerEvent $event)
    {
        $currentOwner = $event->getPlot()->getOwner();
        $newOwner = $event->getOwner();

        $add_xp = Main::getInstance()->getConfig()->get("add_xp");
        $xpToRemove = Main::getInstance()->getConfig()->get("remove_xp");

        if(!is_null($newOwner))
        {
            UserController::addXp($newOwner, $add_xp);
        }

        if(!is_null($currentOwner))
        {
            UserController::setXp($currentOwner, UserController::getXp($currentOwner) - $xpToRemove);
        }
    }



}