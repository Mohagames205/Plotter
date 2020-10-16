<?php


namespace Mohamed205\Plotter\listener;


use Mohamed205\Plotter\plot\Plot;
use pocketmine\block\Chest;
use pocketmine\block\Door;
use pocketmine\block\FenceGate;
use pocketmine\block\Hopper;
use pocketmine\block\ItemFrame;
use pocketmine\block\Trapdoor;
use pocketmine\entity\object\ArmorStand;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

class PlotEventListener implements Listener
{

    public function chestInteraction(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        $player = $e->getPlayer();
        if ($block instanceof Chest) {
            $plot = Plot::getAtVector($block->asVector3(), $block->getLevel());
            if (!is_null($plot)) {
                if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$player->hasPermission("plotter.admin.bypass")) {
                    $player->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                }
            }

        }
    }

    public function trapdoorInteraction(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        if ($block instanceof Trapdoor) {
            $plot = Plot::getAtVector($block->asVector3(), $block->getLevel());
            if (!is_null($plot)) {
                $player = $e->getPlayer();
                if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$player->hasPermission("plotter.admin.bypass")) {
                    $player->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                }
            }
        }
    }

    public function doorInteraction(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        $player = $e->getPlayer();
        if ($block instanceof Door) {
            $plot = Plot::getAtVector($block->asVector3(), $block->getLevel());
            if (!is_null($plot)) {
                if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$player->hasPermission("plotter.admin.bypass")) {
                    $player->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                }
            }
        }
    }

    public function gateInteraction(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        $player = $e->getPlayer();
        if ($block instanceof FenceGate) {
            $plot = Plot::getAtVector($block->asVector3(), $block->getLevel());
            if (!is_null($plot)) {
                if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$player->hasPermission("plotter.admin.bypass")) {
                    $player->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                }
            }
        }
    }

    public function itemFrameInteraction(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        if ($block instanceof ItemFrame) {
            $plot = Plot::getAtVector($block->asVector3(), $block->getLevel());
            if (!is_null($plot)) {
                $player = $e->getPlayer();
                if ($e->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                    if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$player->hasPermission("plotter.admin.bypass")) {
                        $player->sendPopup("§4U kan deze actie niet uitvoeren.");
                        $e->setCancelled();
                        return;
                    }
                }
                if ($e->getAction() == PlayerInteractEvent::LEFT_CLICK_BLOCK && !$player->hasPermission("plotter.admin.bypass")) {
                    $e->getPlayer()->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                }
            }
        }
    }

    public function armorStandInteraction(PlayerInteractEntityEvent $e)
    {
        if ($e->getEntity() instanceof ArmorStand) {
            $plot = Plot::getAtVector($e->getEntity()->asVector3(), $e->getEntity()->getLevel());
            if (!is_null($plot)) {
                if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$e->getPlayer()->hasPermission("plotter.admin.bypass")) {
                    $e->getPlayer()->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                    return;
                }
            }
        }
    }

    public function onArmorStandBreak(EntityDamageByEntityEvent $e)
    {
        $damager = $e->getDamager();
        if ($damager instanceof Player) {
            if ($damager->getGamemode() == Player::CREATIVE) {
                return;
            }
            if ($e->getEntity() instanceof ArmorStand && !$damager->hasPermission("pa.staff.interactbypass")) {
                $damager->sendPopup("§4U kan deze actie niet uitvoeren.");
                $e->setCancelled();
            }
        }
    }

    public function onHopperInteract(PlayerInteractEvent $e)
    {
        $block = $e->getBlock();
        if ($block instanceof Hopper) {
            $plot = Plot::getAtVector($block->asVector3(), $block->getLevel());
            if (!is_null($plot)) {
                if (!$plot->isMember($e->getPlayer()) && $plot->isOwner($e->getPlayer()) && !$e->getPlayer()->hasPermission("plotter.admin.bypass")) {
                    $e->getPlayer()->sendPopup("§4U kan deze actie niet uitvoeren.");
                    $e->setCancelled();
                    return;
                }
            }
        }
    }

}