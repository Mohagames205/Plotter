<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\BasicPlot;
use Mohamed205\Plotter\plot\BuyPlot;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotSetPriceCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new IntegerArgument("plot_price"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

        $plot = Plot::getAtVector($sender->asVector3(), $sender->getLevel());

        if($plot instanceof BuyPlot)
        {
            $plot->setPrice($args["plot_price"]);
        }
        else if($plot instanceof BasicPlot){
            $plot->convertToBuyPlot($args["plot_price"], true, null);
        }

    }
}