<?php


namespace Mohamed205\Plotter\command\PlotSubCommand;


use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use Mohamed205\Plotter\command\PlotCommand;
use Mohamed205\Plotter\plot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;

class PlotTeleportCommand extends BaseSubCommand
{

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        $this->setPermission("plotter.admin.teleport");
        $this->registerArgument(0, new RawStringArgument("plot_name"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $plot = Plot::getByName($args["plot_name"]);
        if(is_null($plot))
        {
            $sender->sendMessage(PlotCommand::$prefix . " §cU staat niet op een plot!");
            return;
        }

        $minVector = $plot->getMinVector();
        $pos = new Position($minVector->getX(), 256, $minVector->getZ(), $plot->getLevel());
        /** @var Player $sender */
        $sender->teleport($pos);

    }
}