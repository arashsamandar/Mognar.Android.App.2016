<?php
include('jdf.php');
// MR AMIR KHALAJI YOU HAVE TO CHANGE THESE IN ORDER TO CONNECT TO YOUR DATABASE AND ACCESS MOGA ON IT .
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "samandar";
$dbname = "moga";


	if($_SERVER['REQUEST_METHOD']=='GET'){

        $id  = $_GET['id'];

        $sql = "SELECT * FROM users WHERE id='".$id."'";

        $r = mysqli_query($con,$sql);

        $res = mysqli_fetch_array($r);

        $result = array();

        array_push($result,array(
                "name"=>$res['name'],
                "lastname"=>$res['lastname'],
                "username"=>$res['username']
            )
        );

        echo json_encode(array("result"=>$result));

        mysqli_close($con);

    }        //__________________________________________________________________________________________________________________________________________________________