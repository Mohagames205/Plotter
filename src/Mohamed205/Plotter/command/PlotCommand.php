<?php


namespace Mohamed205\Plotter\command;


use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;

class PlotCommand extends BaseCommand
{

    protected function prepare(): void
    {
        $this->registerSubCommand(new PlotAddMemberCommand("addmember", "Voegt een lid toe aan het plot"));
        $this->registerSubCommand(new PlotSetOwnerCommand("setowner", "Stelt de eigenaar in van het plot"));
        $this->registerSubCommand(new PlotRemoveMemberCommand("removemember", "Verwijderd de gegeven speler van het plot"));

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $this->sendUsage();
    }
}