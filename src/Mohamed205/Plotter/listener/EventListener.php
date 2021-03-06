<?php


namespace Mohamed205\Plotter\listener;


use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\Main;
use Mohamed205\Plotter\session\PlotCreateSession;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener
{

    public function onFirstPosition(BlockBreakEvent $e) : void
    {
        if(Main::getItemHelper()->isWandItem($e->getItem()))
        {
            var_dump("ibebnnerngongnerngenn");
            $player = $e->getPlayer();
            $session = PlotCreateSession::getSession($player);
            $session->setFirstLocation($e->getBlock()->asVector3());
            $e->setCancelled();

            $player->sendMessage(PlotCommand::$prefix . " §aEerste locatie geselecteerd");
        }
    }

    public function onSecondPosition(PlayerInteractEvent $e) : void
    {
        if ($e->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK && Main::getItemHelper()->isWandItem($e->getItem())) {
            var_dump("NFZELFNEONFONEROFNER");
            $player = $e->getPlayer();
            $session = PlotCreateSession::getSession($player);
            $session->setSecondLocation($e->getBlock()->asVector3());
            $e->setCancelled();

            $player->sendMessage(PlotCommand::$prefix . " §aTweede locatie geselecteerd.");
        }
    }

}