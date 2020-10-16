<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\Main;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PlotWandCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission("plotter.admin.wand");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        /** @var Player $sender */
        $sender->getInventory()->addItem(Main::getItemHelper()->getWandItem());
        $sender->sendMessage(PlotCommand::$prefix . " §aU heeft succesvol een plotwand ontvangen");
    }
}