<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

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
            $sender->sendMessage("§4U staat niet op een plot");
            return;
        }

        $members = $plot->getMembers();
        $plotName = $plot->getName();
        $plotMembers = empty($members) ? "Geen members" : implode(", ", $members);
        $plotOwner = $plot->getOwner() ?? "Geen eigenaar";
        $category = $plot->getCategory() ?? "Geen categorie";

        $sender->sendMessage("§cNaam: §c$plotName");
        $sender->sendMessage("§cOwner: §4$plotOwner");
        $sender->sendMessage("§cLeden: §4$plotMembers");
        $sender->sendMessage("§cCategorie: §4$category");

    }
}