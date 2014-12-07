// user sign up
$("#user_signup_btn").click(function(){
    // TODO:
    // sanitize username and password
    // check password and username not empty and valid
    // ask user to enter password twice.
    
    console.log("Clicked sign up button");
    var username = $("#signup_username").val();
    var password = $("#signup_password").val();
    $.ajax({
            url: "http://planetwalley.com/postit_test/signup.php",
            async: false,
            type: "POST",
            // 下面是发送的信息
            data:{username: username,
                  password: password,}
        }).done(function(data){
            if(data === "Failed"){
                alert("Failed to register\nPlease try later");
                return;
            }
            else if (data === "user_exists"){
                alert("Failed to sign up\nusername " + username + " already existed");
                return;
            }
            console.log(data);
            window.localStorage["username"] = username;
            window.localStorage["user_id"] = data; // data is the user_id
            window.localStorage["status"] = "You dont have status yet"; // set user status
            window.location.href = ("./application.html"); // begin to run app
        }).fail(function(data){
            alert("Failed");
            console.log(data);
        })})