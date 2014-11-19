<?php
    /*
    * init passby data
    * query all data related to user_id
    */
    $user_id = $_POST["user_id"];
    $date = $_POST["date"];

    $cons = mysqli_connect("localhost", "planetnd_yiyi", "4rfv5tgb", "planetnd_postit"); // 连接到数据库
    if (mysqli_connect_errno()){
        echo "Failed";
        exit;
    }

    $query_content = "SELECT passby_id FROM passby WHERE user_id='$user_id' AND date='$date'";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }
    else{
        $return_v = array() ; // [passby_id, username, status]
        $r = $result;
        while($o = mysqli_fetch_array($r, MYSQLI_NUM)){
            $passby_id = $o[0];
            
            // fetch passby user status 
            $query_content = "SELECT status FROM user_status WHERE user_id='$passby_id';";
            $result = mysqli_query($cons, $query_content);
            $status = mysqli_fetch_array($result, MYSQLI_NUM);
            $status = $status[0];
            
            // fetch username
            $query_content = "SELECT username FROM user WHERE user_id='$passby_id'";
            $result = mysqli_query($cons, $query_content);
            $username = mysqli_fetch_array($result, MYSQLI_NUM);
            $username = $username[0];
            
            array_push($return_v, array($passby_id, $username, $status));
        }
        echo json_encode($return_v);
    }

?>