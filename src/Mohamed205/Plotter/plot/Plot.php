<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\Main;
use Mohamed205\Plotter\member\Member;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Server;

abstract class Plot
{

    private int $id;
    private string $name;
    private ?string $owner;
    private Level $level;

    private Vector3 $minVector;
    private Vector3 $maxVector;

    private ?string $category;

    private array $members;

    public function __construct(string $name, ?string $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ?string $category, ...$args)
    {
        $this->name = $name;
        $this->owner = $owner;
        $this->members = $members;

        $this->minVector = $minVector;
        $this->maxVector = $maxVector;
        $this->level = $level;

        $this->category = $category;

        $this->id = $this->fetchId();
    }


    public static function create(string $name, Vector3 $minVector, Vector3 $maxVector, Level $level, string $type = BuyPlot::class)
    {
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

        $x = $vector3->getX();
        $y = $vector3->getY();
        $z = $vector3->getZ();

        $statement->bindParam("x", $x);
        $statement->bindParam("y", $y);
        $statement->bindParam("z", $z);
        $statement->bindParam("world", $worldname);
        $result = $statement->execute();

        return self::fromDatabaseResult($result);

    }

    public static function getByName(string $name) : ?Plot
    {
        $name = strtolower($name);
        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("SELECT * FROM plots WHERE plot_name = :name");
        $statement->bindParam("name", $name);
        $result = $statement->execute();

        return self::fromDatabaseResult($result);
    }

    public static function getById(int $id) : ?Plot
    {
        $connection = DatabaseManager::getConnection();

        $statement = $connection->prepare("SELECT * FROM plots WHERE id = :id");
        $statement->bindParam("id", $id);
        $result = $statement->execute();

        return self::fromDatabaseResult($result);
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getMinVector() : Vector3
    {
        return $this->minVector;
    }

    public function getMaxVector() : Vector3
    {
        return $this->maxVector;
    }


    private function fetchId() : int
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
        $conn->close();

        return $result["id"];
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getOwner() : ?string
    {
        return $this->owner;
    }

    public function isOwner(string $player) : bool
    {
        return strtolower($player) == strtolower($this->getOwner());
    }

    public function getMembers() : array
    {
        return $this->members;
    }

    public function getLevel() : Level
    {
        return $this->level;
    }

    public function setOwner(?string $name) : void
    {
        $id = $this->getId();
        $conn = DatabaseManager::getConnection();

        $statement = $conn->prepare("UPDATE plots SET plot_owner = :name WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("name", $name);
        $statement->execute();
        $statement->close();
    }

    public function addMember(string $member) : bool
    {
        if(!Server::getInstance()->hasOfflinePlayerData($member))
        {
            return false;
        }

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
    }

    public function isMember(string $member)
    {
        return in_array(strtolower($member), $this->members);
    }

    /**
     * @return int
     * TODO: Dit afmaken
     */
    public function getMaxMembers() : int
    {

    }

    public function removeMember(string $member) : bool
    {
        $member = strtolower($member);
        if(!in_array($member, $this->members))
        {
            return false;
        }

        unset($this->members[$member]);
        $members = $this->members;
        $id = $this->getId();

        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("UPDATE plots SET plot_member = :members WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->bindParam("members", $members);
        $statement->execute();
        $statement->close();
        return true;

    }

    public function delete() : void
    {
        $id = $this->getId();
        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("DELETE FROM plots WHERE id = :id");
        $statement->bindParam("id", $id);
        $statement->execute();
    }

    public function getCategory() : string
    {
        return $this->category;
    }

    public function setCategory(string $category) : void
    {
        $id = $this->getId();

        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("UPDATE plots SET plot_category = :cat WHERE plot_id = :plot_id");
        $statement->bindParam("id", $id);
        $statement->bindParam("cat", $category);
        $statement->execute();
        $statement->close();
    }

    protected static function fromDatabaseResult(\SQLite3Result $SQLite3Result) : ?Plot
    {

        $result = $SQLite3Result->fetchArray(SQLITE3_ASSOC);

        if(!$result) return null;

        $plotType = $result["plot_type"];


        $minVector = new Vector3($result["plot_x1"], $result["plot_y1"], $result["plot_z1"]);
        $maxVector = new Vector3($result["plot_x2"], $result["plot_y2"], $result["plot_z2"]);
        $level = Server::getInstance()->getLevelByName($result["plot_world"]);

        //$owner = is_null($result["plot_owner"]) ? null : new Member($result["plot_owner"]);
        $owner = $result["plot_owner"];
        $jsonMembers = json_decode($result["plot_members"], true);

        /** @var Plot $plotType */;
        return new $plotType($result["plot_name"], $owner, $jsonMembers, $minVector, $maxVector, $level, $result["plot_category"], $result["plot_price"], $result["plot_is_sold"], $result["plot_billing_period"]);

    }


}