<?php
    session_start();

    $pageTitle = "notifications";
    include('init.php');

    #if user is loged in
    loged('notifications');

    $user_id = $_SESSION["user_id"];
    $n_table_name = $user_id . '_notifications';
    $select_n = "SELECT * from $n_table_name ORDER BY n_date DESC";
    $select_q = mysqli_query($con, $select_n);
    $notis = mysqli_fetch_all($select_q);
?>

<!--main container-->
<div class="main_container">
    
    <!--main section-->
    <div class="main_section">

        <!--notifications header-->
        <div class="head_container">
            <h1>Notifications </h1>
        </div>

        <!--nav header-->
        <div class="main_container_menu">
            <nav>
                <a href="#" id="defaultOpen" class="tablinks" onclick="openCity(event, 'All')">
                    All
                </a>
                <a href="#"  class="tablinks" onclick="openCity(event, 'Questions')">
                    Questions
                </a>
                <a href="#" class="tablinks" onclick="openCity(event, 'Answers')">
                    Answers
                </a>
                <a href="#"  class="tablinks" onclick="openCity(event, 'Likes')">
                    Likes
                </a>
            </nav>
        </div>

        <!--all tab-->
        <div id="All" class="tabcontent">
        <?php
            foreach($notis as $noti){

                $noti_info = preg_split("/,/", $noti[1]);
                $n_read = $noti[2] == 0 ? ' active_mood' : "";

                if (count($noti_info) == 4) // noti type is versus vote or coins
                {
                    if($noti_info[0] == "c") // coins noti
                    {
                        $a_id = $noti_info[1];
                        $c_count = $noti_info[2];
                        $r_u_id = $noti_info[3];

                        $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $a_table_name = $user_id . '_answers';
                        $select_q_c = "SELECT q_content from $a_table_name where a_id = '$a_id'";
                        $q_content = mysqli_fetch_array(mysqli_query($con, $select_q_c))["q_content"];
                        $q_date = $noti[3];
    
                        echo'
                            <div class="notification_block'. $n_read .'">                   
                                <div class="notification_pics">
                                    <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                        <img src="'.$user_info["user_pic"].'">
                                        <img src="pics/icons/coin.png" style="height: 23px; width: 23px; position: absolute; margin-left: -18px; margin-top: 22px;">
                                    </a>
                                </div>
                                <div class="notification_details">
                                    <a href="answer_details.php?user_id_='.$user_id.'&a_id_='.$a_id.'#" class="question_content">
                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                        give You '.$c_count.' coins for: "
                                        <strong>
                                            <span>
                                                '.$q_content.'
                                            </span>
                                        </strong>
                                        "
                                    </a>
                                    <div class="time_container">
                                        <a href="#">'.  printTime($q_date) .'</a>
                                    </div>
                                </div>
                            </div> 
                        ';
                    }
                    else{
                        $v_id = $noti_info[1];
                        $v_table_name = $user_id . '_versus';
                        $v_choice = $noti_info[2];
                        $r_u_id = $noti_info[3];
    
                        $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $select_q_c = "SELECT v_head, v_l1_sum, v_l2_sum from $v_table_name where v_id = '$v_id'";
                        $v_query = mysqli_query($con, $select_q_c);
                        $v_info = mysqli_fetch_array($v_query);
                        $v_head = $v_info['v_head'];
                        $v_l_sum = $v_info['v_l1_sum'] + $v_info['v_l2_sum'] - 1;
                        $v_date = $noti[3];
                        $v_others = $v_l_sum > 0 ? (' and '.$v_l_sum. ' others') : "";
    
                        echo'
                            <div class="notification_block'.$n_read.'">                   
                                <div class="notification_pics">
                                    <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                        <img src="'.$user_info["user_pic"].'">
                                        <i class="fa fa-heart heart_mark"></i>
                                    </a>
                                </div>
                                <div class="notification_details">
                                    <a href="view_versus.php?user_id_='.$user_id.'&v_id_='.$v_id.'#" class="question_content">
                                        <strong class="asked_name">'.$user_info["user_full_name"]. $v_others. ' </strong>
                                        Voted on your photo poll: "
                                        <strong>
                                            <span>
                                                '.$v_head.'
                                            </span>
                                        </strong>
                                        "
                                    </a>
                                    <div class="time_container">
                                        <a href="#">'.  printTime($v_date) .'</a>
                                    </div>
                                </div>
                            </div> 
                        ';
                    }
                }
                if (count($noti_info) == 3) { // noti type is like
                    
                    $a_id = $noti_info[0];
                    $a_table_name = $noti_info[1];
                    $r_u_id = $noti_info[2];

                    $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                    $select_u_q = mysqli_query($con, $select_u);
                    $user_info = mysqli_fetch_array($select_u_q);
                    
                    $a_table_name = $user_id . '_answers';
                    $select_q_c = "SELECT q_content from $a_table_name where a_id = '$a_id'";
                    $q_content = mysqli_fetch_array(mysqli_query($con, $select_q_c))["q_content"];
                    $q_date = $noti[3];

                    echo'
                        <div class="notification_block'. $n_read .'">                   
                            <div class="notification_pics">
                                <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                    <img src="'.$user_info["user_pic"].'">
                                    <i class="fa fa-heart heart_mark"></i>
                                </a>
                            </div>
                            <div class="notification_details">
                                <a href="answer_details.php?user_id_='.$user_id.'&a_id_='.$a_id.'#" class="question_content">
                                    <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                    likes your answer: "
                                    <strong>
                                        <span>
                                            '.$q_content.'
                                        </span>
                                    </strong>
                                    "
                                </a>
                                <div class="time_container">
                                    <a href="#">'.  printTime($q_date) .'</a>
                                </div>
                            </div>
                        </div> 
                    ';
                }
                if (count($noti_info) == 2)
                { // noti type is answer or question or versus created
                    $t_id = $noti_info[0];
                    $t_table_name = $noti_info[1];
                    $noti_more_info = preg_split("/_/", $t_table_name);
                    if($noti_more_info[1] == "answers")  // user answer your question
                    {    
                        $r_u_id = $noti_more_info[0];
                        $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $select_q_c = "SELECT q_content, a_date from $t_table_name where a_id = $t_id";
                        $q_content = mysqli_fetch_array(mysqli_query($con, $select_q_c))[0];
                        $a_date = mysqli_fetch_array(mysqli_query($con, $select_q_c))[1];

                        echo'
                            <div class="notification_block'.$n_read.'">                     
                                <div class="notification_pics">
                                    <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                        <img src="'.$user_info["user_pic"].'">
                                        <i class="answer_mark">✉</i>
                                    </a>
                                </div>
                                <div class="notification_details">
                                    <a href="answer_details.php?user_id_='.$r_u_id.'&a_id_='.$t_id.'#" class="question_content">

                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                            answered your question: "
                                        <strong>
                                            <span>
                                                '.$q_content.'
                                            </span>
                                        </strong>
                                        "
                                    </a>
                                    <div class="time_container">
                                        <a href="#">'.  printTime($a_date) .'</a>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                    elseif($noti_more_info[1] == "questions") // receive a question or shoutout
                    {    

                        
                        $select_q_c = "SELECT * from $t_table_name where q_id = $t_id";
                        $select_q_c_q = mysqli_query($con, $select_q_c);
                        $q_info = mysqli_fetch_array($select_q_c_q);
                        $q_type = $q_info["q_type"];
                        $q_status = $q_info["q_status"];
                        $q_content = $q_info["q_content"];
                        $q_date = $q_info["q_date"];

                        $q_sender =  $q_info["q_sender"];
                        $select_u = "SELECT * FROM users where user_name = '$q_sender'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);

                        echo'
                            <div class="notification_block'.$n_read.'">
                            <div class="notification_pics">';
                            if ($q_status == "public") {
                                echo'
                                <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                    <img src="'.$user_info["user_pic"].'">
                                ';
                            }
                            else{
                                echo'
                                <a>
                                    <img src="'.randomPic().'">
                                ';
                            }

                        echo'       <i class="question_mark">?</i>
                                </a>
                            </div>
                            <div class="notification_details">
                            <a href="question_answer.php?q_id_='.$t_id.'#" class="question_content">';
                                if ($q_status == "public" & $q_type == "q") {
                                    echo'
                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                        asked you: "
                                    ';
                                }  
                                elseif ($q_status == "private" & $q_type == "q")
                                {
                                    echo'
                                        you have a new question: "
                                    ';
                                }
                                elseif($q_status == "public" & $q_type == "s")
                                {
                                    echo'
                                        you received a shoutout from
                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                        : "
                                    '; 
                                } 
                                else
                                {
                                    echo'
                                        you received a shoutout: "
                                    ';   
                                } 
                            echo '   <strong>
                                        <span>
                                            '.$q_content.' 
                                        </span>
                                    </strong>
                                    "
                                </a>
                                <div class="time_container">
                                    <a href="#">'.  printTime($q_date) .'</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    elseif($noti_more_info[1] == "versus") // created a poll
                    {
                        $t_u_id = $noti_more_info[0];
                        $select_u = "SELECT * FROM users where user_id = '$t_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $select_q_c = "SELECT v_head, v_date from $t_table_name where v_id = $t_id";
                        $v_head = mysqli_fetch_array(mysqli_query($con, $select_q_c))[0];
                        $v_date = mysqli_fetch_array(mysqli_query($con, $select_q_c))[1];

                        echo'
                            <div class="notification_block'.$n_read.'">                     
                                <div class="notification_pics">
                                    <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                        <img src="'.$user_info["user_pic"].'">
                                    </a>
                                </div>
                                <div class="notification_details">
                                    <a href="view_versus.php?user_id_='.$t_u_id.'&v_id_='.$t_id.'#" class="question_content">
                                            your friend
                                            <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                            just created a poll:"
                                        <strong>
                                            <span>
                                                '.$v_head.'
                                            </span>
                                        </strong>
                                        " 
                                        <i class="fa fa-star" style="color:yellow;"></i>
                                    </a>
                                    <div class="time_container">
                                        <a href="#">'.  printTime($v_date) .'</a>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            }
        ?>
        </div>
        <!--Questions tab-->
        <div id="Questions" class="tabcontent">
        <?php
            foreach($notis as $noti){

                $noti_info = preg_split("/,/", $noti[1]);
                $n_read = $noti[2] == 0 ? ' active_mood' : "";

                if (count($noti_info) == 2)
                {
                    // noti type is answer or question or versus
                    $t_id = $noti_info[0];
                    $t_table_name = $noti_info[1];
                    $noti_more_info = preg_split("/_/", $t_table_name);

                    if($noti_more_info[1] == "questions") {    
                        $r_u_id = $noti_more_info[0];
                        $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $select_q_c = "SELECT * from $t_table_name where q_id = $t_id";
                        $select_q_c_q = mysqli_query($con, $select_q_c);
                        $q_info = mysqli_fetch_array($select_q_c_q);
                        $q_type = $q_info["q_type"];
                        $q_status = $q_info["q_status"];
                        $q_content = $q_info["q_content"];
                        $q_date = $q_info["q_date"];

                        echo'
                            <div class="notification_block'.$n_read.'">
                            <div class="notification_pics">';
                            if ($q_status == "public") {
                                echo'
                                <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                    <img src="'.$user_info["user_pic"].'">
                                ';
                            }
                            else{
                                echo'
                                <a>
                                    <img src="'.randomPic().'">
                                ';
                            }

                        echo'       <i class="question_mark">?</i>
                                </a>
                            </div>
                            <div class="notification_details">
                            <a href="question_answer.php?q_id_='.$t_id.'#" class="question_content">';
                                if ($q_status == "public" & $q_type == "q") {
                                    echo'
                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                        asked you: "
                                    ';
                                }  
                                elseif ($q_status == "private" & $q_type == "q")
                                {
                                    echo'
                                        you have a new question: "
                                    ';
                                }
                                elseif($q_status == "public" & $q_type == "s")
                                {
                                    echo'
                                        you received a shoutout from
                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                        : "
                                    '; 
                                } 
                                else
                                {
                                    echo'
                                        you received a shoutout: "
                                    ';   
                                } 
                            echo '   <strong>
                                        <span>
                                            '.$q_content.' 
                                        </span>
                                    </strong>
                                    "
                                </a>
                                <div class="time_container">
                                    <a href="#">'.  printTime($q_date) .'</a>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                }
            }
        ?>
        </div>
        <!--Answers tab-->
        <div id="Answers" class="tabcontent">
        <?php
            foreach($notis as $noti){

                $noti_info = preg_split("/,/", $noti[1]);
                $n_read = $noti[2] == 0 ? ' active_mood' : "";

                if (count($noti_info) == 2)
                {
                    // noti type is answer or question or versus
                    $t_id = $noti_info[0];
                    $t_table_name = $noti_info[1];
                    $noti_more_info = preg_split("/_/", $t_table_name);

                    if($noti_more_info[1] == "answers") {
                            
                        $r_u_id = $noti_more_info[0];
                        $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $select_q_c = "SELECT q_content, a_date from $t_table_name where a_id = $t_id";
                        $q_content = mysqli_fetch_array(mysqli_query($con, $select_q_c))[0];
                        $a_date = mysqli_fetch_array(mysqli_query($con, $select_q_c))[1];

                        echo'
                            <div class="notification_block'.$n_read.'">                     
                                <div class="notification_pics">
                                    <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                        <img src="'.$user_info["user_pic"].'">
                                        <i class="answer_mark">✉</i>
                                    </a>
                                </div>
                                <div class="notification_details">
                                    <a href="answer_details.php?user_id_='.$r_u_id.'&a_id_='.$t_id.'#" class="question_content">

                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                            answered your question: "
                                        <strong>
                                            <span>
                                                '.$q_content.'
                                            </span>
                                        </strong>
                                        "
                                    </a>
                                    <div class="time_container">
                                        <a href="#">'.  printTime($a_date) .'</a>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                }
            }
        ?>
        </div>
        <!--Likes tab-->
        <div id="Likes" class="tabcontent">
        <?php
            foreach($notis as $noti){

                $noti_info = preg_split("/,/", $noti[1]);
                $n_read = $noti[2] == 0 ? ' active_mood' : "";

                if (count($noti_info) == 3) { // noti type is like

                        $a_id = $noti_info[0];
                        $a_table_name = $noti_info[1];
                        $r_u_id = $noti_info[2];
    
                        $select_u = "SELECT * FROM users where user_id = '$r_u_id'";
                        $select_u_q = mysqli_query($con, $select_u);
                        $user_info = mysqli_fetch_array($select_u_q);
                        
                        $a_table_name = $user_id . '_answers';
                        $select_q_c = "SELECT q_content from $a_table_name where a_id = $a_id";
                        $q_content = mysqli_fetch_array(mysqli_query($con, $select_q_c))[0];
    
                        echo'
                            <div class="notification_block'.$n_read.'">                   
                                <div class="notification_pics">
                                    <a href="profile.php?user_name_='.$user_info["user_name"].'">
                                        <img src="'.$user_info["user_pic"].'">
                                        <i class="fa fa-heart heart_mark"></i>
                                    </a>
                                </div>
                                <div class="notification_details">
                                    <a href="answer_details.php?user_id_='.$user_id.'&a_id_='.$a_id.'#" class="question_content">
                                        <strong class="asked_name">'.$user_info["user_full_name"].'</strong>
                                        likes your answer: "
                                        <strong>
                                            <span>
                                                '.$q_content.'
                                            </span>
                                        </strong>
                                        "
                                    </a>
                                    <div class="time_container">
                                        <a href="#">'.  printTime($q_date) .'</a>
                                    </div>
                                </div>
                            </div> 
                        ';
                }

            }
        ?>  
        </div>

    </div>

<!--side section-->
<div class="side_section">
    <?php include 'includes/temps/solid_side_section.php'; ?>
</div>


</div>
<script src="js/jquery-3.3.1.min.js"></script>
<!--settings navbar js-->
<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>
<?php
    notiReaded($n_table_name);
    include('includes/temps/footer.php');
?>