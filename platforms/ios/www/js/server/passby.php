<?php
    /*
    * save passby information to database
    */ 
    $user_id = $_POST["user_id"];
    $passby_id = $_POST["passby_id"];
    $date = $_POST["date"];

    $cons = mysqli_connect("localhost", "planetnd_yiyi", "4rfv5tgb", "planetnd_postit"); // 连接到数据库
    if (mysqli_connect_errno()){
        echo "Failed";
        exit;
    }

    // check exists
    $query_content = "SELECT * FROM passby WHERE user_id='$user_id' AND passby_id='$passby_id' AND date='$date';";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }
    if(mysqli_num_rows($result) != 0){ // already exist.
        echo "Failed"; 
        exit;
    }
    
    // insert into passby table
    $query_content = "INSERT INTO passby VALUES('$user_id', '$passby_id', '$date');";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }

    // get passby user status
    $query_content = "SELECT status FROM user_status WHERE user_id='$passby_id';";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }

    if(mysqli_num_rows($result) == 1){ // fetch passby user status
        $status = mysqli_fetch_array($result, MYSQLI_NUM);
        $status = $status[0];
        
        $query_content = "SELECT username FROM user WHERE user_id='$passby_id'";
        $result = mysqli_query($cons, $query_content);
        if(!$result){
            echo "Failed";
            exit;
        }
        $username = mysqli_fetch_array($result, MYSQLI_NUM);
        $username = $username[0];
        echo json_encode(array($status, $username));
    }
    else{
        echo "Failed";
        exit;
    }

?>