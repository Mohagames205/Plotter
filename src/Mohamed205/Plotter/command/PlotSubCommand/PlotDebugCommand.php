<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotDebugCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission("plotter.admin.debug");
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
        $plotType = get_class($plot);
        $id = $plot->getId();

        $buyable = "Geen BuyPlot";

        if($plot instanceof BuyPlot)
        {
            $buyable = (int)$plot->isBuyable();
        }


        $line = str_repeat("-", 50);

        $sender->sendMessage("§3" . $line);
        $sender->sendMessage("§3§lNaam: §r§b$plotName");
        $sender->sendMessage("§3§lOwner: §r§b$plotOwner");
        $sender->sendMessage("§3§lLeden: §r§b$plotMembers");
        $sender->sendMessage("§3§lCategorie: §r§b$category");
        $sender->sendMessage("§3§lPlotType: §r§b$plotType");
        $sender->sendMessage("§3§lPlotID: §r§b$id");
        $sender->sendMessage("§3§lisBuyable: §r§b$buyable");
        $sender->sendMessage("§3" . $line);
    }
}