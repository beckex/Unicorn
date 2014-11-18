<?php
    /*
    * 通过 login.php 用户 login
    */ 
    $username = $_POST["username"];
    $password = $_POST["password"];

    $cons = mysqli_connect("localhost", "planetnd_yiyi", "4rfv5tgb", "planetnd_postit"); // 连接到数据库
    if (mysqli_connect_errno()){
        echo "Failed";
        exit;
    }
    
    // check user exist
    $query_content = "SELECT user_id FROM user WHERE username='$username' and password='$password'";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }

    if(mysqli_num_rows($result) == 1){
        // user exists
        $id = mysqli_fetch_array($result, MYSQLI_NUM);
        $id = $id[0];
        $status = "You dont have status yet";
        
        // fetch user status
        $query_content = "SELECT status FROM user_status WHERE user_id='$id';";
        $result = mysqli_query($cons, $query_content);
        if(!result){
            echo "Failed";
            exit;
        }
        
        if(mysqli_num_rows($result) == 0){ // no status yet
            
        }
        else{
            $status = mysqli_fetch_array($result, MYSQLI_NUM);
            $status = $status[0]; // get status
        }
        echo json_encode(array($id, $status));
    }
    else{
        echo "wrong_username_or_password";
        exit;
    }

?>