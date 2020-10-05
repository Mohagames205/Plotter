<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class PlotRemoveMemberCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("member"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender, $sender->getLevel());
        $member = $args["member"];
        if(is_null($plot))
        {
            $sender->sendMessage("§4U staat niet op een plot");
            return;
        }

        if(!$plot->isOwner($sender->getName()))
        {
            $sender->sendMessage("§4U bent niet bevoegd om dit te doen!");
            return;
        }

        if(!Server::getInstance()->hasOfflinePlayerData($member))
        {
            $sender->sendMessage("§4De opgegeven speler bestaat niet!");
            return;
        }

        if(!$plot->isMember($member))
        {
            $sender->sendMessage("§4Deze speler is geen lid van het plot!");
        }

        $plot->addMember($args["member"]);
        $sender->sendMessage("§aDe speler is succesvol verwijderd!");
    }
}