<?php

$dbhost = "185.88.153.218";
$dbuser = "mognarco_rahpad";
$dbpass = "6BDy+z96fVo!";
$dbname = "mognarco_rahpad";


$connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("connection was not succesfull");
mysqli_set_charset($connection,"utf8");
echo "</br>";
echo "</br>";
echo "----------------------------create_secretariat-----------------------------";
echo "________________________THIS ARE USERS_______________________</br>";

if ($result1=mysqli_query($connection,"SELECT username,password FROM users")) {
    $rowcount = mysqli_num_rows($result1);
    if ($rowcount > 0) {
        while ($row = $result1->fetch_assoc()) {
            echo "</br>";
            echo 'UserName : ' . $row['username'] . ' Password :' . $row['password'];
            echo "</br>";

        }
    }
}

echo "----------------------------Log_Information-----------------------------";
echo "</br>";
if ($result2=mysqli_query($connection,"select username,computerName,discription,present_time_current from log_information")) {
    $rowcount = mysqli_num_rows($result1);
    if ($rowcount > 0) {
        while ($row = $result2->fetch_assoc()) {
            echo "</br>" . "UserName::  " . " " . $row['username'] . "__" . "Computername : " . $row['computerName'] . "___" .  " Discription : " . $row['discription'] . "__" . " Time-Current : " . " " . $row['present_time_current'] . "</br></br>";
        }
    } else {echo "rowcount is 0";echo "</br>";}
} else {echo "didnt even make a connection";}

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}
echo "</br>";
echo "</br>";

//-----------------------------IT WORKS , BELLOW CODE ( WORKS )-------------------------------------

echo "</br>";

$connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("connection was not succesfull");
mysqli_set_charset($connection,"utf8");

$username = "nazi";

if ($result1=mysqli_query($connection,"SELECT name,lastname,gender,password,username FROM users")) {
    $rowcount = mysqli_num_rows($result1);
    if ($rowcount > 0) {
        while ($row = $result1->fetch_assoc()) {
            echo "password :" . $row["password"] . " " . "username :" . $row["username"];echo "</br></br>";
            echo "نام کاربری : " . $row['name'] . " نام خانوادگی : " .  $row['lastname'] . " جنسیت : " . $row['gender'] . "</br></br>";
        }
    }
}