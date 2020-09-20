<?php


namespace Mohamed205\Plotter\command;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotAddMemberCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        //$this->registerArgument(0, new RawStringArgument("member"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        var_dump(Plot::getAtVector($sender->asVector3(), $sender->getLevel()));
    }
}