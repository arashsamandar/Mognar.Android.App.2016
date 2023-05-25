<?php

include 'jdf.php';
include_once 'connect.php';


class Android_Functions
{


    #______________________________________DB __construct()_________________________________________

    public function __construct()
    {
        $db = new DB_Class();
    }

    #______________________________________login($username,$password) FUNCTION_________________________________________

    public $adevice;
    public $aip;
    public $userinformation;


    public function login($username, $password)

    {

        $username = $this->secureParam($username);


        $username = strtolower($username);


        $result = mysql_query("SELECT id FROM users WHERE username = '" . $username . "' AND

password = '" . $password . "' ");

        $no_rows = mysql_num_rows($result);

        $user_data = mysql_fetch_array($result);



        if ($no_rows == '1') {

            $id = $user_data['id'];

            $this->userinformation = $this->give_away_user_info_for_firstEdition_Android($id);

            return true;

        } else {

            $this->check_user($username, $password);

        }


    }

    #______________________________________check_user($username,$password) Function_________________________________________


    public function check_user($username, $password)

    {

        $sql = mysql_query("SELECT id FROM users WHERE username = '" . $username . "'");

        $s = mysql_num_rows($sql);



        if ($s == '1') {



            $user_data = mysql_fetch_assoc($sql);

            $id = $user_data['id'];

            $username_log = $this->get_fullname($id);

            echo "  نام کاربری صحیح است  ";

            $code_log = 3;

            $discription = "Password Logged:" . $password;

            $this->logCorrect($code_log, $username_log, $discription);


        } else {

// user wrong

            echo " نام کاربری معتبر نیست ";

            $code_log = 2;

            $discription = "UserName Logged :" . $username;

            $this->logCorrect($code_log, null, $discription);

            //return false;

        }

        $sql2 = mysql_query("SELECT id FROM users WHERE password = '" . $password . "'");

        $s2 = mysql_num_rows($sql2);


        if ($s2 != '1') {

            $code_log = 3;

            $discription = "Password Logged :" . $password;

            $this->logCorrect($code_log, null, $discription);

            echo " کلمه ی عبور اشتباه است ";

            //return false;


        }

    }


    #______________________________________get_fullname($id) Function_________________________________________

    public function get_fullname($id)

    {

        $id = $this->secureNumber($id);

        $result = mysql_query("SELECT name,lastname,username,gender FROM users WHERE id = '" . $id . "'");

        $user_data = mysql_fetch_assoc($result);

        $username = $user_data['username'];

        $username = $this->secureParam($username);

        return $username;

    }

    public function give_away_user_info_for_firstEdition_Android($id)

    {

        $id = $this->secureNumber($id);

        $result = mysql_query("SELECT name,lastname,username,gender FROM users WHERE id = '" . $id . "'");

        $user_data = mysql_fetch_assoc($result);

        $username = $user_data['username'];
        $fname = $user_data['name'];
        $lname = $user_data['lastname'];
        $gender = $user_data['gender'];

        echo  urldecode($fname) . "," . urldecode($lname) . "," . urldecode($gender) . ",";

        $userinfoarray = array();

        $username = $this->secureParam($username);

        return $username;

    }


    #________logCorrect($code_log,$username_log=null,$discription=null,$id_secretariat=null) Function__________


    public function logCorrect($code_log,$username_log=null,$discription=null,$id_secretariat=null)
    {
        $code_log       = $this->secureInt($code_log);
        $username_log   = $this->secureValueTable($username_log);
        $discription    = $this->secureValueTable($discription);
        $id_secretariat = $this->secureInt($id_secretariat);

        $ip = $this->aip;

        //Record Date
        $record_date = jdate('Y/m/d');
        $current_date_log =  tr_num($record_date);

        $current_time_log = date("h:i:s A");

        $computerName =  $this->adevice;

        $query = mysql_query("INSERT INTO log_information (code,username,ip,present_time_current,present_date_current,computerName,discription,secreteriate_id)
                                VALUES ('".$code_log."','".$username_log."','".$ip."','".$current_time_log."','".$current_date_log."','".$computerName."','".$discription."','".$id_secretariat."')")
        or die(mysql_error());

        if($query){
            $id = mysql_insert_id();
        }

        return $id;

    }



    #______________________________________hashValue($value) Function_________________________________________

    function hashValue($value)
    {
        $salt = 'SUPER_RAHPAD_MOGNAR';
        return md5($value . $salt);
    }

    #______________________________________secureParam($value) Function_________________________________________

    function secureParam($value)
    {
        $value = trim($value);
        $value = addslashes($value);
        $value = htmlspecialchars($value,ENT_QUOTES,"UTF-8");
        $value = strip_tags($value);
        return $value;
    }

    #______________________________________secureNumber($value) Function_________________________________________

    public function secureNumber($value)
    {
        $value = addslashes($value);
        $value = htmlspecialchars($value,ENT_QUOTES,"UTF-8");
        $value = strip_tags($value);
        $value = intval($value);
        return $value;
    }



    #______________________Secure Number________________secureInt($value) Function_______________________________

    function secureInt($value)
    {
        $value = addslashes($value);
        $value = htmlspecialchars($value,ENT_QUOTES,"UTF-8");
        $value = strip_tags($value);
        $value = intval($value);
        $value = mysql_real_escape_string($value);
        return $value;
    }

    #______________________________________secureValueTable($value) Function_________________________________________

    function secureValueTable($value)
    {
        $value = addslashes($value);
        $value = htmlspecialchars($value,ENT_QUOTES,"UTF-8");
        $value = strip_tags($value);
        $value = mysql_real_escape_string($value);
        return $value;
    }

    #______________________________________Testing_Android_Function_class() Function_________________________________________


    public function Testing_Android_Function_class() {
        if ($result1=mysql_query("SELECT username,password FROM users")) {
            $rowcount = mysql_num_rows($result1);
            if ($rowcount > 0) {
                while ($row = mysql_fetch_assoc($result1)) {
                    echo "</br>";
                    echo $row["username"];
                    echo "</br>";
                    echo $row["password"];
                }
            }
        }
    }


    #____________________check temporary tables for delete_______Testing_Android_Function_class() Function__________________


    public function delete_temporary_with_login($table, $value)

    {

        $value = $this->secureParam($value);

        $query = mysql_query("SELECT * FROM  $table WHERE username='" . $value . "'") or die(mysql_error());

        $result1 = mysql_num_rows($query);



        if ($result1 > 0) {

            mysql_query("DELETE FROM $table WHERE username='" . $value . "'") or die(mysql_error());

        }

    }

    #_________________________________________________delete_user_temporary($username)______________________________________

    public function delete_user_temporary($username)

    {
        $username = $this->secureParam($username);
        $query = mysql_query("DELETE FROM user_temporary WHERE username='" . $username . "'");
    }


    #___________________________________________________________________________________________________________________________________________
    #____________________________________________________SECRETARIAT SECTIOLN___________________________________________________________________
    #___________________________________________________________________________________________________________________________________________

//Get Secretariat User & Secretariat Id of Database
    // Arash , this is the phase one of getting secretarial_title , YOU EATHER GET id_secretariat from this function or from the next function which is following this one .
    #____________________________________________________________________________________________Start of The Block
    public function check_user_create_secretriat($username)
    {
        $username = $this->secureParam($username);

        $myquery = mysql_query("SELECT * FROM create_secretariat WHERE user_secretariat='".$username."'")  or die(mysql_error());
        $record = mysql_num_rows($myquery);

        if($record >0)
        {
            while($row = mysql_fetch_assoc($myquery))
            {
                $secretariate_id = $row['id'];
            }

        }else{
            $secretariate_id = $this->check_user_exist_seretariat();
        }

        return $secretariate_id;

    }

    //User Member exist Secretariat

    // Arash this is exactly the phase two , which you suppose to check .
    public function check_user_exist_seretariat($username)
    { //$id = $_SESSION['id']; // ino nemidonam , where is this id comming from and what does it stand for ? ( appreciate your help beforehand :) ) .
        #________________________________________________bellow is my question_____________________________________________________________
        $myqueryid = mysql_query("SELECT id_secretariate FROM users WHERE username = '$username'")  or die(mysql_error());
        if(mysql_num_rows($myqueryid) > 0) {
            while($row1 = mysql_fetch_assoc($myqueryid)){
                $id = $row1['id_secretariate'];
            }
        }
        #__________________________________________________________________________________________________________________________________
        $id = $this->secureNumber($id);

        $myquery = mysql_query("SELECT * FROM permissions_secretariat WHERE user_id='".$id."' AND login=1")  or die(mysql_error());
        $record = mysql_num_rows($myquery);

        if($record >0)
        {
            $myquery = mysql_query("SELECT * FROM users WHERE id='".$id."'")  or die(mysql_error());
            while ($row=mysql_fetch_assoc($myquery))
            {
                $id_secretariate = $row['id_secretariate'];
            }

        }

        return $id_secretariate;

    }


    // IT IS SIMPLE : har do tabeye bala $id_secretariate ro bar_migardonan . agar az avali natonesti $id_secretariat ro begiri as dovomi bayad begiri ( be hamin rahati )
    public function get_secretariat($secreteriate_id)
    {
        $secreteriate_id = $this->secureNumber($secreteriate_id);
        $query = mysql_query("SELECT * FROM create_secretariat WHERE id='".$secreteriate_id."'") or die(mysql_error());
        if(mysql_num_rows($query) > 0) {
            while($row = mysql_fetch_assoc($query)){
                $secreteriate = $row['title_secretariat'];
            }
        }
        return $secreteriate;
    }

    function IsNullOrEmptyString($question){
        return (!isset($question) || trim($question)==='');
    }

}