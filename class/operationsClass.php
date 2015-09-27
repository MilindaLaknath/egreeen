<?php

require 'db.php';

class Operations {

    public $session_id = '';
    public $session_menu = '';
    public $session_pg = 0;
    public $session_tel = '';
    public $session_others = '';
    public $res_arry = array();
    public $res_arry_id = array();

    public function setSessions($sessions) {
        global $connection;
//        $connection = mysqli_connect("localhost", "root", "123", "sessiondb");
//        mysqli_select_db($connection, "sessiondb");

        $sql_sessions = "INSERT INTO `egsessions` (`sessionsid`, `tel`, `menu`, `pg`, `created_at`,`others`,`longitude`,`latitude`) VALUES 
			('" . $sessions['sessionid'] . "', '" . $sessions['tel'] . "', '" . $sessions['menu'] . "', '" . $sessions['pg'] . "', CURRENT_TIMESTAMP,'" . $sessions['others'] . "', '" . $sessions['longitude'] . "', '" . $sessions['latitude'] . "')";

        mysqli_query($connection, $sql_sessions);
//        mysqli_close($connection);
//        return $sql_sessions;
    }

    public function getSession($sessionid) {
        global $connection;
//        $connection = mysqli_connect("localhost", "root", "123", "sessiondb");
        $sql_session = "SELECT *  FROM  `egsessions` WHERE  sessionsid='" . $sessionid . "'";
        $quy_sessions = mysqli_query($connection, $sql_session);
        $fet_sessions = mysqli_fetch_array($quy_sessions);
        $this->session_others = $fet_sessions['others'];
//        mysqli_close($connection);
        return $fet_sessions;
    }

    public function saveSesssion() {
        global $connection;
//        $connection = mysqli_connect("localhost", "root", "123", "sessiondb");
        $sql_session = "UPDATE  `egsessions` SET 
			`menu` =  '" . $this->session_menu . "',
			`pg` =  '" . $this->session_pg . "',
			`others` =  '" . $this->session_others . "'
			WHERE `sessionsid` =  '" . $this->session_id . "'";
        mysqli_query($connection, $sql_session);
//        mysqli_close($connection);
    }

