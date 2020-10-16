<?php


namespace Mohamed205\Plotter\event;

use Mohamed205\Plotter\plot\Plot;
use pocketmine\event\Cancellable;

class PlotSetCategoryEvent extends PlotEvent implements Cancellable
{

    protected ?string $category;
    protected Plot $plot;

    public function __construct(Plot $plot, ?string $category)
    {
        $this->category = $category;
        $this->plot = $plot;
    }

    public function getPlot(): Plot
    {
        return $this->plot;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }


}