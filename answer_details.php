<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    # code...
    header('location: sign in.php');
    exit();
}
$pageTitle = "Answer Details";
include('init.php');
$u_id = $_GET["user_id_"];
$a_id = $_GET["a_id_"];
$a_table_name = $u_id . "_answers";

?>

<!--background gradient-->
<div class="backgroundWrap withImage">
    <img alt="">
    <div class="gradient"></div>
</div>

<!--main container-->
<div class="main_container">
    
    <!--main section-->
    <div class="main_section transparent details">

        <?php
            $select_answers = "SELECT * from $a_table_name where a_id = $a_id";
            $select_answers_query = mysqli_query($con, $select_answers);
            $answer_info = mysqli_fetch_array($select_answers_query);

            if ($answer_info == null) {
                header('location: unReachable.php');
                exit();
            }

            #sender info
            $sender_user_name = $answer_info["q_sender"];
            $select_sender = "SELECT * from users where user_name = '$sender_user_name'";
            $select_sender_query = mysqli_query($con, $select_sender);
            $sender_info = mysqli_fetch_array($select_sender_query);

            #sender info
            $select_receiver = "SELECT * from users where user_id = '$u_id'";
            $select_receiver_query = mysqli_query($con, $select_receiver);
            $receiver_info = mysqli_fetch_array($select_receiver_query);

            $a_date = $answer_info['a_date'];
            $q_dir = detectDir($answer_info["q_content"]);
            $img_float = $q_dir == "ltr" ? 'style="float:right;"' : 'style="float:left;"';

            echo '
                <div class="answer_box">
                    <div dir="'.$q_dir.'" class="answered_question_section">
                        <!--the answered question-->
                        <h3 class="answered_question_content">' . $answer_info["q_content"] . '</h3>
                        <!--the asked img & name-->
            ';
            if ($answer_info["q_status"] == "public") {
                # code...
                echo '
                                    <a href="profile.php?user_name_='.$sender_user_name.'" class="asked_details">
                                    <img src="' . $sender_info["user_pic"] . '" '. $img_float .' alt="" >
                ';                                            
                        
                echo'
                    <span>' . $sender_info["user_full_name"] . '</span>
                    </a>
                ';
            }
            echo '
                            </div>
                            <div class="answer_receiver">
                                <div class="friend_pics">
                                    <a href="profile.php?user_name_='.$receiver_info["user_name"].'">
                                        <img src="'.$receiver_info["user_pic"].'">
            ';
            if($receiver_info['user_mood'] != null and $receiver_info['user_mood'] != 0)
            {
                echo'
                    <div class="user_mood">
                        <img src="pics/moods/'.$receiver_info['user_mood'].'.gif">
                    </div>
                ';
            }       
            echo'                      
                                    </a>
                                </div>
                                <div >
                                    <a href="profile.php?user_name_='.$receiver_info["user_name"].'">
                                        <span class="friend_name">'.$receiver_info["user_full_name"].'</span>
                                    </a>
                                </div>
                                <div class="time_container">
                                    <a>' . printTime($a_date) . '</a>
                                </div>
                            </div>
                            <!--the answer content-->
                            <div class="answer_content" dir="'. detectDir($answer_info["a_content"]).'">
                            ' . $answer_info["a_content"] 
            ;
            if ($answer_info["a_pic"] != null) {
                            echo '
                                <img src="'.$answer_info['a_pic'].'">
                            ';
            }
            echo'
                            </div>
                            <!--interactions with the answer (like, reward, share, ...)-->
                            <div class="iteractions_section">
            ';
            if (interacted($_SESSION['user_id'], $a_table_name, $answer_info['a_id'], "likes")) {
                echo '
                                        <button class="heart" title="Like" onclick="likeMe('.$receiver_info["user_id"].',' . $answer_info["a_id"] . ')" value="' . $receiver_info["user_id"].',' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#ee1144;"></i>
                                        </button>
                                    ';
            } else {
                echo '
                                        <button class="heart" title="Like" onclick="likeMe('.$receiver_info["user_id"].',' . $answer_info["a_id"] . ')" value="' . $receiver_info["user_id"].',' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#b2b2bb;"></i>
                                        </button>
                                    ';
            }
            echo '
                                <a href="answer_details.php?user_id_=' . $receiver_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="likes_count">' . $answer_info["l_sum"] . '</a>';
            if (interacted($_SESSION['user_id'], $a_table_name, $answer_info['a_id'], "coins")) {
                echo '
                                        <button class="heart reward" title="Reward" onclick="rewardMe('.$receiver_info["user_id"].','.$answer_info["a_id"].')" value="' . $receiver_info["user_id"].',' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#ee1144"></i>                                        
                                        </button>
                                    ';
            } else {
                echo '
                                        <button class="heart reward" title="Reward" onclick="rewardMe('.$receiver_info["user_id"].','.$answer_info["a_id"].')" value="' . $receiver_info["user_id"].',' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#b2b2bb"></i>                                        
                                        </button>
                                    ';
            }
            echo 
            '
                                <a href="answer_details.php?user_id_=' . $receiver_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="coins_count">' . $answer_info["c_sum"] . '</a>
                                <a class="interacttions more" title="more">...</a>
                                <div id="qDropdown" class="dropdown-content-a">
                                    <a id="report_q_btn" value="'.$answer_info['a_id'].'">
                                        <i class="fa fa-flag"></i>
                                        Report post
                                    </a>
                                </div>
                                <a href="ask_friends.php?q_content=' . $answer_info["q_content"] . '" class="interacttions">
                                    <i class="fa fa-recycle" title="forward" style="font-size: 22px; color:#b2b2bb"></i>
                                </a>
                            </div>
                        </div>
            ';
        ?>
        
        <!--nav header-->
        <div class="main_container_menu big">
            <nav class="dark">
                <a href="#" id="defaultOpen" class="tablinks" onclick="openCity(event, 'Likes')">
                    Likes
                </a>
                <a href="#" class="tablinks" onclick="openCity(event, 'Coins')">
                    Coins
                </a>
            </nav>
        </div>

        <div id="Likes" class="tabcontent">
            <?php
                $r_users_l = preg_split("/,/",$answer_info["l_users"]);
                for($i = count($r_users_l) ; $i > 0 ; $i--)
                {
                    $user = $r_users_l[$i - 1];
                    $frnds_query = "SELECT * from users where user_id = '$user'";
                    $run_query = mysqli_query($con, $frnds_query);
                    $r_user_info = mysqli_fetch_array($run_query);

                    if ($r_user_info["user_name"] != "") {
                        echo
                        '<div class="friend_section" style="border: none;">
                            <div class="friend_pics">
                                <a href="profile.php?user_name_='.$r_user_info["user_name"].'">
                                    <img src="'.$r_user_info["user_pic"].'">
                                </a>
                            </div>
                            <div >
                                <a href="profile.php?user_name_='.$r_user_info["user_name"].'" class="friend_info">
                                    <span class="friend_name">'.$r_user_info["user_full_name"].'</span>
                                    <span class="friend_user_name">@'.$r_user_info["user_name"].'</span>
                                </a>
                            </div>
                        </div>';
                    }

                }
            ?>
        </div>

        <div id="Coins" class="tabcontent">
            <?php
                
                $n_table_name = $u_id . '_notifications';
                $select_noti_stmt = "SELECT * FROM $n_table_name";
                $select_noti_q = mysqli_query($con, $select_noti_stmt);
                $notis = mysqli_fetch_all($select_noti_q);
                
                $r_users_l = preg_split("/,/",$answer_info["c_users"]);
                for($i = count($r_users_l) ; $i > 0 ; $i--)
                {
                    $user = $r_users_l[$i - 1];
                    $frnds_query = "SELECT * from users where user_id = '$user'";
                    $run_query = mysqli_query($con, $frnds_query);
                    $r_user_info = mysqli_fetch_array($run_query);

                    if ($r_user_info["user_name"] != "") {

                        $c_count = 0;
                        // retrive coins count
                        foreach($notis as $noti)
                        {
                            $noti_info = preg_split("/,/", $noti[1]);                
                            if($noti_info[0] == "c") // coins noti
                            {
                                if ($noti_info[1] == $a_id) { // same answer
                                    if($noti_info[3] == $r_user_info["user_id"])   // same user
                                    {
                                        $c_count += $noti_info[2];
                                    }       
                                }
                            }
                        }

                        echo
                        '<div class="friend_section" style="border: none;">
                            <div class="friend_pics">
                                <a href="profile.php?user_name_='.$r_user_info["user_name"].'">
                                    <img src="'.$r_user_info["user_pic"].'">
                                </a>
                            </div>
                            <div>
                                <a href="profile.php?user_name_='.$r_user_info["user_name"].'" class="friend_info">
                                    <span class="friend_name">'.$r_user_info["user_full_name"].'</span>
                                    <span class="friend_user_name">@'.$r_user_info["user_name"].'</span>
                                </a>
                            </div>
                            <div class="coins_info">
                                <div>
                                    <img src="pics/icons/coin.png" alt="">
                                    <span>'.$c_count.'</span>
                                </div>
                            </div>
                        </div>';
                    }
                }
            ?>
        </div>

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

