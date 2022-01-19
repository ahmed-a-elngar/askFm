<?php
    session_start();

    $_SESSION['active_tab'] = 'change password';


    $pageTitle = "Settings | Change Password";
    include('init.php');

    #if user is loged in
    loged('change_password');

    $user_id = $_SESSION["user_id"];
    $select_user_info = "SELECT * FROM users where user_id = '$user_id'";
    $run_select_user_info = mysqli_query($con, $select_user_info);
    $user_info = mysqli_fetch_array($run_select_user_info);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $user_old_pass = htmlentities(mysqli_real_escape_string($con, $_POST['old_pass']));
        $user_new_pass = htmlentities(mysqli_real_escape_string($con, $_POST['new_pass_1']));
        $user_old_pass_enc = md5($user_old_pass);
        if ($user_old_pass_enc == $user_info['user_pass']) {
            $user_new_pass_enc = md5($user_new_pass);
            $update_stmt = "UPDATE users SET user_pass = '$user_new_pass_enc' WHERE user_id = '$user_id'";
            $update_q = mysqli_query($con, $update_stmt);
            if ($update_q) {
                session_unset();
                session_destroy();
                header('location: sign in.php');
            }
        } else
            $_SESSION['pass_error'] = $user_new_pass;
    }
?>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section" id="Account">
        <h1 style="font-size: 20px; margin:20px 0px 10px; color:#131619">Change Password</h1>

        <div class="form_container">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="change_pass_form">

                <div class="inp_sec">
                    <p>Current Password</p>
                    <div>
                        <?php
                            if (isset($_SESSION['reset_pass'])) {
                                echo'
                                    <input type="text" id="u_old_pass" value="'.$user_info['user_name'].'" name="old_pass" maxlength="30" size="30" autocomplete="off">
                                ';
                                unset($_SESSION['reset_pass']);
                            }
                            else
                            {
                                echo'
                                    <input type="text" id="u_old_pass" name="old_pass" maxlength="30" size="30" autocomplete="off">
                                ';
                            }
                        ?>
                    </div>
                </div>
                <div class="inp_sec">
                    <p>New Password</p>
                    <div>
                        <input type="text" id="u_new_pass_1" name="new_pass_1" maxlength="30" size="30" autocomplete="off">
                    </div>
                </div>
                <div class="inp_sec">
                    <p>Repeat New Password</p>
                    <div>
                        <input type="text" id="u_new_pass_2" name="new_pass_2" maxlength="30" size="30" autocomplete="off">
                    </div>
                    <p class="warning"></p>
                </div>

                <div class="form_action_sec">
                    <input type="click" name="change_pass_btn" id="submits" class="btn pri_btn" value="Save">
                    <input type="button" class="btn sec_btn" value="Cancel">
                </div>
            </form>
        </div>
    </div>

    <!--side section-->
    <div class="side_section">
        <?php include 'includes/temps/solid_side_section.php'; ?>
    </div>

    <?php
    if (isset($_SESSION['pass_error'])) {
        echo '
                <script>
                    document.getElementById("u_old_pass").style.border = "1px solid #e14";
                    document.getElementById("u_new_pass_1").value = "' . $_SESSION['pass_error'] . '";
                    document.getElementById("u_new_pass_2").value = "' . $_SESSION['pass_error'] . '";
                </script>
            ';
        $_SESSION['pass_error'] = null;
    }
    ?>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
    // remove red border if completed
    $(document).on('keyup input', '#Account input[type="text"]', function() {
        $(this).css('border', 'none');
    });

    // check if 2 password equal
    $(document).on('keyup input', '#Account #u_new_pass_2', function() {

        rep_pass = $(this).val();
        pass = $('#Account #u_new_pass_1').val();
        old_pass = $('#Account #u_old_pass').val();

        if (old_pass.trim() == "") {
            $('#Account #u_old_pass').attr('placeholder', 'please complete me');
            $('#Account #u_old_pass').css('border', '1px solid #e14');
        }
        if (pass.trim() == "") {
            $('#Account #u_new_pass_1').attr('placeholder', 'please complete me');
            $('#Account #u_new_pass_1').css('border', '1px solid #e14');
        }
        if (pass.trim() != rep_pass.trim() && (pass.length != 0)) {
            $(this).css('border', '1px solid #e14');
            $('#Account .warning').text('repeated password is incorrect');
        }
        if (pass.trim() == rep_pass.trim()) {
            $(this).css('border', 'none');
            $('#Account .warning').text('');
        }

    });

    // check if 2 password equal
    $(document).on('keyup input', '#Account #u_new_pass_1', function() {

        pass = $(this).val();
        rep_pass = $('#Account #u_new_pass_2').val();
        old_pass = $('#Account #u_old_pass').val();

        if (old_pass.trim() == "") {
            $('#Account #u_old_pass').attr('placeholder', 'please complete me');
            $('#Account #u_old_pass').css('border', '1px solid #e14');
        }
        if (rep_pass.trim() == "") {
            $('#Account #u_new_pass_2').attr('placeholder', 'please complete me');
            $('#Account #u_new_pass_2').css('border', '1px solid #e14');
        }
        if (pass.trim() != rep_pass.trim() && (rep_pass.length != 0)) {
            $('#Account #u_new_pass_2').css('border', '#e14');
            $('#Account .warning').text('repeated password is incorrect');
        }
        if (pass.trim() == rep_pass.trim()) {
            $('#Account #u_new_pass_2').css('border', 'none');
            $('#Account .warning').text('');
        }

    });
    // check for errors
    function FormValidation() {
        old_pass = $('#Account #u_old_pass').val();
        pass = $('#Account #u_new_pass_1').val();
        rep_pass = $('#Account #u_new_pass_2').val();
        no_error = true;

        if (old_pass.trim() == "") {
            $('#Account #u_old_pass').attr('placeholder', 'please complete me');
            $('#Account #u_old_pass').css('border', '1px solid #e14');
            no_error = false;
        }
        if (pass.trim() == "") {
            $('#Account #u_new_pass_1').attr('placeholder', 'please complete me');
            $('#Account #u_new_pass_1').css('border', '1px solid #e14');
            no_error = false;
        }
        if (rep_pass.trim() == "") {
            $('#Account #u_new_pass_2').attr('placeholder', 'please complete me');
            $('#Account #u_new_pass_2').css('border', '1px solid #e14');
            no_error = false;
        }
        if (pass.trim() != rep_pass.trim()) {
            $('#Account #u_new_pass_2').css('border', '#e14');
            $('#Account .warning').text('repeated password is incorrect');
            no_error = false;
        }

        return no_error;
    }
    // submit form
    $(document).on('click', 'input[type="click"]', function() {
        if (FormValidation()) {
            document.getElementById('change_pass_form').submit();
        }
    });
    // cancel 
    $(document).on('click', 'input[value="Cancel"]', function() {
        window.location.href = "settings.php?target_=account";
    });
</script>
<script src="js/functions.js"></script>
</body>