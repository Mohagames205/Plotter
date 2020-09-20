<?php


namespace Mohamed205\Plotter\database;


use Mohamed205\Plotter\Main;
use SQLite3;

class DatabaseManager
{

    private Main $plugin;
    private static SQLite3 $connection;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function initDatabase()
    {
        $database = new SQLite3($this->getPlugin()->getDataFolder() . "Plotter.db");
        $database->query("
            CREATE TABLE IF NOT EXISTS plots(
                id INTEGER PRIMARY KEY AUTOINCREMENT, 
                plot_name TEXT, 
                plot_owner TEXT DEFAULT NULL, 
                plot_members TEXT,
                plot_max_members INTEGER DEFAULT 10,
                plot_price INTEGER DEFAULT NULL,
                plot_is_sold INTEGER DEFAULT 1,
                plot_type TEXT NOT NULL,
                plot_world TEXT,
                plot_x1 INTEGER, 
                plot_y1 INTEGER, 
                plot_z1 INTEGER, 
                plot_x2 INTEGER, 
                plot_y2 INTEGER, 
                plot_z2 INTEGER)
        ");

        DatabaseManager::$connection = $database;
    }

    public function getPlugin()
    {
        return $this->plugin;
    }

    public static function getConnection() : SQLite3
    {
        return DatabaseManager::$connection;
    }






}