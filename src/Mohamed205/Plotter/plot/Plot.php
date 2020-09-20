<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\member\Member;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

abstract class Plot
{


    private static $plotTypes;

    public function __construct()
    {

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


    public static function create(string $name, AxisAlignedBB $axisAlignedBB, Level $level, string $type = BuyPlot::class)
    {
        $conn = DatabaseManager::getConnection();
        $members = json_encode([]);
        $world = $level->getFolderName();


        $statement = $conn->prepare("
        INSERT INTO plots (plot_name, plot_members,plot_type, plot_world, plot_x1, plot_y1, plot_z1, plot_x2, plot_y2, plot_z2) 
        values(:plot_name, :plot_members, :plot_type, :plot_world, :plot_x1, :plot_y1, :plot_z1, :plot_x2, :plot_y2, :plot_z2)");

        $statement->bindParam("plot_name", $name);
        $statement->bindParam("plot_members", $members);
        $statement->bindParam("plot_type", $type);
        $statement->bindParam("plot_world", $world);

        $statement->bindParam("plot_x1", $members);
        $statement->bindParam("plot_y1", $members);
        $statement->bindParam("plot_z1", $members);

        $statement->bindParam("plot_x2", $members);
        $statement->bindParam("plot_y2", $members);
        $statement->bindParam("plot_z2", $members);
    }

    public static function getByVector(Vector3 $vector3, Level $level) : ?Plot
    {
        $conn = DatabaseManager::getConnection();
        $statement = $conn->prepare("
            SELECT * FROM plots WHERE 
            plot_x1 <= :x AND
            plot_y1 <= :y AND
            plot_z1 <= :z AND
            plot_x2 >= :x AND
            plot_y2 >= :y AND
            plot_z2 >= :z
        ");

        $x = $vector3->getFloorX();
        $y = $vector3->getFloorY();
        $z = $vector3->getFloorZ();

        $statement->bindParam("x", $x);
        $statement->bindParam("y", $y);
        $statement->bindParam("z", $z);
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