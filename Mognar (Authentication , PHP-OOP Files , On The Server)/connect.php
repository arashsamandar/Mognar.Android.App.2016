<?php
/*define('DB_SERVER','185.88.153.218');
define('DB_USERNAME','mognarco_rahpad');
define('DB_PASSWORD','6BDy+z96fVo!');
define('DB_DATABASE','mognarco_rahpad');*/

define('DB_SERVER','185.88.153.218');
define('DB_USERNAME','mognarco_rahpad');
define('DB_PASSWORD','6BDy+z96fVo!');
define('DB_DATABASE','mognarco_rahpad');


class DB_Class
{
    function __construct()
    {
        $connection = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD)
        or die('Oops connection error->'.mysql_error());
        mysql_select_db(DB_DATABASE,$connection)
        or die('Database error->'.mysql_error());

        mysql_query("SET NAMES 'utf8'", $connection);
        mysql_query("SET CHARACTER SET 'utf8'", $connection);
        mysql_query("SET character_set_connection = 'utf8'", $connection);

    }
}