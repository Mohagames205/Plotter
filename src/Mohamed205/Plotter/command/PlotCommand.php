<?php


namespace Mohamed205\Plotter\command;


use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotAddMemberCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotBuyCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotCreateCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotDebugCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotDeleteCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotInfoCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotPriceCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotRemoveMemberCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotSellCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotSetCategory;
use Mohamed205\Plotter\command\PlotSubCommand\PlotSetOwnerCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotSetPriceCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotStopSellCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotTeleportCommand;
use Mohamed205\Plotter\command\PlotSubCommand\PlotWandCommand;
use Mohamed205\Plotter\util\Module;
use pocketmine\command\CommandSender;

class PlotCommand extends BaseCommand
{

    public static $prefix = "§7[§aPlot§2ter§7]";

    protected function prepare(): void
    {
        //$this->registerArgument(0, new RawStringArgument("subcommand"));
        $this->registerSubCommand(new PlotAddMemberCommand("addmember", "Voegt een lid toe aan het plot"));
        $this->registerSubCommand(new PlotSetOwnerCommand("setowner", "Stelt de eigenaar in van het plot"));
        $this->registerSubCommand(new PlotRemoveMemberCommand("removemember", "Verwijderd de gegeven speler van het plot"));
        $this->registerSubCommand(new PlotCreateCommand("create", "Maakt een plot aan."));
        $this->registerSubCommand(new PlotWandCommand("wand", "Geeft jou een plotwand"));
        $this->registerSubCommand(new PlotInfoCommand("info", "Toont info over het plot"));
        $this->registerSubCommand(new PlotSetCategory("setcategory", "Stelt de plot categorie in"));
        $this->registerSubCommand(new PlotBuyCommand("buy", "Koop het plot als het beschikbaar is!"));
        $this->registerSubCommand(new PlotSetPriceCommand("setprice", "Stelt de prijs van het plot in"));
        $this->registerSubCommand(new PlotSellCommand("sell", "Verkoop je plot!"));
        $this->registerSubCommand(new PlotDeleteCommand("delete", "Verwijdert het plot permanent."));
        $this->registerSubCommand(new PlotStopSellCommand("stopsell", "Stopt de verkoop van jouw plot."));
        $this->registerSubCommand(new PlotPriceCommand("price", "Toont de huidige prijs van het plot."));
        $this->registerSubCommand(new PlotTeleportCommand("tp", "Teleporteer naar een plot"));
        $this->registerSubCommand(new PlotDebugCommand("debug", "DEVELOPER COMMAND"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        foreach($this->getSubCommands() as $subCommand){
            $sender->sendMessage("§a/".$this->getName()." ".$subCommand->generateUsageMessage()." §7".$subCommand->getDescription());
        }
    }
}
