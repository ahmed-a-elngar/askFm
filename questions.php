<?php
    session_start();

    $_SESSION['active_tab'] = 'questions';

    $pageTitle = "Questions";
    include('init.php');

    unset($_SESSION['active_tab']);
    #if user is loged in
    loged('questions');

?>

    <!--main container-->
    <div class="main_container">
        
        <!--main section-->
        <div class="main_section">

            <?php
                $user_name = $_SESSION['user_name'];
                $q_table_name = $_SESSION['user_id'] . "_questions";
                $ques_query = "SELECT * from $q_table_name ORDER BY q_date DESC";
                $run_query = mysqli_query($con, $ques_query);
                $q_sum = mysqli_num_rows($run_query);
            ?>
            <!--questions header-->
            <div class="head_container">
                <h1>Questions</h1>
                <small>(</small>
                <small id="ques_count">
                <?php
                    echo $q_sum;
                ?>
                </small>
                <small>)</small>
            </div>

            <!--Delete questions & display or hide shoutouts section-->
            <div class="delete_questions">
                <i class="fas fa-trash trash_mark"></i>
                <small id="delete_all">Delete All Questions</small>
                <small id="shouts">shoutouts</small>
                <label class="switch">
                    <input type="checkbox" checked="true">
                    <span class="slider round" id="switch_q"></span>
                </label>
            </div>

            <!--Questions & shoutouts section-->
            <?php
                while($ques = mysqli_fetch_array($run_query))
                {
                    $q_content = $ques['q_content'];
                    $q_sender = $ques['q_sender'];
                    $sender_details_query = "SELECT user_full_name, user_pic from users where user_name = '$q_sender'";
                    $run_sender_query = mysqli_query($con, $sender_details_query);
                    $get_sender_details = mysqli_fetch_array($run_sender_query);
                    $q_date = $ques['q_date'];

                    if ($ques['q_status'] == "public" & $ques['q_type'] == "q") {
                        # code...
                        echo'
                            <div class="question_block">
                                <div class="question_pics">
                                        <a href="profile.php?user_name_='.$q_sender.'">
                                        <img src="'.$get_sender_details[1].'">
                                    </a>
                                </div>
                                <div style="display:inline-block;">
                                    <a href="profile.php?user_name_='.$q_sender.'" class="question_asked_name">'. "$get_sender_details[0]" .'</a>
                                    <a href="question_answer.php?q_id_='.$ques['q_id'].'#" class="question_content" dir="'. detectDir($ques["q_content"]).'">'."$q_content".'</a>
                                    <div class="time_container">
                                        <a href="#"> '
                                            .  printTime($q_date) .
                                        ' </a>
                                        <a class="more">...</a>
                                        <div id="qDropdown" class="dropdown-content-q">
                                            <a id="delete_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </a>
                                            <a id="report_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-flag"></i>
                                                Report question
                                            </a>
                                            <a id="block_q_u_btn" value="'.$ques['q_id'].'">
                                                <i class="fas fa-ban"></i>
                                                Block user
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                        /*
                        <img onclick="myFunction()" class="dropbtn" src="pics/icons/settings.png">
                        <div id="myDropdown" class="dropdown-content">
                            <a href="settings.php">settings</a>
                            <a href="log_out.php" title="log out">log out</a>
                        </div>
                        */
                    }
                    elseif ($ques['q_status'] == "private" & $ques['q_type'] == "q") {
                        echo'
                            <div class="question_block private">
                                <div class="question_pics">
                                        <a>
                                        <img src="'.randomPic().'">
                                    </a>
                                </div>
                                <div style="display:inline-block; margin-top:18px;">
                                <a href="question_answer.php?q_id_='.$ques['q_id'].'#" class="question_content" dir="'. detectDir($ques["q_content"]).'">'."$q_content".'</a>
                                <div class="time_container">
                                        <a href="#"> '
                                            .  printTime($q_date) .
                                        ' </a>
                                        <a class="more">...</a>
                                        <div id="qDropdown" class="dropdown-content-q">
                                            <a id="delete_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </a>
                                            <a id="report_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-flag"></i>
                                                Report question
                                            </a>
                                            <a id="block_q_u_btn" value="'.$ques['q_id'].'">
                                                <i class="fas fa-ban"></i>
                                                Block user
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                    elseif ($ques['q_status'] == "public" & $ques['q_type'] == "s")
                    {
                        echo'
                            <div class="question_block shout">
                                <div class="question_pics">
                                        <a href="profile.php?user_name_='.$q_sender.'">
                                        <img src="'.$get_sender_details[1].'">
                                    </a>
                                </div>
                                <div style="display:inline-block;">
                                    <a href="profile.php?user_name_='.$q_sender.'" class="question_asked_name">'. "$get_sender_details[0]" .'</a>
                                    <a href="question_answer.php?q_id_='.$ques['q_id'].'#" class="question_content" dir="'. detectDir($ques["q_content"]).'">'."$q_content".'</a>
                                    <div class="time_container">
                                        <a href="#"> shoutout | '
                                            .  printTime($q_date) .
                                        ' </a>
                                        <a class="more">...</a>
                                        <div id="qDropdown" class="dropdown-content-q">
                                            <a id="delete_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </a>
                                            <a id="report_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-flag"></i>
                                                Report question
                                            </a>
                                            <a id="block_q_u_btn" value="'.$ques['q_id'].'">
                                                <i class="fas fa-ban"></i>
                                                Block user
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                    else{
                        echo'
                            <div class="question_block private shout">
                                <div class="question_pics">
                                        <a>
                                        <img src="'.randomPic().'">
                                    </a>
                                </div>
                                <div style="display:inline-block; margin-top:18px;">
                                <a href="question_answer.php?q_id_='.$ques['q_id'].'#" class="question_content" dir="'. detectDir($ques["q_content"]).'">'."$q_content".'</a>
                                <div class="time_container">
                                        <a href="#"> shoutout | '
                                            .  printTime($q_date) .
                                        ' </a>
                                        <a class="more">...</a>
                                        <div id="qDropdown" class="dropdown-content-q">
                                            <a id="delete_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </a>
                                            <a id="report_q_btn" value="'.$ques['q_id'].'">
                                                <i class="fa fa-flag"></i>
                                                Report question
                                            </a>
                                            <a id="block_q_u_btn" value="'.$ques['q_id'].'">
                                                <i class="fas fa-ban"></i>
                                                Block user
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }

            ?>

        </div>

        <!--side section-->
        <div class="side_section">
            <?php
                include 'includes/temps/friends_side_section.php';
            ?>
            <div class="sticky">
                <?php include 'includes/temps/solid_side_section.php'; ?>
            </div>
        </div>

    </div>

<?php
    include 'includes/temps/footer.php';
?>