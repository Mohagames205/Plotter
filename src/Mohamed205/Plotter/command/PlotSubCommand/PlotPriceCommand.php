<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotPriceCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender->asVector3(), $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix ." §cU staat niet op een plot!");
            return;
        }

        if(!$plot instanceof BuyPlot)
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cDit plot heeft geen prijs!");
        }

        if(!$plot->isOwner($sender->getName()) && (!$plot->isBuyable() && is_null($plot->getOwner())))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU heeft geen toestemming om de prijs van dit plot te bekijken!");
            return;
        }

        $plotName = $plot->getName();
        $price = $plot->getPrice();
        $quickSellPrice = $plot->getQuickSellPrice();

        $line = str_repeat("-", 50);

        $sender->sendMessage("§3" . $line);
        $sender->sendMessage("§3§lNaam: §r§b$plotName");
        $sender->sendMessage("§3§lStandaardprijs: §r§b$price");
        $sender->sendMessage("§3§lSnelverkoop-prijs: §r§b$quickSellPrice");
        $sender->sendMessage("§3" . $line);

    }
}