    public function getGcolectors($type, $latitude, $longitude) {
        global $connection;

        /*
          SELECT id, ( 6371 * acos( cos( radians($latitude) ) * cos( radians( latitude ))
         * cos( radians( longitude ) - radians($longitude) ) + sin( radians($latitude) )
         * sin( radians( latitude ) ) ) ) AS distance FROM markers
          HAVING distance < 30 ORDER BY distance LIMIT 0 , 20;
         */
        if ($type === "All") {
            $serch_qry = "SELECT gcid,organization,city,( 6371 * acos( cos( radians($latitude) ) * cos( radians( lat )) * cos( radians( lng) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM eggbcollector HAVING distance < 10 ORDER BY distance  LIMIT 0 , 5";
        } else {
            $serch_qry = "SELECT gcid,organization,city,( 6371 * acos( cos( radians($latitude) ) * cos( radians( lat )) * cos( radians( lng ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM eggbcollector, egcollector_types ct WHERE gcid=ct.collector_id AND ct.type_id='" . $type . "' HAVING distance < 10 ORDER BY distance  LIMIT 0 , 5";
        }
        $result = mysqli_query($connection, $serch_qry);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $arry = array();
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $insideArry = array($row['gcid'], $row["organization"] . " : " . $row["city"]);
                $input = array($count => $insideArry);
                array_push($arry, $input);
                $count++;
            }
        }
        return $arry;
    }

    function getGcolDetails($resp_no, $sesId, $operations) {
        global $connection;
        $serch_qry = null;
        $msg = $sesId;
        $gcid = "";
        $gctype = "";
        //Generate Unique ID
//        $uniqid = date('U');
//        $rand_pin = hexdec($uniqid);
        $rand_pin = $operations->randStrGen(5);

        $sehqry = "SELECT gcid,type FROM egtmp_save WHERE sessid='" . $sesId . "' AND countnum='" . $resp_no . "'";
        $ressult_gcid = mysqli_query($connection, $sehqry);
        while ($row_gcid = mysqli_fetch_assoc($ressult_gcid)) {
            $gcid = $row_gcid["gcid"];
            $gctype = $row_gcid["type"];
        }
        $serch_qry = "SELECT * FROM eggbcollector WHERE gcid='" . $gcid . "'";
        $reslt = mysqli_query($connection, $serch_qry);
        while ($row = mysqli_fetch_assoc($reslt)) {
            $msg = "Name : " . $row["organization"] . "\nCity : " . $row["city"] . "\nPin : " . $rand_pin;       //"\nNo : " . $row["number"] .
        }

        $pinqry = "INSERT INTO egindividual(sessionid,gtype,assigned,collected,pin) VALUES('" . $sesId . "','" . $gctype . "','" . $gcid . "',0,'" . $rand_pin . "')";
        mysqli_query($connection, $pinqry);

        $del_tmp = "DELETE FROM egtmp_save WHERE sessid='" . $sesId . "'";
        mysqli_query($connection, $del_tmp);

        mysqli_free_result($ressult_gcid);
        mysqli_free_result($reslt);

        

        return $msg;
    }

    function setArtoStr($res_ar, $sesId, $gctype) {
        global $connection;


        $text = "Select one\n";

        if (!$res_ar) {
            $text = "Sorry !\nNo one to find near you.\n";
        }
        foreach ($res_ar as $value) {
            foreach ($value as $x => $x_value) {
                $text = $text . $x . ". " . $x_value[1] . "\n";
                //printing array include gcid and gcDetails
                $tmp_qry = "INSERT INTO egtmp_save(sessid,countnum,gcid,type) VALUES('" . $sesId . "','" . $x . "','" . $x_value[0] . "','" . $gctype . "')";
                mysqli_query($connection, $tmp_qry);
            }
        }

        $text = $text . "99. Exit";
        return $text;
    }

    // Random PIN genetator

    function randStrGen($len) {
        $result = "";
        $chars = "0123456789";
//        $chars = "abcdefghijklmnopqrstuvwxyz$?!-0123456789";
        $charArray = str_split($chars);
        for ($i = 0; $i < $len; $i++) {
            $randItem = array_rand($charArray);
            $result .= "" . $charArray[$randItem];
        }
        return $result;
    }

    function getColectorId($sesid) {
        global $connection;
        $colectorid = "0";
        $sehqry = "SELECT assigned FROM egindividual WHERE sessionid='" . $sesid . "'";
        $ressult_gcid = mysqli_query($connection, $sehqry);
        while ($row_gcid = mysqli_fetch_assoc($ressult_gcid)) {
            $colectorid = $row_gcid["assigned"];
        }
        mysqli_free_result($ressult_gcid);
        return $colectorid;
    }

    function getGcTypeId($sesid) {
        global $connection;
        $gtyp = "0";
        $sehqry = "SELECT gtype FROM egindividual WHERE sessionid='" . $sesid . "'";
        $ressult_gcid = mysqli_query($connection, $sehqry);
        while ($row_gcid = mysqli_fetch_assoc($ressult_gcid)) {
            $gtyp = $row_gcid["gtype"];
        }
        mysqli_free_result($ressult_gcid);
        return $gtyp;
    }

    function getUsrNo($sesid) {
        global $connection;
        $mobile = "0";
        $sehqry = "SELECT mobno FROM egusers WHERE userid=(SELECT tel FROM egsessions WHERE sessionsid='" . $sesid . "')";
        $ressult_gcid = mysqli_query($connection, $sehqry);
        while ($row_gcid = mysqli_fetch_assoc($ressult_gcid)) {
            $mobile = $row_gcid["mobno"];
        }
        mysqli_free_result($ressult_gcid);
        return $mobile;
    }

    function closeConn() {
        global $connection;
        mysqli_close($connection);
    }

}

?>