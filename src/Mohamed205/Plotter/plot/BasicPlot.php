<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\database\DatabaseManager;


class BasicPlot extends Plot
{
    public function convertToBuyPlot(?int $price, bool $isBuyable, ?int $playerSellPrice) : BuyPlot
    {
        $id = $this->getId();
        $isBuyable = (int)$isBuyable;
        $type = BuyPlot::class;
        $conn = DatabaseManager::getConnection();
        $stmt = $conn->prepare("UPDATE plots SET plot_type = :type, plot_price = :price, plot_sell_price = :sell_price, plot_is_buyable = :buyable WHERE id = :id");
        $stmt->bindParam("type", $type);
        $stmt->bindParam("id", $id);
        $stmt->bindParam("price", $price);
        $stmt->bindParam("sell_price", $playerSellPrice);
        $stmt->bindParam("buyable", $isBuyable);
        $stmt->execute();
        $stmt->close();

        return new BuyPlot($this->getName(), $this->getOwner(), $this->getMembers(), $this->getMinVector(), $this->getMaxVector(), $this->getLevel(), $this->getCategory(), $this->getMaxMembers(), $price, $isBuyable, $playerSellPrice);
    }

}