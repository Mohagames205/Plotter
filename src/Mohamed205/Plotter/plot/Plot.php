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


    private static $plotTypes;
    private string $name;
    private ?Member $owner;
    private Level $level;

    private Vector3 $minVector;
    private Vector3 $maxVector;

    private array $members;

    public function __construct(string $name, ?Member $owner, array $members, Vector3 $minVector, Vector3 $maxVector, Level $level, ...$args)
    {
        $this->name = $name;
        $this->owner = $owner;
        $this->members = $members;

        $this->minVector = $minVector;
        $this->maxVector = $maxVector;
        $this->level = $level;
    }

    public static function init()
    {
        self::registerPlotType(BuyPlot::class);
        self::registerPlotType(RentPlot::class);
    }

    public static function registerPlotType(string $className) : bool
    {
        $class = new \ReflectionClass($className);
        if(is_a($className, Plot::class, true) and !$class->isAbstract()){
            self::$plotTypes[$class->getShortName()] = $className;
            return true;
        }
        return false;
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
            plot_x1 <= :x AND
            plot_y1 <= :y AND
            plot_z1 <= :z AND
            plot_x2 >= :x AND
            plot_y2 >= :y AND
            plot_z2 >= :z AND
            plot_world = :world               
        ");

        $x = $vector3->getX();
        $y = $vector3->getY();
        $z = $vector3->getZ();

        $statement->bindParam("x", $x);
        $statement->bindParam("y", $y);
        $statement->bindParam("z", $z);
        $statement->bindParam("world", $worldname);

        $result = $statement->execute()->fetchArray(SQLITE3_ASSOC);

        if(!$result) return null;

        $plotType = $result["plot_type"];


        $minVector = new Vector3($result["plot_x1"], $result["plot_y1"], $result["plot_z1"]);
        $maxVector = new Vector3($result["plot_x2"], $result["plot_y2"], $result["plot_z2"]);
        $level = Server::getInstance()->getLevelByName($result["plot_world"]);

        /** @var Plot $plotType */;
        return new $plotType($result["plot_name"], $result["plot_owner"], json_decode($result["plot_members"], true), $minVector, $maxVector, $level, $result["plot_price"], $result["plot_is_sold"], $result["plot_billing_period"]);
    }

    public static function getByName() : ?Plot
    {

    }

    public function getById() : ?Plot
    {

    }

    public function getId() : int
    {

    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getOwner() : ?Member
    {

    }

    public function getMembers() : array
    {

    }

    public function setOwner() : void
    {

    }

    public function addMember() : bool
    {

    }

    public function removeMember() : bool
    {

    }

    public function delete()
    {

    }


}