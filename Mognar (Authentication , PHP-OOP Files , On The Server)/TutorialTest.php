<?php
include('jdf.php');
$dbhost = "185.88.153.218";
$dbuser = "mognarco_rahpad";
$dbpass = "6BDy+z96fVo!";
$dbname = "mognarco_rahpad";

$connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die("connection was not succesfull");
mysqli_set_charset($connection,"utf8");

function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = $_POST["username_KEY"];
    $devicename = $_POST["devicename_KEY"];
    $useripcode = $_POST["userIp_KEY"];
    $codevalue = (int)$_POST["codevalue_KEY"];
    $secreteariateid = (int)$_POST["secreteariate_KEY"];
    $descriptions = $_POST["description_KEY"];
    $jalali_hour = jdate("H:i:s a");
    $jalali_date = jdate("Y/m/d");
    $password_temp = $_POST["userpass_KEY"];
    $password = md5($password_temp);


    $queryResultArray = array();

    if (isset($_POST["username_KEY"]) || isset($_POST["userpass_KEY"])) {
        $username = $_POST["username_KEY"];
        $queryResultArray[1] = "";
        if ($result1=mysqli_query($connection,"SELECT id_secretariate FROM users WHERE username like '$username'"))
        {  $rowcount=mysqli_num_rows($result1);
            if($rowcount > 0) {
                while ($row = $result1->fetch_assoc()) {$queryResultArray[0] = $row["id_secretariate"];}
                if ($result2 = mysqli_query($connection, "SELECT title_secretariat FROM create_secretariat WHERE id=$queryResultArray[0]")) {
                    $rowcount2 = mysqli_num_rows($result2);
                    if ($rowcount2 > 0) {
                        while ($row = $result2->fetch_assoc()) { $queryResultArray[1] = $row["title_secretariat"];}
                    } else {$queryResultArray[2] = "if4fail"; }
                } else {$queryResultArray[2] = "if3fail";}
            } else {$queryResultArray[2] = "if2fail";}
        } else {$queryResultArray[2] = "if1fail";}
//THIS IS THE CONTINUE OF ABOVE CODE : IF ABOVE FAILS THEN :
//--------------------------------------------------------------------fase two--------------------------------------------------------------------------------------------
        if(IsNullOrEmptyString($queryResultArray[1])) {
            $queryResultArray[3] = "";
            $queryResultArray[4] = "";
            $queryResultArray[6] = "";
            if ($joinquery = "SELECT permissions_secretariat.user_id,users.id_secretariate,users.username FROM permissions_secretariat INNER JOIN
 users ON permissions_secretariat.user_id=users.id AND permissions_secretariat.login=1 AND users.username = '$username'") {
                $mresult = mysqli_query($connection, $joinquery);
                $rowcounts = mysqli_num_rows($mresult);
                if ($rowcounts > 0) {
                    while ($row = $mresult->fetch_assoc()) {
                        $queryResultArray[3] = $row["user_id"];
                        $queryResultArray[4] = $row["id_secretariate"];
                    }
                } else {
                    $queryResultArray[5] = "FailInJoinQuery";
                }

                if (!IsNullOrEmptyString($queryResultArray[3]) && !IsNullOrEmptyString($queryResultArray[4]) && $queryResultArray[3] == $queryResultArray[4]) {
                    if ($joinquery2 = "SELECT title_secretariat FROM create_secretariat") {
                        $mresult2 = mysqli_query($connection, $joinquery2);
                        $rowcounts2 = mysqli_num_rows($mresult);
                        if ($rowcounts2 > 0) {
                            while ($row1 = $mresult2->fetch_assoc()) {
                                $queryResultArray[6] = $row1["title_secretariat"];
                                echo $queryResultArray[6] . "fasetwo,";
                            }
                        } else {$queryResultArray[2] = "fase2-if5failed";}
                    } else {$queryResultArray[2] = "fase2-if4failed";}
                } else {$queryResultArray[2] = "fase2-if3failed";}
            } else {$queryResultArray[2] = "fase2-if2failed";}

        } else {echo $queryResultArray[1] . "faseone,";}

        //THEN A REQUEST IS COMING FROM ANDROID DEVICE
        //__________________________________________________________________________________________________________________________________________________________

        if (IsNullOrEmptyString($_POST["userpass_KEY"])) {
            //IN THIS CASE USER ONLY ENTERED A USERNAME
            $descriptions = "Password is : NULL";
            $sqlquery = "INSERT INTO log_information(username,computerName,present_time_current,present_date_current,ip,code,secreteriate_id,discription) VALUES
 ('$username','$devicename','$jalali_hour','$jalali_date','$useripcode','$codevalue','$secreteariateid','$descriptions')";
        } //__________________________________________________________________________________________________________________________________________________________

        else if (IsNullOrEmptyString($_POST["username_KEY"])) {
            //IN THIS CASE USER ONLY ENTERED A Password
            $descriptions = "Password is : " . $password = $_POST["userpass_KEY"];
            $sqlquery = "INSERT INTO log_information(username,computerName,present_time_current,present_date_current,ip,code,secreteriate_id,discription) VALUES
 ('$username','$devicename','$jalali_hour','$jalali_date','$useripcode','$codevalue','$secreteariateid','$descriptions')";
        } //__________________________________________________________________________________________________________________________________________________________

        else if (IsNullOrEmptyString($_POST["username_KEY"]) && IsNullOrEmptyString($_POST["userpass_KEY"])) {
            $descriptions = "SomeOne Tried To Hit Login Button Without Even Filling The UsingName Or Password";
            $sqlquery = "INSERT INTO log_information(username,computerName,present_time_current,present_date_current,ip,code,secreteriate_id,discription) VALUES
 ('$username','$devicename','$jalali_hour','$jalali_date','$useripcode','$codevalue','$secreteariateid','$descriptions')";
        } else if (!IsNullOrEmptyString($_POST["username_KEY"]) && !IsNullOrEmptyString($_POST["userpass_KEY"])) {

            $sqlquery = "SELECT name,lastname,gender,id_secretariate FROM users WHERE username LIKE '%$username%' AND password LIKE '%$password%'";
            $result = $connection->query($sqlquery);
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $queryResultArray[7] = $row["name"];$queryResultArray[8] = $row["lastname"];$queryResultArray[9] = $row["gender"];
                    echo $queryResultArray[7] . "," . $queryResultArray[8] . "," . $queryResultArray[9];
                }
                $descriptions = "User Successfully Logined With UserName : " . $username . " And Password : " . $password;
                $sqlquery = "INSERT INTO log_information(username,computerName,present_time_current,present_date_current,ip,code,secreteriate_id,discription) VALUES
            ('$username','$devicename','$jalali_hour','$jalali_date','$useripcode','$codevalue','$secreteariateid','$descriptions')";
            } else {
                $queryResultArray[10] = "none";
                echo $queryResultArray[10];
                $descriptions = "Alert,Suspicious Activity On UserName : " . $username . " And Password : " . $password;
                $sqlquery = "INSERT INTO log_information(username,computerName,present_time_current,present_date_current,ip,code,secreteriate_id,discription) VALUES
            ('$username','$devicename','$jalali_hour','$jalali_date','$useripcode','$codevalue','$secreteariateid','$descriptions')";
            }
            $result = $connection->query($sqlquery);
        }
    }
}