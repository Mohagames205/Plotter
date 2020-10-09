<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotSellCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new IntegerArgument("price"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender->asVector3(), $sender->getLevel());

        if(is_null($plot))
        {
            $sender->sendMessage("§cU staat niet op een plot");
            return;
        }

        if(!$plot->isOwner($sender->getName()))
        {
            $sender->sendMessage("§cU heeft geen toestemming om dit plot te verkopen");
            return;
        }

        if(!$plot instanceof BuyPlot)
        {
            $sender->sendMessage("§cU kan dit plot niet verkopen");
        }

        $plot->sell($args["price"]);
    }
}