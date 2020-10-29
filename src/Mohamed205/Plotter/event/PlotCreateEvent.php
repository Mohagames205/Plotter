<?php


namespace Mohamed205\Plotter\event;

use Mohamed205\Plotter\plot\Plot;

class PlotCreateEvent extends PlotEvent
{

    protected $plot;

    public function __construct(Plot $plot)
    {
        $this->plot = $plot;
    }

    public function getPlot(): Plot
    {
        return $this->plot;
    }
}