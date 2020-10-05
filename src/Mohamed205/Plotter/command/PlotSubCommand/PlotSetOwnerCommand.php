<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class PlotSetOwnerCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("owner"));
        $this->setPermission("plotter.admin.setowner");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender, $sender->getLevel());
        $owner = $args["owner"];
        if(is_null($plot))
        {
            $sender->sendMessage("§4U staat niet op een plot");
            return;
        }

        if(!Server::getInstance()->hasOfflinePlayerData($owner))
        {
            $sender->sendMessage("§4De opgegeven speler bestaat niet!");
            return;
        }

        $plot->setOwner($args["owner"]);
        $sender->sendMessage("§aDe speler is nu eigenaar van het plot");
    }
}