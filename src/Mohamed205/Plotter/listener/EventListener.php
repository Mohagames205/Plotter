<?php


namespace Mohamed205\Plotter\listener;


use Mohamed205\Plotter\session\PlotCreateSession;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener
{

    public function onFirstPosition(BlockBreakEvent $e) : void
    {
        $player = $e->getPlayer();
        $session = PlotCreateSession::getSession($player);
        $session->setFirstLocation($e->getBlock()->asVector3());
        $e->setCancelled();

        $player->sendMessage("§f[§cTDBJail§f] §aEerste locatie geselecteerd");
    }

    public function onSecondPosition(PlayerInteractEvent $e) : void
    {
        if ($e->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $player = $e->getPlayer();
            $session = PlotCreateSession::getSession($player);
            $session->setSecondLocation($e->getBlock()->asVector3());
            $e->setCancelled();

            $player->sendMessage("§f[§cTDBJail§f] §aTweede locatie geselecteerd.");
        }
    }

}