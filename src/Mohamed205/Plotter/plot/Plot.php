<?php


namespace Mohamed205\Plotter\plot;


use Mohamed205\Plotter\database\DatabaseManager;
use Mohamed205\Plotter\member\Member;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

abstract class Plot
{


    public function __construct()
    {

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