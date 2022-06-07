<!DOCTYPE html>

<head>
    <title>
        Registration Page
    </title>
</head>

<body>
    <h1>Register</h1>
    <form id="registerForm" action="register_backend.php" method="POST">
        <label> Account Name </label>
        <input type="text" name="full_name" /> <br> <br>
        <label> Bank Number </label>
        <input type="text" name="account_number" /> <br> <br>
        <label> MoMo Number </label>
        <input type="text" name="momo_number" /> <br> <br>
        <label for="dob">Gender</label>
        <select name="gender" id="gender">
            <option value="M" selected>Male</option>
            <option value="F" selected>Female</option>
        </select><br> <br>
        <label>Email</label>
        <input type="email" name="email_address" /> <br> <br>
        <label>Password</label>
        <input type="password" name="user_password" id="user_password" /> <br> <br>
        <label>Retype password</label>
        <input type="password" name="retype_password" id="retype_password" /> <br> <br>
        <button type="submit">Register</button>
    </form>
    <p><a href="index.php">Click here</a> to login.</p>

    <script src="js/jquery-2.2.3.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#registerForm").on("submit", function(e) {
                e.preventDefault();
                if ($("#user_password").val() != $("#retype_password").val()) {
                    alert("Password does much!");
                } else {
                    //window.location.href = "purchase_step2.php";
                    $.ajax({
                        type: "POST",
                        url: "backend/register.php",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(result) {
                            console.log(result);
                            /*if (result) {
                                window.location.href = 'purchase_step2.php';
                            }*/
                            /*if (res["response"] == "success") {
                                console.log(res['msg']);
                                window.location.href = 'verify-code.php'
                            } else {
                                console.log(res['msg']);
                            }*/
                        },
                        error: function(error) {}
                    });
                }
            });
        });
    </script>
</body>

</html>