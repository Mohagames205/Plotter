<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
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
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        if(!$plot->isOwner($sender->getName()) && !$sender->hasPermission("plotter.admin.removemember"))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU bent niet bevoegd om deze command uit te voeren!");
            return;
        }

        if(!Server::getInstance()->hasOfflinePlayerData($member))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cDe opgegeven speler bestaat niet!");
            return;
        }

        if(!$plot->isMember($member))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cDeze speler is geen lid van het plotç");
            return;
        }

        $plot->removeMember($args["member"]);
        $sender->sendMessage(PlotCommand::$prefix . " §aDe speler is succesvol toegevoegd.");
    }
}