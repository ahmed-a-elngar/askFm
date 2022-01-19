<?php

    session_start();
    
    $_SESSION['active_tab'] = 'sign up';
    
    $noNav = '';
    $pageTitle = 'Sign up | ASK fm';
    include('init.php');

    #if user is loged in
    loged('sign');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        # code...
        $user_email = htmlentities(mysqli_real_escape_string($con, $_POST['email']));
        $user_DB =htmlentities(mysqli_real_escape_string($con, $_POST['DB']));

        $check_mail = "SELECT * from users where user_email = '$user_email'";
        $run_mail_check = mysqli_query($con, $check_mail);
        $check = mysqli_num_rows($run_mail_check);
        if ($check == 1) {
            echo '<div style="color:white; background-color:#e14; text-align:center; font-size:16px; padding:12px;">' .
                'Email already exists, please sign in</div>';
        }
        else{
            $user_name =preg_split("/@/", $user_email)[0];
            $user_pass = md5($user_name);
            $insert = "insert into users (user_name ,user_email, user_pass, user_DB) 
                                   values('$user_name', '$user_email','$user_pass', '$user_DB')";
            $query = mysqli_query($con, $insert);            

            if ($query) {
                #create user tables
                $user_id = mysqli_insert_id($con);
                create_user_tables($user_id);

                $_SESSION['user_name'] = $user_name;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_pic'] = "pics/private.jpeg";
                $_SESSION['reset_pass'] = "true";
                $_SESSION['user_c_count'] = 0;
                #redirect to profile
                header('location: change_password.php');
            }
            else{
                echo '<div style="color:white; background-color:#e14; text-align:center; font-size:16px; padding:12px;">' .
                'Failed, please try again!<br>'.$query.'</div>';
            }
        }

    }
?>
    
    <!--main container-->
    <div class="main_container one_side">

        <img class="logo" src="pics/logo-red-9af653502f0b8f01022ea1aa0ab49f00b41db433c00fee35a9848e5a87a0dff9.png">
        <div class="form_container">
            <h3>Sign up</h3>
            <p style="margin-top: 1vh;">
                Alrady have an account?
                <a href="sign in.php">
                    Log in                
                </a>
            </p>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                <label>E-mail</label><br>
                <input type="email" placeholder="E-mail" name="email" required>
                <label>Birthday</label><br>
                <input type="date" name="DB" max="2003-01-16" required>

                <p class="grey">
                    By signing up you agree to our 
                    <a href="">Terms</a> 
                    and 
                    <a href="">Privacy Policy</a>
                </p>
                <button type="submit">Sign up</button> 
            </form>
       
        </div>

        <?php include 'includes/temps/signing_with_and_lang.php';?>


    </div>

</body>
</html>