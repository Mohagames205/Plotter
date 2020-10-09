<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
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
            $sender->sendMessage("§cU staat niet op een plot!");
            return;
        }

        if(!$plot instanceof BuyPlot)
        {
            $sender->sendMessage("§cU kan dit plot niet kopen!");
            return;
        }

        if(!$plot->isBuyable())
        {
            $sender->sendMessage("§4Dit plot is al verkocht");
            return;
        }

        $plot->buy($sender->getName());
        $sender->sendMessage("§aU heeft het Plot succesvol gekocht!");


    }
}