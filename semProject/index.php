<!DOCTYPE html>
<html>

<head>

    <title> Login Page </title>

</head>

<body>
    <form id="loginForm" action="register_backend.php" method="post">
        <label>Email</label>
        <input type="email" name="email" id="email" placeholder="type your email address">
        <br><br>
        <label>Pin </label>
        <input type="password" name="password" id="password" placeholder="type your password">
        <br><br>
        <button type="submit" name="login">Login</button>
    </form>
    <p><a href="register.php">Click here</a> to register.</p>

    <script src="js/jquery-2.2.3.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").on("submit", function(e) {
                e.preventDefault();
                //window.location.href = "purchase_step2.php";
                $.ajax({
                    type: "POST",
                    url: "backend/login.php",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(result) {
                        console.log(result);
                        let rslt = JSON.parse(result);
                        alert(rslt['msg']);
                        if (rslt['response'] == 'success') {
                            window.location.href = 'home.php';
                        }
                        /*if (res["response"] == "success") {
                            console.log(res['msg']);
                            window.location.href = 'verify-code.php'
                        } else {
                            console.log(res['msg']);
                        }*/
                    },
                    error: function(error) {}
                });
            });
        });
    </script>
</body>

</html>