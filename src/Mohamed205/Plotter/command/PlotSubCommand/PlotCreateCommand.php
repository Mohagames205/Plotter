<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use Mohamed205\Plotter\plot\BasicPlot;
use Mohamed205\Plotter\plot\Plot;
use Mohamed205\Plotter\plot\RentPlot;
use Mohamed205\Plotter\session\PlotCreateSession;
use Mohamed205\Plotter\session\Session;
use pocketmine\command\CommandSender;

class PlotCreateCommand extends \CortexPE\Commando\BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {

        $this->registerArgument(0, new RawStringArgument("plot_name"));
        $this->setPermission("plotter.admin.create");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $session = PlotCreateSession::getSession($sender);

        if($session->isReady())
        {
            Plot::create($args["plot_name"], $session->getMinLocation(), $session->getMaxLocation(), $sender->getLevel(), BasicPlot::class);
        }
    }
}