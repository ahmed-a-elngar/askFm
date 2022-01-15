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
            $a_date = $answer_info['a_date'];
            $q_dir = detectDir($answer_info["q_content"]);
            $img_float = $q_dir == "ltr" ? 'style="float:right;"' : 'style="float:left;"';

            #<!--answer-->
            echo '
            <div class="answer_box">
                            <div dir="'.$q_dir.'" class="answered_question_section">
                                <!--the answered question-->
                                <h3 class="answered_question_content">' . $answer_info["q_content"] . '</h3>
                                <!--the asked img & name-->';
            if ($answer_info["q_status"] == "public") {
                # code...
                echo '
                                    <a href="profile.php?user_name_='.$sender_user_name.'" class="asked_details">
                                    <img src="' . $sender_info["user_pic"] . '" '. $img_float .' alt="" >
                                    <span>' . $sender_info["user_full_name"] . '</span>
                                    </a>';
            }
            echo '
                            </div>
                            <!--time left after being answered-->
                            <div class="time_container">
                                <a>' . printTime($a_date) . '</a>
                            </div>
                            <!--the answer content-->
                            <div class="answer_content" dir="'. detectDir($answer_info["a_content"]).'">
                            ' . $answer_info["a_content"] . '
                            </div>
                            <!--interactions with the answer (like, reward, share, ...)-->
                            <div class="iteractions_section">';
            if (interacted($_SESSION['user_id'], $a_table_name, $answer_info['a_id'], "likes")) {
                echo '
                                        <button class="heart" title="Like" value="' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#ee1144;"></i>
                                        </button>
                                    ';
            } else {
                echo '
                                        <button class="heart" title="Like" value="' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#b2b2bb;"></i>
                                        </button>
                                    ';
            }
            echo '
                <a href="#" class="likes_count">' . $answer_info["l_sum"] . '</a>';
            if (interacted($_SESSION['user_id'], $a_table_name, $answer_info['a_id'], "coins")) {
                echo '
                                        <button class="heart reward" title="Reward" value="' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#ee1144"></i>                                        
                                        </button>
                                    ';
            } else {
                echo '
                                        <button class="heart reward" title="Reward" value="' . $answer_info["a_id"] . '">
                                            <i class="fa fa-heart" style="font-size: 22px; color:#b2b2bb"></i>                                        
                                        </button>
                                    ';
            }
            echo '
                    <a href="#" class="coins_count">' . $answer_info["c_sum"] . '</a>
                    <a class="interacttions more" title="more">...</a>
                        <div id="qDropdown" class="dropdown-content-a">
                            <a id="delete_a_a_btn" value="'.$answer_info['a_id'].'">
                                <i class="fa fa-trash"></i>
                                Delete
                            </a>
                        </div>
                        <a href="ask_friends.php?q_content=' . $answer_info["q_content"] . '" class="interacttions">
                            <i class="fa fa-recycle" title="forward" style="font-size: 22px; color:#b2b2bb"></i>
                        </a>
                        <a href="#" class="interacttions">
                            <i class="fa fa-upload" title="share" style="font-size: 22px; color:#b2b2bb"></i>
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
    include('includes/temps/footer.php');
?>