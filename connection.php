<?php
class DatabaseConnection {
    private static $conn;
    public static function getConnection() {
        if (!self::$conn) {
            $serverName = "localhost";
            $username = "u284987667_trackerDev";
            $pass = "L1~r&!rpG3=";
            $dataBase = "u284987667_trackerAdsanim";

            self::$conn = new mysqli($serverName, $username, $pass, $dataBase);

            if (self::$conn->connect_error) {
                echo json_encode(array("error" => "database connection error"));
                die;

            }
        }

        return self::$conn;
    }
}

?>
