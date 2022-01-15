<?php

    session_start();

    $noNav = '';
    $pageTitle = 'Sign in | ASK fm';
    include('init.php');

    #if user is loged in
    loged('sign');


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            # code...
            $user_email = htmlentities(mysqli_real_escape_string($con, $_POST['email']));
            $user_password =htmlentities(mysqli_real_escape_string($con, $_POST['password']));
            $user_password = md5($user_password);

            $check_user = "SELECT * from users where user_email = '$user_email' AND user_pass = '$user_password'";
            $query = mysqli_query($con, $check_user);
            $check = mysqli_num_rows($query);
            if ($check == 1) {
                $_SESSION['user_email'] = $user_email;
                $get_user = "SELECT * from users where user_email = '$user_email'";
                $run_user = mysqli_query($con, $get_user);
                $row = mysqli_fetch_array($run_user);

                $user_name = $row['user_name'];
                $_SESSION['user_name'] = $user_name;
                $user_id = $row['user_id'];
                $_SESSION['user_id'] = $user_id;
                $user_pic = $row['user_pic'];
                $_SESSION['user_pic'] = $user_pic;
                $user_l = $row['user_l_count'];
                $_SESSION['user_l_count'] = $user_l;
                $user_c = $row['user_c_count'];
                $_SESSION['user_c_count'] = $user_c;

                if (isset($_SESSION["another_profile"])) {
                    $destination = $_SESSION["another_profile"];
                    header('location:profile.php?user_name_='.$destination);
                }
                else {
                    $destination = isset($_SESSION["destination_"])? $_SESSION["destination_"]: "profile";
                    header('location:'.$destination.'.php');
                }

            }
            else{
                echo '<div style="color:white; background-color:#e14; text-align:center; font-size:16px; padding:12px;">' .
                    'Email or password is wrong, please try again!</div>';
            }

        }
    ?>

    <!--main container-->
    <div class="main_container one_side">

        <img class="logo" src="pics/logo-red-9af653502f0b8f01022ea1aa0ab49f00b41db433c00fee35a9848e5a87a0dff9.png">
        <div class="form_container">
            <h3>Log in</h3>
            <p style="margin-top: 1vh;">
                Don’t have an account yet!? 
                <a href="sign up.php">
                    Sign Up
                </a>
            </p>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <label>Log in</label><br>
                <input type="email" placeholder="UserName or e-mail" name="email">
                <label>Password</label><br>
                <input type="password" placeholder="Password" name="password">
                <input class="remember_box" type="checkbox" checked="True">
                <label>Remember me</label>
                <a class="forget_link">Forget password!</a>
                <button type="submit">Log in</button>                 
            </form>
       
        </div>

    <?php include 'includes/temps/signing_with_and_lang.php';?>

    </div>


</body>
</html>