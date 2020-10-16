<?php


namespace Mohamed205\Plotter\util;


use pocketmine\math\Vector3;

trait Math
{

    public function calculateMinLocation(Vector3 $loc1, Vector3 $loc2): Vector3
    {
        $minX = min($loc1->getFloorX(), $loc2->getFloorX());
        $minY = min($loc1->getFloorY(), $loc2->getFloorY());
        $minZ = min($loc1->getFloorZ(), $loc2->getFloorZ());

        return new Vector3($minX, $minY, $minZ);
    }

    public function calculateMaxLocation(Vector3 $loc1, Vector3 $loc2): Vector3
    {
        $maxX = max($loc1->getFloorX(), $loc2->getFloorX());
        $maxY = max($loc1->getFloorY(), $loc2->getFloorY());
        $maxZ = max($loc1->getFloorZ(), $loc2->getFloorZ());

        return new Vector3($maxX, $maxY, $maxZ);
    }
}