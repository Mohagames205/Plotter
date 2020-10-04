<?php


namespace Mohamed205\Plotter\command;


use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotAddMemberCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotCreateCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotRemoveMemberCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotSetOwnerCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotWandCommand;
use pocketmine\command\CommandSender;

class PlotCommand extends BaseCommand
{

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("subcommand"));
        $this->registerSubCommand(new PlotAddMemberCommand("addmember", "Voegt een lid toe aan het plot"));
        $this->registerSubCommand(new PlotSetOwnerCommand("setowner", "Stelt de eigenaar in van het plot"));
        $this->registerSubCommand(new PlotRemoveMemberCommand("removemember", "Verwijderd de gegeven speler van het plot"));
        $this->registerSubCommand(new PlotCreateCommand("create", "Maakt een plot aan."));
        $this->registerSubCommand(new PlotWandCommand("wand", "Geeft jou een plotwand"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        foreach($this->getSubCommands() as $subCommand){
            $sender->sendMessage("§a/".$this->getName()." ".$subCommand->generateUsageMessage()." §7".$subCommand->getDescription());
        }
    }
}