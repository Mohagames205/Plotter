<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotDeleteCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission("plotter.admin.delete");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender, $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot");
            return;
        }

        $plot->delete();
        $sender->sendMessage(PlotCommand::$prefix . " §aHet plot is succesvol verwijderd!");
    }
}