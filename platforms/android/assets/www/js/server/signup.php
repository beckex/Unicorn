<?php
    /*
    * 通过 signup.php 用户注册
    * 这里要把信息写到数据库里
    */ 
    $id = uniqid();
    $username = $_POST["username"];
    $password = $_POST["password"];

    $cons = mysqli_connect("localhost", "planetnd_yiyi", "4rfv5tgb", "planetnd_postit"); // 连接到数据库
    if (mysqli_connect_errno()){
        echo "Failed";
        exit;
    }
    
    // check user exist
    $query_content = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }

    if(mysqli_num_rows($result) != 0){
        // user exists
        echo "user_exists";
        exit;
    }

    $query_content = "INSERT INTO user VALUES ( '$id', 
                                                '$username',
                                                '$password')";  
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }
    echo $id;
?>