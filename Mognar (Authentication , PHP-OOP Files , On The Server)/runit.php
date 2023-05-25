<?php

error_reporting(0);

require_once 'Android_Functions.php';
$log = new Android_Functions;

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST["username_KEY"];
    $log->aip = $useripcode = $_POST["userIp_KEY"];
    $log->adevice = $devicename = $_POST["devicename_KEY"];
    $password_temp = $_POST['userpass_KEY'];
    $password = $log->hashValue($password_temp);
    $ResultArray = array();

    if ($log->login($username, $password)) {
        $ResultArray[4] = $log->userinformation;
        $log->delete_user_temporary($username);
        //Check Temporary Tables For Delete
        $log->delete_temporary_with_login('letter_temporary_receiver', $username);
        $log->delete_temporary_with_login('letter_temporary_sender', $username);
        $log->delete_temporary_with_login('temporary_receiver_receive', $username);
        $log->delete_temporary_with_login('temporary_receiver_sender', $username);

        $code_log = 1;
        $log->logCorrect($code_log,1);
    if($arash = $log->check_user_exist_seretariat($username)) {
        $ResultArray[2] =  $log->get_secretariat($arash);
    } else if ($arash2 = $log->check_user_create_secretriat($username)) {
        $ResultArray[2] = $log->get_secretariat($arash2);
    } else { $ResultArray[2] = "_"; }
    } else {  echo "..."; }

    echo $ResultArray[2] . "," . $ResultArray[4];
}

?>