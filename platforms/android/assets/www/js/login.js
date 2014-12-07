// user login
$("#user_login_btn").click(function(){
    // TODO:
    // sanitize username and password
    // check password and username not empty and valid
    
    console.log("Clicked login button");
    var username = $("#login_username").val();
    var password = $("#login_password").val();
    $.ajax({
            url: "http://planetwalley.com/postit_test/login.php",
            async: false,
            type: "POST",
            // 下面是发送的信息
            data:{username: username,
                  password: password,}
        }).done(function(data){
            if(data === "Failed"){
                alert("Failed to login\nPlease try later");
                return;
            }
            else if (data === "wrong_username_or_password"){
                alert("wrong username or password");
                return;
            }
            data = JSON.parse(data);
            console.log(data);
            window.localStorage["username"] = username;
            window.localStorage["user_id"] = data[0]; // data[0] is the user_id
            window.localStorage["status"] = data[1]; // data[1] is status

            window.location.href = ("./application.html"); // begin to run app
            
        }).fail(function(data){
            alert("Failed");
        })}
                          )