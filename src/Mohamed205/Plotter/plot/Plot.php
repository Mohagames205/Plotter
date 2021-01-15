<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\event\PlotAddMemberEvent;
use Mohamed205\Plotter\event\PlotDeleteEvent;
use Mohamed205\Plotter\event\PlotRemoveMemberEvent;
use Mohamed205\Plotter\event\PlotSetCategoryEvent;
use Mohamed205\Plotter\event\PlotSetOwnerEvent;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Server;
use ReflectionClass;
use ReflectionException;
use SQLite3Result;

abstract class Plot
{

    private static array $plotTypes = [];

    private $id;
    private $name;
    private $owner;
    private $level;

    private $minVector;
    private $maxVector;

    private $category;

    private $members;
    private $maxMembers;

    public function __construct(string $name, ?string $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ?string $category, int $maxMembers, ...$args)
    {
        $this->name = $name;
        $this->owner = $owner;
        $this->members = $members;

        $this->minVector = $minVector;
        $this->maxVector = $maxVector;
        $this->level = $level;

        $this->category = $category;

        $this->maxMembers = $maxMembers;

        $this->id = $this->fetchId();
    }

    public static function registerPlotType(string $plotClass)
    {
        try {
            $class = new ReflectionClass($plotClass);

            if (!in_array($plotClass, self::$plotTypes)) {
                self::$plotTypes[] = $class->getName();
                Server::getInstance()->getLogger()->info($class->getName() . " has been registered");
            }
        } catch (ReflectionException $exception) {
            Server::getInstance()->getLogger()->critical($plotClass . " does not exist and could not be registered");
        }
    }

    public static function init()
    {
        self::registerPlotType(BuyPlot::class);
        self::registerPlotType(BasicPlot::class);
    }


