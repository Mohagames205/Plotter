<?php


namespace Mohamed205\Plotter\event;

use Mohamed205\Plotter\plot\Plot;
use pocketmine\event\Cancellable;

class PlotAddMemberEvent extends PlotEvent implements Cancellable
{

    protected $member;
    protected $plot;

    public function __construct(Plot $plot, string $member)
    {
        $this->member = $member;
        $this->plot = $plot;
    }

    public function getPlot(): Plot
    {
        return $this->plot;
    }

    public function getMember(): string
    {
        return $this->member;
    }


}