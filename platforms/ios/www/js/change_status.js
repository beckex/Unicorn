// user change status
$("#change_status_btn").click(function(){
    var user_id = window.localStorage["user_id"];
    var status = $("#status_input").val();
    console.log("Change status: " + status);
    if(status === "You dont have status yet") return;
    $.ajax({
            url: "http://planetwalley.com/postit_test/change_status.php",
            async: false,
            type: "POST",
            // 下面是发送的信息
            data:{user_id: user_id,
                  status: status,}
        }).done(function(data){
            if(data === "Failed"){
                alert("Failed to change status\nPlease try later");
                return;
            }
            else if (data === "Success"){
                window.localStorage["status"] = status;
                $("#status").html(status);
                alert("Status updated");
                $.mobile.navigate("#profile_page", {transition:"flip"});
            }
            else{
                alert("Failed to change status\nPlease try later");
            }
        }).fail(function(data){
            alert("Failed");
        })
                              })