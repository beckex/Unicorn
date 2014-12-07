<?php
    /*
    * 通过 upload_user_data.php 上传用户地理位置
    * 这里要把信息写到数据库里
    */ 
    $user_id = $_POST["user_id"];
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];
    $region_lon = $_POST["region_lon"];
    $region_lat = $_POST["region_lat"];

    $cons = mysqli_connect("localhost", "planetnd_yiyi", "4rfv5tgb", "planetnd_postit"); // 连接到数据库
    if (mysqli_connect_errno()){
        echo "Failed";
        exit;
    }

    // check data exists?
    $query_content = "SELECT * FROM user_location WHERE user_id='$user_id'";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }
    if(mysqli_num_rows($result) != 0){ // location exist, update data
        $query_content = "UPDATE user_location SET longitude='$longitude', 
                                                   latitude='$latitude',
                                                   region_lon='$region_lon',
                                                   region_lat='$region_lat'
                                                WHERE user_id='$user_id';";
        $result = mysqli_query($cons, $query_content);
        if(!$result){
            echo "Failed";
            exit;
        }
        else{
        }
    }
    else{ // doesn't exist
        $query_content = "INSERT INTO user_location VALUES('$user_id',
                                                           '$longitude',
                                                           '$latitude',
                                                           '$region_lon',
                                                           '$region_lat');";
        $result = mysqli_query($cons, $query_content);
        if(!$result){
            echo "Failed";
            exit;
        }
        else{
        }
    }
    // get other users data;
    $query_content = "SELECT * FROM user_location WHERE user_id<>'$user_id' 
                                                    and region_lon='$region_lon'
                                                    and region_lat='$region_lat';";
    $result = mysqli_query($cons, $query_content);
    if(!$result){
        echo "Failed";
        exit;
    }
    else{
        $o = array();
        while($v = mysqli_fetch_array($result, MYSQLI_NUM)){
            array_push($o, $v);
        }
        echo json_encode($o);
    }


?>