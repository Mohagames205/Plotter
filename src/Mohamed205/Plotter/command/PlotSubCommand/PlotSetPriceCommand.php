<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
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

        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        if($plot instanceof BuyPlot)
        {
            if($args["plot_price"] == 0)
            {
                $plot->convertToBasicPlot();
                $sender->sendMessage(PlotCommand::$prefix . " §aHet plot is niet meer koopbaar en is succesvol omgezet naar een BasicPlot.");
                return;
            }
            $plot->setBuyable(!$plot->hasOwner());
            $plot->setPrice($args["plot_price"]);
            $buyable = $plot->isBuyable() ? "koopbaar" : "niet koopbaar";
            $sender->sendMessage(PlotCommand::$prefix . " §aDe prijs is succesvol aangepast. Het plot is nu: §2" . $buyable);
        }
        else if($plot instanceof BasicPlot){
            $plot->convertToBuyPlot($args["plot_price"], !$plot->hasOwner(), null);
            $sender->sendMessage(PlotCommand::$prefix . " §aDe prijs is succesvol ingesteld en het plot is omgezet naar een BuyPlot.");
        }

    }
}