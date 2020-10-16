<?php

namespace Mohamed205\Plotter\command\PlotSubCommand;

use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotQuickSellCommand extends BaseSubCommand
{

    protected function prepare(): void
    {

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender->asVector3(), $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        if(!$plot->isOwner($sender->getName()))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU heeft geen toestemming om dit plot te verkopen!");
            return;
        }

        if(!$plot instanceof BuyPlot)
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU kan dit plot niet verkopen!");
            return;
        }

        $plot->quickSell();
        $sender->sendMessage(PlotCommand::$prefix . " §aHet plot is succesvol verkocht voor §2" . $plot->getQuickSellPrice() . " euro");
    }
}