    public static function create(string $name, Vector3 $minVector, Vector3 $maxVector, Level $level, string $type = BuyPlot::class)
    {
        if(!in_array($type, Plot::$plotTypes))
        {
            throw new \InvalidArgumentException("This plottype is not registered and does not exist.");
        }

        $conn = DatabaseManager::getConnection();
        $members = json_encode([]);
        $world = $level->getFolderName();

        $statement = $conn->prepare("
        INSERT INTO plots (plot_name, plot_members,plot_type, plot_world, plot_x1, plot_y1, plot_z1, plot_x2, plot_y2, plot_z2) 
        values(:plot_name, :plot_members, :plot_type, :plot_world, :plot_x1, :plot_y1, :plot_z1, :plot_x2, :plot_y2, :plot_z2)");

        $minX = $minVector->getFloorX();
        $minY = $minVector->getFloorY();
        $minZ = $minVector->getFloorZ();

        $maxX = $maxVector->getFloorX();
        $maxY = $maxVector->getFloorY();
        $maxZ = $maxVector->getFloorZ();

        $statement->bindParam("plot_name", $name);
        $statement->bindParam("plot_members", $members);
        $statement->bindParam("plot_type", $type);
        $statement->bindParam("plot_world", $world);

        $statement->bindParam("plot_x1", $minX);
        $statement->bindParam("plot_y1", $minY);
        $statement->bindParam("plot_z1", $minZ);

        $statement->bindParam("plot_x2", $maxX);
        $statement->bindParam("plot_y2", $maxY);
        $statement->bindParam("plot_z2", $maxZ);

        $statement->execute();

    }

    /**
     * @param Position $position
     * @return Plot|null
     * @deprecated This function is for backwardscompatibility
     */
    public static function get(Position $position)
    {
        $level = $position->getLevel();
        $vector3 = $position->asVector3();

        return self::getAtVector($vector3, $level);
    }

    public static function getAtVector(Vector3 $vector3, Level $level)
    {
        $conn = DatabaseManager::getConnection();
        $worldname = $level->getFolderName();

        $statement = $conn->prepare("
            SELECT * FROM plots WHERE 
            (plot_x1 <= :x AND
            plot_z1 <= :z AND
            plot_x2 >= :x AND
            plot_z2 >= :z) AND
            ((plot_y1 <= :y AND
            plot_y2 >= :y) OR plot_y1 = plot_y2) AND
            plot_world = :world               
        ");

        $x = $vector3->getFloorX();
        $y = $vector3->getFloorY();
        $z = $vector3->getFloorZ();

        $statement->bindParam("x", $x);
        $statement->bindParam("y", $y);
        $statement->bindParam("z", $z);
        $statement->bindParam("world", $worldname);
        $result = $statement->execute();

        return self::fromDatabaseResult($result);
    }

    public static function getByName(string $name): ?Plot
    {
        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("SELECT * FROM plots WHERE lower(plot_name) = lower(:name)");
        $statement->bindParam("name", $name);
        $result = $statement->execute();

        return self::fromDatabaseResult($result);
    }

    public static function getByType(string $type)
    {
        if(!in_array($type, Plot::$plotTypes))
        {
            throw new \InvalidArgumentException("This plottype is not registered and does not exist.");
        }

        $db = DatabaseManager::getConnection();
        $statement = $db->prepare("SELECT * FROM plots WHERE plot_type = :type");
        $statement->bindParam("type", $type);
        $result = $statement->execute();

        $plots = [];
        while ($row = $result->fetchArray())
        {
            $plot = self::getById($row["id"]);
            if(!is_null($plot))
            {
                $plots[] = $plot;
            }
        }

        return $plots;
    }

    public static function getById(int $id): ?Plot
    {
        $connection = DatabaseManager::getConnection();

        $statement = $connection->prepare("SELECT * FROM plots WHERE id = :id");
        $statement->bindParam("id", $id);
        $result = $statement->execute();

        return self::fromDatabaseResult($result);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMinVector(): Vector3
    {
        return $this->minVector;
    }

    public function getMaxVector(): Vector3
    {
        return $this->maxVector;
    }


    private function fetchId(): int
    {
        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("SELECT id FROM plots WHERE 
            plot_x1 = :x1 AND
            plot_y1 = :y1 AND
            plot_z1 = :z1 AND
            plot_x2 = :x2 AND
            plot_y2 = :y2 AND
            plot_z2 = :z2");

        $minVector = $this->getMinVector();
        $maxVector = $this->getMaxVector();

        $minX = $minVector->getFloorX();
        $minY = $minVector->getFloorY();
        $minZ = $minVector->getFloorZ();

        $maxX = $maxVector->getFloorX();
        $maxY = $maxVector->getFloorY();
        $maxZ = $maxVector->getFloorZ();

        $statement->bindParam("x1", $minX);
        $statement->bindParam("y1", $minY);
        $statement->bindParam("z1", $minZ);

        $statement->bindParam("x2", $maxX);
        $statement->bindParam("y2", $maxY);
        $statement->bindParam("z2", $maxZ);


        $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);
        $statement->close();

        return $result["id"];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function isOwner(string $player): bool
    {
        return strtolower($player) == strtolower($this->getOwner());
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function setOwner(?string $name): void
    {
        if (!is_null($name) && !Server::getInstance()->hasOfflinePlayerData($name)) return;

        $ev = new PlotSetOwnerEvent($this, $name);
        $ev->call();
        if ($ev->isCancelled()) return;

        $id = $this->getId();
        $conn = DatabaseManager::getConnection();

        $statement = $conn->prepare("UPDATE plots SET plot_owner = :name WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("name", $name);
        $statement->execute();
        $statement->close();
    }

    public function addMember(string $member): bool
    {
        if (count($this->getMembers()) >= $this->getMaxMembers() || !Server::getInstance()->hasOfflinePlayerData($member)) {
            return false;
        }

        $ev = new PlotAddMemberEvent($this, $member);
        $ev->call();
        if ($ev->isCancelled()) return false;

        $id = $this->getId();
        $members = $this->getMembers();
        $members[] = strtolower($member);
        $this->members = $members;

        $databaseMembers = json_encode($members);

        $connection = DatabaseManager::getConnection();
        $statement = $connection->prepare("UPDATE plots SET plot_members = :members WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("members", $databaseMembers);
        $statement->execute();
        $statement->close();

        return true;
    }

    public function isMember(string $member)
    {
        return in_array(strtolower($member), $this->getMembers());
    }

    /**
     * @return int
     */
    public function getMaxMembers(): int
    {
        return $this->maxMembers;
    }

    public function hasOwner(): bool
    {
        return !is_null($this->getOwner());
    }

    public function setMaxMembers(int $amount): void
    {
        $id = $this->getId();

        $connection = DatabaseManager::getConnection();
        $statement = $connection->prepare("UPDATE plots SET plot_max_members = :max WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("max", $amount);
        $statement->execute();
        $statement->close();
    }

    public function hasMembers(): bool
    {
        return count($this->getMembers()) > 0;
    }


    public function removeMember(string $member): bool
    {
        $member = strtolower($member);
        if (!in_array($member, $this->getMembers())) {
            return false;
        }

        $ev = new PlotRemoveMemberEvent($this, $member);
        $ev->call();
        if ($ev->isCancelled()) return false;

        $old_members = $this->getMembers();
        $this->members = array_diff($old_members, array($member));
        $jsonMembers = json_encode($this->getMembers());
        $id = $this->getId();

        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("UPDATE plots SET plot_members = :members WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("members", $jsonMembers);
        $statement->execute();
        $statement->close();
        return true;

    }

    public function reset(): void
    {
        $this->setOwner(null);
        $this->removeAllMembers();
    }

    public function removeAllMembers(): void
    {
        foreach ($this->getMembers() as $member) {
            $this->removeMember($member);
        }
    }

    public function delete(): void
    {
        $ev = new PlotDeleteEvent($this);
        $ev->call();
        if ($ev->isCancelled()) return;

        $id = $this->getId();
        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("DELETE FROM plots WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->execute();
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $ev = new PlotSetCategoryEvent($this, $category);
        $ev->call();
        if ($ev->isCancelled()) return;

        $id = $this->getId();

        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("UPDATE plots SET plot_category = :cat WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("cat", $category);
        $statement->execute();
        $statement->close();
    }

    protected static function fromDatabaseResult(SQLite3Result $SQLite3Result): ?Plot
    {

        $result = $SQLite3Result->fetchArray(SQLITE3_ASSOC);

        if (!$result) return null;

        $plotType = $result["plot_type"];


        $minVector = new Vector3($result["plot_x1"], $result["plot_y1"], $result["plot_z1"]);
        $maxVector = new Vector3($result["plot_x2"], $result["plot_y2"], $result["plot_z2"]);
        $level = Server::getInstance()->getLevelByName($result["plot_world"]);

        //$owner = is_null($result["plot_owner"]) ? null : new Member($result["plot_owner"]);
        $owner = $result["plot_owner"];
        $jsonMembers = json_decode($result["plot_members"], true);/** @var Plot $plotType */
        return new $plotType($result["plot_name"], $owner, $jsonMembers, $minVector, $maxVector, $level, $result["plot_category"], $result["plot_max_members"], $result["plot_price"], $result["plot_is_buyable"], $result["plot_sell_price"]);

    }

    public static function convertPlotTo(Plot $plot, string $type)
    {
        if(!in_array($type, Plot::$plotTypes))
        {
            throw new \InvalidArgumentException("This plottype is not registered and does not exist.");
        }

        $conn = DatabaseManager::getConnection();
        $id = $plot->getId();

        $stmt = $conn->prepare("UPDATE plots SET plot_type = :type WHERE id = :id");
        $stmt->bindParam("type", $type);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->close();

    }
}