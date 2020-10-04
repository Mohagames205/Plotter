<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;

class PlotSetCategory extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("category"));
        $this->setPermission("plotter.admin.setcategory");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getAtVector($sender, $sender->getLevel());
        $category = $args["category"];
        if(is_null($plot))
        {
            $sender->sendMessage("§4U staat niet op een plot");
            return;
        }

        $plot->setCategory($category);
    }
}