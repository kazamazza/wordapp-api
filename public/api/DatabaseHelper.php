<?php


class DatabaseHelper
{


    public $conn;

    public $queryCount = 0;

    public $queries = array();

    static private $instance;

    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "Aldridgews98ns");
        $this->conn->select_db("klira");
        $this->conn->set_charset("utf-8");

    }

    static public function instance()
    {
        if (!isset(self::$instance)) {
            $name = __CLASS__;
            self::$instance = new $name;
        }
        return self::$instance;
    }
}

