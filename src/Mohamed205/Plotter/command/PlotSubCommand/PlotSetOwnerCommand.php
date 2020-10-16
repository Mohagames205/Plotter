<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class PlotSetOwnerCommand extends BaseSubCommand
{

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("owner", true));
        $this->setPermission("plotter.admin.setowner");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender, $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        if(!isset($args["owner"]))
        {
            $plot->setOwner(null);
            $sender->sendMessage(PlotCommand::$prefix . " §aDe eigenaar van het plot is succesvol verwijderd.");
            return;
        }

        $owner = $args["owner"];

        if(!Server::getInstance()->hasOfflinePlayerData($owner))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cDe opgegeven speler bestaat niet!");
            return;
        }

        $plot->setOwner($args["owner"]);
        $sender->sendMessage(PlotCommand::$prefix . " §aDe speler is nu eigenaar van het plot.");
    }
}