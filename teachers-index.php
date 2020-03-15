<?php
session_start();

if (isset($_SESSION["teachers-login-status"]) and $_SESSION["teachers-login-status"] == true) {
    header("location: teachers-home.php");
}

$err_login = $err_register = array();
// Login variables
$username = $password = "";

// Signup variables
$_fname = $_username = $_email = $_password = $_cpassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btn-login"])) {
        $_SESSION["username"] = $_POST["username-input"];
        $_SESSION["password"] = $_POST["password-input"];
        header("location: handlers/teachers-login-handler.php");
    }

    if (isset($_POST["btn-register"])) {
        $_SESSION["fname"] = $_POST["rfname-input"];
        $_SESSION["username"] = $_POST["rusername-input"];
        $_SESSION["email"] = $_POST["remail-input"];
        $_SESSION["password"] = $_POST["rpassword-input"];
        $_SESSION["cpassword"] = $_POST["rcpassword-input"];
        header("location: handlers/teachers-register-handler.php");
    }
}

if (isset($_SESSION["errors"])) {
    $errors = $_SESSION["errors"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">

    <script>
        function display_status() {
            $(".status-container").css("display", "block");
            $(".status-container").css("animation", "disp-status 10s forwards");
        }
    </script>
</head>
<body>
    <!-- ********************************** HEADER ************************************  -->
    <header>
        <img id="logo" src="img/code.png" alt="">
        <nav id="nav-links">
            <ul>
                <li><a href="#">ABOUT</a></li>
                <li><a href="#">CONTACT US</a></li>
                <li><button id="modal-btn-signup">SIGNUP</button></li>
            </ul>
        </nav>

        <span id="menu"></span>
    </header>

    <!-- ******************************** MAIN CONTAINER *********************************  -->

    <div class="main-container">
        <section class="left">
            <div class="content">
                <h1>Welcome To</h1>
                <h1>BCA Department Management System</h1>
                <p>
                    BCA Department Management System (BDMS) is being developed to fulfill all the needs
                     and requirements at department level. It is integrated with all the department’s daily
                      operations including taking attendance of the students, adding student’s performance for a 
                      test or assignment, automatic IA calculation, notice board for the members of the department,
                       student’s activity report and Q&A with the department’s teachers.
                </p>
                <button id="modal-btn-login">LOGIN</button>
            </div>
        </section>

        <section class="right">
            <img src="img/3190343.jpg" alt="">
        </section>
    </div>

    <!-- ******************************* MODAL FORMS ********************************** -->
    <div class="modal-container">
        <div class="login-modal">
            <span class="btn-close">&times;</span>
            <h2>Login</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="text" name="username-input" placeholder="Username">
                <input type="password" name="password-input" placeholder="Password">
                <input type="submit" name="btn-login" value="LOGIN">
                <hr>

                <a class="links" href="#">Forgot Password?</a>
            </form>
        </div>

        <div class="signup-modal">
            <span class="btn-close">&times;</span>
            <h2>Signup</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="text" name="rfname-input" placeholder="Full Name">
                <input type="text" name="rusername-input" placeholder="Username">
                <input type="password" name="rpassword-input" placeholder="Password">
                <input type="password" name="rcpassword-input" placeholder="Confirm Password">
                <input type="submit" name="btn-register" value="SIGNUP">
                <hr>
            </form> 
        </div>
    </div>

    <!-- ****************** STATUS CONTAINER **************************** -->
    <div class="status-container">
        
        <?php
            if (!empty($errors)) {
                echo "<span style='font-size: 16px; color: red; font-weight: bold;'>&times; ERROR</span>";

                foreach ($errors as $key => $value) {
                    echo "<br>" . $key . ": " . $value;
                }

                echo "<script>display_status();</script>";
                unset($_SESSION["errors"]);
            }

            if (isset($_SESSION["status"])) {
                echo "<span style='font-size: 16px; color: rgb(0, 156, 134); font-weight: bold;'>&check; SUCCESS</span>";
                $status_msg = $_SESSION["status"];
                unset($_SESSION["status"]);
                echo "<br>" . $status_msg;
                echo "<script>display_status();</script>";
            }
        ?>
    </div>

    <!-- <footer>
        <div class="lfooter">Icons made by <a class="links" href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a class="links" href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
        <a class="rfooter links" title="https://www.freepik.com/free-photos-vectors/technology" href="https://www.freepik.com/free-photos-vectors/technology">Technology vector created by stories - www.freepik.com</a>
    </footer> -->

    <script src="js/common.js"></script>
</body>
</html>