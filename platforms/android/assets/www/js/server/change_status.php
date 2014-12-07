<?php  
/*
    * update user's status
    * 这里要把信息写到数据库里
    */ 
    $user_id = $_POST["user_id"];
    $status = $_POST["status"];
    
    $cons = mysqli_connect("localhost", "planetnd_yiyi", "4rfv5tgb", "planetnd_postit"); // 连接到数据库
    if (mysqli_connect_errno()){
        echo "Failed";
        exit;
    }
    
    // check user exist
    $query_content = "SELECT * FROM user_status WHERE user_id='$user_id'";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }

    if(mysqli_num_rows($result) == 0){ // status doesn't exist
        $query_content = "INSERT INTO user_status VALUES ('$user_id', '$status');";
        $result = mysqli_query($cons, $query_content);
        if(!$result){
            echo "Failed";
            exit;
        }
        else{
            echo "Success";
        }
    }
    else{
        $query_content = "UPDATE user_status SET status='$status' 
                                             WHERE user_id='$user_id'";
        $result = mysqli_query($cons, $query_content);
        if(!$result){
            echo "Failed";
            exit;
        }
        else{
            echo "Success";
        }
    }
?>