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
        self::$connection = new SQLite3($this->getPlugin()->getDataFolder() . "Plotter.db");
        self::$connection->query("
            CREATE TABLE IF NOT EXISTS plots(
                id INTEGER PRIMARY KEY AUTOINCREMENT, 
                plot_name TEXT, 
                plot_owner TEXT DEFAULT NULL, 
                plot_members TEXT,
                plot_max_members INTEGER DEFAULT 10,
                plot_price INTEGER DEFAULT NULL,
                plot_sell_price INTEGER DEFAULT NULL,
                plot_is_buyable INTEGER DEFAULT 1,
                plot_billing_period INTEGER DEFAULT NULL,
                plot_type TEXT NOT NULL,
                plot_category TEXT DEFAULT NULL,
                plot_world TEXT,
                plot_x1 INTEGER, 
                plot_y1 INTEGER,
                plot_z1 INTEGER, 
                plot_x2 INTEGER, 
                plot_y2 INTEGER, 
                plot_z2 INTEGER)
        ");
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