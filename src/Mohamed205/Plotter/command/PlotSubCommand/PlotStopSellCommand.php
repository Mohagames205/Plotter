<?php

namespace Mohamed205\Plotter\command\PlotSubCommand;

use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotStopSellCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender->asVector3(), $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        if(!$plot instanceof BuyPlot)
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU kan deze command niet gebruiken.");
            return;
        }

        if(!$plot->isSellingByPlayer())
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cDit plot wordt momenteel niet verkocht!");
            return;
        }

        $plot->stopSellingByPlayer();
        $sender->sendMessage(PlotCommand::$prefix ." §aHet plot wordt niet meer verkocht.");
    }
}