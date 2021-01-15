<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\BasicPlot;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotInfoCommand extends BaseSubCommand
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
            $sender->sendMessage(PlotCommand::$prefix ." §cU staat niet op een plot!");
            return;
        }

        $members = $plot->getMembers();
        $plotName = $plot->getName();
        $plotMembers = empty($members) ? "Geen leden" : implode(", ", $members);
        $plotOwner = $plot->getOwner() ?? "Gemeente";
        $category = $plot->getCategory() ?? "Geen categorie";

        $line = str_repeat("-", 50);

        $sender->sendMessage("§3" . $line);
        $sender->sendMessage("§3§lNaam: §r§b$plotName");
        $sender->sendMessage("§3§lOwner: §r§b$plotOwner");
        $sender->sendMessage("§3§lLeden: §r§b$plotMembers");
        $sender->sendMessage("§3§lCategorie: §r§b$category");

        if($plot instanceof BuyPlot && $plot->isBuyable())
        {
            $priceMessage = $plot->isSellingByPlayer() ? "§3§lSpeler verkoopprijs: §r§b" . $plot->getPlayerSellPrice()  . " euro": "§3§lVerkoopprijs: §r§b" . $plot->getPrice() . " euro";
            $sender->sendMessage($priceMessage);
            $sender->sendMessage("§3§lGebruik §b/plot buy §3om dit plot te kopen");
        }

        if(($plot instanceof BasicPlot && !$plot->hasOwner() && !$plot->hasMembers() || ($plot instanceof BuyPlot && !$plot->isBuyable() && !$plot->hasOwner() && !$plot->hasMembers())))
        {
            $sender->sendMessage("§3§lDit plot is niet te koop!");
        }


        $sender->sendMessage("§3" . $line);

    }
}