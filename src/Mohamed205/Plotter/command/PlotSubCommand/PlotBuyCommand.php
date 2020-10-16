<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\Main;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotBuyCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender, $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        if(!$plot instanceof BuyPlot)
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU kan dit plot niet kopen!");
            return;
        }

        if(!$plot->isBuyable())
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cDit plot is al verkocht");
            return;
        }

        $eco = Main::getEco();
        $playerName = $sender->getName();

        if(($plot->isSellingByPlayer() && $eco->getBalance($playerName) < $plot->getPlayerSellPrice()) || ($plot->isBuyable() && $eco->getBalance($playerName) < $plot->getPrice()))
        {
            $sender->sendMessage(PlotCommand::$prefix . "§cU heeft onvoldoende geld op uw rekening.");
            return;
        }

        $plot->buy($sender->getName());
        $sender->sendMessage(PlotCommand::$prefix . " §aU heeft het Plot succesvol gekocht!");


    }
}