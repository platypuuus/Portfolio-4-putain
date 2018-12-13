<?php

class SQL
{
    public static $db_connection;


    public static function connect()
    {
        try {
            self::$db_connection = @new PDO('mysql:host=localhost;dbname=projets;charset=UTF8','root','');
            return true; //OK
        } catch (PDOException $e) {
            return $e;
        }
    }
    public static function close() {
        self::$db_connection = NULL;
    }
    public static function query($query_string) {
        return self::$db_connection->query($query_string);
    }

    public static function fetchAllAssoc($query_result) {
        $result = array();
        while($row=self::fetchAssoc($query_result)) {
            $result[]=$row;
        }
        return $result;
    }

    public static function fetchAssoc($query_result) {
        return $query_result->fetch(PDO::FETCH_ASSOC);
    }


}


$return = SQL::connect();


if ($return === true) {

    $store_query = "SELECT * FROM projets";

    $results = SQL::query($store_query);
    $results = SQL::fetchAllAssoc($results);

//print_r( $results);


    //replace the current module data with one sent, no check
//$results = SQL::fetch($results);

}

SQL::close();
//exit($return );

//jsonp_exit($results);

exit(json_encode($results));