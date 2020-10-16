<?php


namespace Mohamed205\Plotter\event;

use Mohamed205\Plotter\plot\Plot;
use pocketmine\event\Cancellable;

class PlotDeleteEvent extends PlotEvent implements Cancellable
{

    protected Plot $plot;

    public function __construct(Plot $plot)
    {
        $this->plot = $plot;
    }

    public function getPlot(): Plot
    {
        return $this->plot;
    }
}