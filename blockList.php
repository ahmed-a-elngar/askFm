<?php
    session_start();

    $pageTitle = "Settings | Change Password";
    include('init.php');

    #if user is loged in
    loged('change_password');

    $user_id = $_SESSION["user_id"];
    $b_table_name = $user_id . '_blocks';

    $select_b_stmt = "SELECT * FROM $b_table_name";
    $select_b_q = mysqli_query($con, $select_b_stmt);

?>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section">
        <h1 style="font-size: 20px; margin:20px 0px; padding:20px; color:#131619">
            Blocked List
            <strong>
                (<?php echo mysqli_num_rows($select_b_q); ?>)
            </strong>
        </h1>

        <?php
            while($blocked_info = mysqli_fetch_array($select_b_q))
            {
                $b_info = preg_split("/,/", $blocked_info['b_info']);

                if (count($b_info) == 1) // profile (u_id)
                {
                    $b_u_id = $b_info[0];
                    $select_u_stmt = "SELECT * FROM users WHERE user_id = '$b_u_id'";
                    $select_u_q = mysqli_query($con, $select_u_stmt);
                    $b_u_info = mysqli_fetch_array($select_u_q);

                    echo'
                        <div class="friend_section big" style="border: none; padding:10px 20px;">
                            <div class="friend_pics">
                                <a href="profile.php?user_name_=' . $b_u_info["user_name"] . '">
                                    <img src="' . $b_u_info["user_pic"] . '" title="'.$b_u_info['user_full_name'].'">
                                </a>
                            </div>
                            <div >
                                <a href="profile.php?user_name_=' . $b_u_info["user_name"] . '">
                                    <span class="friend_name">' . $b_u_info["user_full_name"] . '</span>
                                    <span class="friend_user_name">@' . $b_u_info["user_name"] . '</span>
                                </a>
                            </div>
                            <div class="ask_friend_btn">
                                <a id="unblock_btn" value="' . $blocked_info["b_id"] . '">Un block</a>
                            </div>
                        </div>
                    ';
                }
                else if(count($b_info) == 4)    // question or shoutout (q_content, q_type, q_status, u_id)
                {

                    $q_content = $b_info[0];
                    $q_type = $b_info[1];
                    $q_status = $b_info[2];
                    $q_sender = $b_info[3];

                    $sender_details_query = "SELECT * from users where user_id = '$q_sender'";
                    $run_sender_query = mysqli_query($con, $sender_details_query);
                    $get_sender_details = mysqli_fetch_array($run_sender_query);

                    if ($q_status == "public" & $q_type == "q") {
                        echo'
                            <div class="friend_section big" style="border: none; padding:10px 20px; overflow:hidden">
                                <div class="friend_pics" style="float:left;">
                                    <a href="profile.php?user_name_=' . $get_sender_details['user_name'] . '">
                                        <img src="' . $get_sender_details['user_pic'] . '" title="'.$get_sender_details['user_full_name'].'">
                                    </a>
                                </div>
                                <div style="float:left; margin-top: 8px; width:300px;">
                                        <span class="question_content">' . $q_content . '</span>
                                </div>
                                <div class="ask_friend_btn">
                                    <a id="unblock_btn" value="' . $blocked_info["b_id"] . '">Un block</a>
                                </div>
                            </div>
                        ';
                    }
                    elseif ($q_status == "private" & $q_type == "q") {
                        echo'
                            <div class="friend_section big" style="border: none; padding:10px 20px; overflow:hidden">
                                <div class="friend_pics" style="float:left;">
                                    <a>
                                        <img src="' .randomPic(). '" title="Anonomous">
                                    </a>
                                </div>
                                <div style="float:left; margin-top: 8px; width:300px;">
                                        <span class="question_content">' . $q_content . '</span>
                                </div>
                                <div class="ask_friend_btn">
                                    <a id="unblock_btn" value="' . $blocked_info["b_id"] . '">Un block</a>
                                </div>
                            </div>
                        ';
                    }
                    elseif ($q_status == "public" & $q_type == "s")
                    {
                        echo'
                            <div class="friend_section big" style="border: none; padding:10px 20px; overflow:hidden">
                                <div class="friend_pics" style="float:left;">
                                    <a href="profile.php?user_name_=' . $get_sender_details['user_name'] . '">
                                        <img src="' . $get_sender_details['user_pic'] . '" title="'.$get_sender_details['user_full_name'].'">
                                    </a>
                                </div>
                                <div style="float:left; margin-top: 8px; width:300px;">
                                        <span class="question_content">' . $q_content . '</span>
                                </div>
                                <div class="ask_friend_btn">
                                    <a id="unblock_btn" value="' . $blocked_info["b_id"] . '">Un block</a>
                                </div>
                            </div>
                        ';
                    }
                    else{
                        echo'
                            <div class="friend_section big" style="border: none; padding:10px 20px; overflow:hidden">
                                <div class="friend_pics" style="float:left;">
                                    <a>
                                        <img src="' .randomPic(). '" title="Anonomous">
                                    </a>
                                </div>
                                <div style="float:left; margin-top: 8px; width:300px;">
                                        <span class="question_content">' . $q_content . '</span>
                                </div>
                                <div class="ask_friend_btn">
                                    <a id="unblock_btn" value="' . $blocked_info["b_id"] . '">Un block</a>
                                </div>
                            </div>
                        ';
                    }
                }
            }
        ?>

    </div>

    <!--side section-->
    <div class="side_section">
        <?php include 'includes/temps/solid_side_section.php'; ?>
    </div>

</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
    $(document).on('click', '#unblock_btn', function(){
        info = $(this).attr('value');
        $(this).load('includes/funcs/unblockMe.php', {
            pa_1: info
        });
        $(this).parentsUntil('.main_section').remove();
    });
</script>
<script src="js/functions.js"></script>
</body>