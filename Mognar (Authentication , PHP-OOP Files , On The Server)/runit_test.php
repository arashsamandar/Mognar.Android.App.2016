<?php

error_reporting(0);

require_once 'Android_Functions.php';
$log = new Android_Functions;

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $temp_password = $_POST['password'];
    $password = $log->hashValue($temp_password);

    if ($log->login($username, $password)) {
        $log->delete_user_temporary($username);
        //Check Temporary Tables For Delete
        $log->delete_temporary_with_login('letter_temporary_receiver', $username);
        $log->delete_temporary_with_login('letter_temporary_sender', $username);
        $log->delete_temporary_with_login('temporary_receiver_receive', $username);
        $log->delete_temporary_with_login('temporary_receiver_sender', $username);

        $code_log = 1;
        $log->logCorrect($code_log,1);
        $message = "LOG IN WAS SUCCESSFULL....!";
    } $message = "SECOND IF ERROR";
    $message = "first if was successfull....!";
}

?>

<html xmlns="http://www.w3.org/1999/html">
<body>
<br/>
<?php echo $message . "<br/>" ?>
<form action="runit.php" method="post">
    UserName : <input type="text" name="username" value=""/><br/>
    Password : <input type="password" name="password" value=""/><br/>
    <br/>
    <input type="submit" name="submit" value="submit" />
</form>
</body>
</html>
