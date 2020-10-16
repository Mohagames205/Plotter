<?php


namespace Mohamed205\Plotter\event;


use Mohamed205\Plotter\plot\Plot;
use pocketmine\event\Event;

abstract class PlotEvent extends Event
{

    public abstract function getPlot(): Plot;


}