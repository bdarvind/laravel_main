$(document).ready(function(){
    var user_name = $("#user_name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var re_password = $("#re_password").val();
    var nationality = $("#nationality").val();

    $("#register").click(
        function (){

            // Check Empty
            if($.trim(user_name) == '')
            {
                alert("User Name can't be Empty");
            }
            else if($.trim(email) == '')
            {
                alert("Email can't be Empty");
            }
            else if($.trim(password) == '')
            {
                alert("Password can't be Empty");
            }
            else if($.trim(user_name) == '')
            {
                alert("User Name can't be Empty");
            }
            else if($.trim(re_password)!= $.trim(password) )
            {
                alert("Retyped passwords do not match");
            }
            else if($.trim(nationality) == '') {
                alert("Nationality can't be Empty");
            }
            else {
                alert("Register Successful !");
            }
        }
    );

});