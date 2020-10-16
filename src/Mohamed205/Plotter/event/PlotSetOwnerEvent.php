<?php


namespace Mohamed205\Plotter\event;

use Mohamed205\Plotter\plot\Plot;
use pocketmine\event\Cancellable;

class PlotSetOwnerEvent extends PlotEvent implements Cancellable
{

    protected ?string $owner;
    protected Plot $plot;

    public function __construct(Plot $plot, ?string $owner)
    {
        $this->owner = $owner;
        $this->plot = $plot;
    }

    public function getPlot(): Plot
    {
        return $this->plot;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }
}