<!--settings navbar js-->
<script src="js/jquery-3.3.1.min.js"></script>
<script>

    a_id = -1;
    change = 1;
    old_val = -1;
    a_owner = -1;
    value = "";

    // like or dislike answer
    function likeMe(u_id, ans_id) {
        a_id = ans_id
        r_u_id = <?php echo $_SESSION['user_id']; ?>;
        table_name = u_id + '_answers';
        value = u_id + ',' + ans_id;

        l_count = $("[title='Like'][value='" + value + "'] + a").html();
        if (r_u_id != u_id) {
            x = $("[title='Like'][value='" + value + "'] + a").load("includes/funcs/likeOrDislike.php", {
                pa_1: r_u_id,
                pa_2: u_id,
                pa_3: table_name,
                pa_4: a_id,
                pa_5: l_count
            });
        }
        old_val = l_count;
        a_owner = u_id;
    }
    // give reward
    function rewardMe(u_id, ans_id) {
        a_id = ans_id
        r_u_id = <?php echo $_SESSION['user_id']; ?>;
        table_name = u_id + '_answers';
        value = u_id + ',' + ans_id;

        c_count = $("[title='Reward'][value='" + value + "'] + a").html();

        if (r_u_id != u_id) {
            $("[title='Reward'][value='" + value + "'] + a").load("includes/funcs/rewards.php", {
                pa_1: r_u_id,
                pa_2: u_id,
                pa_3: table_name,
                pa_4: a_id,
                pa_5: c_count
            });
        }
    }
    // change like button color
    $(document).on("DOMSubtreeModified", "[title='Like']+ a", function(){
        new_val = $("[title='Like'][value='" + value + "'] + a").html();

        if(new_val != old_val && new_val != "")
        {
            old_color = $("i", "[title='Like'][value='" + value + "']").css("color");
            if(old_color == "rgb(178, 178, 187)")
            {
                color = "#ee1144";
            }
            else if(old_color == "rgb(238, 17, 68)")
            {
                color = "#b2b2bb";
            }
            $("i", "[title='Like'][value='" + value + "']").css("color", color);
            old_val = new_val;
        }

    });
    // change reward button color    
    $(document).on("DOMSubtreeModified", "[title='Reward']+ a", function(){

        new_val = $("[title='Reward'][value='" + value + "'] + a").html();
        if(change  == 2 )
        {
            if(new_val != old_val)
            {
                $("i", "[title='Reward'][value='" + value + "']").css("color", "#e14");
            }
            change = 1;
        }
        else
            change += 1;
    });

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
<script src="js/functions.js"></script>
</body>
</html>