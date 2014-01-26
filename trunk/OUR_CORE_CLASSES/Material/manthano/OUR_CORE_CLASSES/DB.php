<?php
class DB
{
    // Hold an instance of the class
    private static $instance;
    private $db;

    // The singleton method
    public function __construct()
    {
        $this->db=new PDO("mysql:localhost;dbname=manthanodb","root","",array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function execQuery($query)
    {
        $this->db->exec("use manthanodb");
        return $this->db->exec($query);
    }
}
?>