<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\Main;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Server;

class BuyPlot extends Plot implements EconomyPlot
{

    private $price;
    private $isBuyable;
    private $playerSellPrice;

    public function __construct(string $name, ?string $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ?string $category, int $maxMembers, ?int $price, bool $isBuyable, ?int $playerSellPrice)
    {
        $this->price = $price;
        $this->isBuyable = $isBuyable;
        $this->playerSellPrice = $playerSellPrice;

        parent::__construct($name, $owner, $members, $minVector, $maxVector, $level, $category, $maxMembers);
    }

    public function buy(string $player): bool
    {
        if (!$this->isBuyable() || !Server::getInstance()->hasOfflinePlayerData($player)) {
            return false;
        }

        $eco = Main::getEco();

        if ($this->isSellingByPlayer()) {
            $eco->removeFromBalance($player, $this->getPlayerSellPrice());
            $eco->addToBalance($this->getOwner(), $this->getPlayerSellPrice());
            $this->reset();
            $this->setOwner($player);
            $this->setBuyable(false);
            $this->setPlayerSellPrice(null);
            return true;
        }

        $this->reset();
        $eco->removeFromBalance($player, $this->getPrice());
        $this->setOwner($player);
        $this->setBuyable(false);
        return true;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price)
    {
        if (is_int($price) && $price < 0) return;

        $this->price = $price;

        $id = $this->getId();
        $conn = DatabaseManager::getConnection();
        $stmt = $conn->prepare("UPDATE plots SET plot_price = :price WHERE id = :id");
        $stmt->bindParam("id", $id);
        $stmt->bindParam("price", $price);

        $stmt->execute();
        $stmt->close();
    }

    public function getPlayerSellPrice(): ?int
    {
        return $this->playerSellPrice;
    }

    public function setPlayerSellPrice(?int $price)
    {
        $this->playerSellPrice = $price;

        $id = $this->getId();
        $conn = DatabaseManager::getConnection();
        $stmt = $conn->prepare("UPDATE plots SET plot_sell_price = :price WHERE id = :id");
        $stmt->bindParam("id", $id);
        $stmt->bindParam("price", $price);
        $stmt->execute();
    }

    public function stopSellingByPlayer()
    {
        if ($this->isSellingByPlayer()) {
            $this->setBuyable(false);
            $this->setPlayerSellPrice(null);
        }
    }

    public function sell(int $price)
    {
        $this->setPlayerSellPrice($price);
        $this->setBuyable();
        $this->removeAllMembers();
    }

    public function getQuickSellPrice(): int
    {
        $sellPercentage = Main::getInstance()->getConfig()->get("quicksell_percentage");
        return $this->getPrice() * $sellPercentage / 100;

    }

    public function quickSell()
    {
        Main::getEco()->addToBalance($this->getOwner(), $this->getQuickSellPrice());
        $this->reset();
    }

    public function isSellingByPlayer(): bool
    {
        return !is_null($this->getOwner()) && !is_null($this->getPlayerSellPrice()) && $this->isBuyable();
    }

    public function setBuyable(bool $isBuyable = true)
    {
        $this->isBuyable = $isBuyable;

        $isBuyable = (int)$isBuyable;

        $id = $this->getId();
        $conn = DatabaseManager::getConnection();
        $stmt = $conn->prepare("UPDATE plots SET plot_is_buyable = :buyable WHERE id = :plot_id");
        $stmt->bindParam("plot_id", $id);
        $stmt->bindParam("buyable", $isBuyable);
        $stmt->execute();
    }

    /** TODO: LOGICA aanpassen */
    public function setOwner(?string $name): void
    {
        $this->setBuyable(is_null($name));
        $this->setPlayerSellPrice(null);
        parent::setOwner($name);
    }

    public function isBuyable(): bool
    {
        return $this->isBuyable;
    }

    public function convertToBasicPlot() : BasicPlot
    {

        $this->setPrice(null);
        $this->setBuyable(false);
        $this->setPlayerSellPrice(null);

        $id = $this->getId();
        $type = BasicPlot::class;
        $conn = DatabaseManager::getConnection();
        $stmt = $conn->prepare("UPDATE plots SET plot_type = :type WHERE id = :id");
        $stmt->bindParam("type", $type);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->close();

        return new BasicPlot($this->getName(), $this->getOwner(), $this->getMembers(), $this->getMinVector(), $this->getMaxVector(), $this->getLevel(), $this->getCategory(), $this->getMaxMembers());
    }

}