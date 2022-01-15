<?php
session_start();

$pageTitle = "profile";
include('init.php');

if (isset($_GET["user_name_"])) {
    $_SESSION["another_profile"] = $_GET["user_name_"];
}

#if user is loged in
loged('profile');

if (isset($_GET["user_name_"])) {
    $_SESSION["guest_name"] = $_GET["user_name_"];
}
if (! isset($_POST["submit"]) & ! isset($_GET["user_name_"])) {
    $_SESSION["guest_name"] = "";
}
if ($_SESSION["guest_name"] != "") {
    $user_name_select = $_SESSION["guest_name"];
}
else if($_SESSION["guest_name"] == "")
{
    $user_name_select = $_SESSION["user_name"];
}
$role = "owner";
if($_SESSION["guest_name"] != "")
{
    if($_SESSION["guest_name"] != $_SESSION["user_name"])
    {
        $role = "guest";
    }
}

#retrive user profile details
$select_query = "SELECT * from users where user_name = '$user_name_select'";
$run_query = mysqli_query($con, $select_query);
$user_info = mysqli_fetch_array($run_query);
$a_table_name = $user_info['user_id'] . "_answers";
$f_table_name = $_SESSION['user_id'] . "_friends";
$u_bio = $user_info["user_bio"];
$u_location = $user_info["user_location"];
$u_web = $user_info["user_web"];

if (is_null($user_info)) {
    header('location: unReachable.php');
    exit();
}

// send q
if ($_SERVER['REQUEST_METHOD']=='POST') {

    $q_content = htmlentities(mysqli_real_escape_string($con, $_POST['q_content']));
    $s_status = htmlentities(mysqli_real_escape_string($con, $_POST['s_status']));
    $target_id = $_SESSION['target_id'];

    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
    if (trim($q_content) != "" & trim($target_id) != "") {
        if (sendToTargets($q_content, $target_id, $user_id, $user_name, $s_status)) {
            $q_table_name = $target_id . '_questions';
            $select_q_id  = "SELECT MAX(q_id) from $q_table_name";
            $q_id = mysqli_fetch_array(mysqli_query($con, $select_q_id))[0];
            notifyMe("q", $q_id, $target_id);
            header('location: profile.php');
        }
    }
    if (trim($q_content) == "") {
        echo'
            <script>console.log( " please enter question content ..." );</script>
        '; 
    }
}

?>

<!--background gradient-->
<div class="backgroundWrap withImage ">
    <img src="<?php echo $user_info["user_bg"]; ?>" alt="">
    <div class="gradient"></div>
</div>

<!--main container-->
<div class="main_container">

    <!--header section-->
    <section class="profile_head">
        <a class="profile_pic" href="#" style="background-image: url(<?php echo $user_info["user_pic"]; ?>);"></a>
        <p id="pp" class="profile_user_name">@<?php echo $user_info["user_name"]; ?></p>

            <?php
                if ($role == "guest")
                {
                    echo'
                        <h1 class="profile_name">'.$user_info["user_full_name"];
                    echo '
                        <label class="switch left" style="margin-top: 6px; margin-left:10px;">
                        <input type="checkbox" name="status" id="status">
                    ';
                    if($user_info["user_status"] == 0)
                    {
                        echo'
                                    <span class="status_slider" ></span>
                                    </label>
                                </h1>
                            ';
                    }
                    else
                    {
                        echo'
                                    <span class="status_slider active" ></span>
                                    </label>
                                </h1>
                            ';
                    }
                    // follow or un follow
                    $moreStyle = "";
                    echo '<div class="profile_head_details">';
                    if(! isFriend($f_table_name, $user_info['user_id']))    // not friend
                    {
                        echo '
                            <a class="follow_link" value="'. $user_info['user_id'].'">
                                <i class="fa fa-plus" style="font-size:11px; padding-right:4px;"></i>
                                Folllow
                            </a>
                        ';
                    }
                    else{
                        echo '
                            <a class="follow_link" value="'. $user_info['user_id'].'">
                                Folllowed
                            </a>
                        ';
                        // add to favorite or remove 
                        if (isFav($f_table_name, $user_info['user_id'])) {
                            echo '
                                <button class="non_btn" value="'. $user_info['user_id'] .'">
                                    <i class="fa fa-star star_mark active" title="un fevorite"></i>
                            ';
                        }
                        else {
                            echo '
                                <button class="non_btn" value="'. $user_info['user_id'] .'">
                                    <i class="fa fa-star star_mark" title="favorite"></i>
                            ';

                        }
                        echo '</button>';
                        $moreStyle = "style = 'right: 540px;'";
                    }

                    echo '
                        <a title="more" class="more">...</a>
                        <div id="qDropdown" class="dropdown-content-a" '.$moreStyle.'>
                            <a id="report_q_btn" value="'.$user_info['user_id'].'">
                                <i class="fa fa-flag"></i>
                                Report
                            </a>
                            <a id="block_u_btn" value="'.$user_info['user_id'].'">
                                <i class="fa fa-flag"></i>
                                Block
                            </a>
                        </div>
                    ';
                }
                // if user page
                else
                {
                    
                    echo '
                        <h1 class="profile_name">'. $user_info["user_full_name"] .'</h1>
                        <div class="profile_head_details">
                    ';

                    echo '
                        <a href="leaderboard.php" class="wallet_link">
                            <div>
                                <img src="pics/icons/coin.png">
                                <span>'. number_format($user_info["user_c_count"],0,'.', ' ').'</span>
                            </div>
                            <span class="vertiacl_bar">|</span>
                        </a>
                        <label class="switch" style="margin-top: 6px; margin-left:10px;">
                            <input type="checkbox" name="status" id="status">
                        ';
                        if($user_info["user_status"] == 0)
                        {
                            echo'
                                <span class="slider round visible_switch" onclick="visible_or_not()"></span>
                            </label>
                            <span id="visible_or_not">Offline</span>
                            ';
                        }
                        else
                        {
                            echo'
                                <span class="slider round visible_switch" onclick="visible_or_not()"></span>
                                <script>document.getElementById("status").checked = "true";</script>
                            </label>
                            <span id="visible_or_not">Online</span>
                            ';
                            }
                        echo '
                                <span class="vertiacl_bar">|</span>
                                <a class="fa fa-share-square share_mark" href="#"></a>
                                <a title="more" class="more">...</a>
                                <div id="qDropdown" class="dropdown-content-a" style="right:430px;">
                                    <a id="report_q_btn" value="'.$user_info['user_id'].'">
                                        <i class="fa fa-flag"></i>
                                        Change picture
                                    </a>
                                    <a id="report_q_btn" value="'.$user_info['user_id'].'">
                                        <i class="fa fa-flag"></i>
                                        Change background
                                    </a>
                                </div>
                            </div>
                        ';
                }
            ?>

    </section>

    <!--main section-->
    <div class="main_section after_profile">

        <!--ask box-->
        <div class="ask_box">
            <h2 class="side_heading">Ask yourself</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="question_form">
                <textarea name="q_content" placeholder="What, when, why… ask" class="question_txtArea"></textarea>
                <div class="anonymously_box">
                    <label class="switch">
                        <input type="checkbox" checked="true">
                        <span class="slider round"></span>
                    </label>
                    <span class="anonymously_switch">Ask anonymously</span>
                    <input type="text" name="s_status" id="s_status" value="private" style="visibility: hidden;">
                </div>
                <div style="float: right;">
                    <button class="ask_btn">
                        >
                    </button>
                </div>
            </form>
        </div>

        <!--nav header-->
        <div class="main_container_menu">
            <nav>
                <a id="defaultOpen" class="tablinks" onclick="openCity(event, 'Latest')">
                    Latest
                </a>
                <a class="tablinks" onclick="openCity(event, 'Top')">
                    Top
                </a>
            </nav>
        </div>

        <!--Latest tab-->
        <div id="Latest" class="tabcontent">
            <?php
            $_SESSION['target_id'] = $user_info['user_id'];

            $select_answers = "SELECT * from $a_table_name ORDER BY a_date DESC";
            $select_answers_query = mysqli_query($con, $select_answers);
            while ($answer_info = mysqli_fetch_array($select_answers_query)) {

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
                                    <a href="answer_details.php?user_id_=' . $user_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="likes_count">' . $answer_info["l_sum"] . '</a>';
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
                            <a href="answer_details.php?user_id_=' . $user_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="coins_count">' . $answer_info["c_sum"] . '</a>
                            <a class="interacttions more" title="more">...</a>
                            <div id="qDropdown" class="dropdown-content-a">
                                <a id="delete_a_btn" value="'.$answer_info['a_id'].'">
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
            }
            ?>
        </div>

        <!--Top tab-->
        <div id="Top" class="tabcontent">
            <?php
            $select_answers = "SELECT * from $a_table_name ORDER BY l_sum DESC, a_date DESC";
            $select_answers_query = mysqli_query($con, $select_answers);
            while ($answer_info = mysqli_fetch_array($select_answers_query)) {

                #sender info
                $sender_user_name = $answer_info["q_sender"];
                $select_sender = "SELECT * from users where user_name = '$sender_user_name'";
                $select_sender_query = mysqli_query($con, $select_sender);
                $sender_info = mysqli_fetch_array($select_sender_query);
                $a_date = $answer_info['a_date'];
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
                                    <a href="answer_details.php?user_id_=' . $user_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="likes_count">' . $answer_info["l_sum"] . '</a>';
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
                            <a href="answer_details.php?user_id_=' . $user_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="coins_count">' . $answer_info["c_sum"] . '</a>
                            <a class="interacttions more" title="more">...</a>
                            <div id="qDropdown" class="dropdown-content-a">
                                <a id="delete_a_btn" value="'.$answer_info['a_id'].'">
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
            }
            ?>
        </div>

    </div>

    <!--side section-->
    <div class="side_section after_profile">

        <!--statistics details & about me-->
        <ul class="profile_statistics">
            <?php
                $posts_s = "SELECT COUNT(a_id) AS pc FROM $a_table_name";
                $posts_q = mysqli_query($con, $posts_s);
                $posts_c = mysqli_fetch_array($posts_q);

                $display_2 = $role == "guest" ? "no_followers" : "";
                echo '
                    <li>
                        <div class="statistics_mark posts_statistics ' . $display_2 .'">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div class="statistics_value" id="user_stics_posts">
                            '. number_format($posts_c["pc"],0,'.', ' ')  .'
                        </div>
                        <div class="statistics_name ">
                            posts
                        </div>
                    </li>
                ';

                $user_l_c =  number_format($user_info["user_l_count"],0,'.', ' ');
                $border_type = $role == "guest" ? "style = border:none;" : " ";
                echo '
                    <li '. $border_type .'>
                        <div class="statistics_mark ' . $display_2 .'">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div class="statistics_value" id="user_stics_likes">
                           '. $user_l_c .'
                        </div>
                        <div class="statistics_name">
                            Likes
                        </div>
                    </li>
                ';

                if ( $role != "guest")
                {
                    echo '
                        <li style="border: none;">
                            <div class="statistics_mark followers_statistics">
                                <i class="fa fa-heart"></i>
                            </div>
                            <div class="statistics_value">
                                '. number_format($user_info["user_f_count"],0,'.', ' ') .'
                            </div>
                            <div class="statistics_name ">
                                Followers
                            </div>
                        </li>
                    ';
                }
            ?>
        
        </ul>

        <div class="about_block">
                <?php
                    $about_type = $role == "guest" ? $user_info["user_full_name"] : "Me";
                    echo '
                                <h2>About ' . $about_type . ' :';
                    if ($role != "guest")
                    {
                        echo '
                            <a href="settings.php">
                                <i class="fa fa-edit"></i>
                                Edit profile
                            </a>
                        ';
                    }
                ?>
            </h2>
            <div class="about_info">
                <?php
                    $empty_about = true;
                    if (trim($user_info["user_bio"]) != "")
                    {
                        echo '
                            <div>
                                <i class="fas fa-user"></i>';
                         print $user_info["user_bio"];
                         echo '
                            </div>
                        ';
                        $empty_about = false;
                    }
                    if (trim($user_info["user_location"]) != "")
                    {
                        echo '
                                <div>
                                   <i class="fas fa-map-marker-alt"></i>';
                        print $user_info["user_location"];
                        echo '
                                </div>
                            ';
                        $empty_about = false;
                    }
                    if (trim($user_info["user_web"]) != "")
                    {
                        echo '
                                    <div>
                                        <i class="fa fa-link"></i>
                                        <a href="'. $user_info["user_web"] . '">';
                        print $user_info["user_web"];
                        echo '                   
                                        </a>
                                    </div>
                                ';
                        $empty_about = false;
                    }
                    if (trim($user_info["user_interests"]) != "")
                    {
                        echo '
                            <div>
                                <i class="fas fa-hashtag"></i>
                        ';

                        if(strlen($user_info["user_interests"]) > 1)
                        {
                            $interests = preg_split("/,/", $user_info["user_interests"]);
                            foreach($interests as $interest){
                                echo'
                                <a href="#" style="color:#e14;">
                                    '.$interest.'
                                </a>
                            ';
                            }
                        }
                        else
                        {
                            echo'
                                <a href="settings" class="interests">
                                    Add Interests
                                </a>
                            ';
                        }
                        echo '
                            </div>
                        ';
                        $empty_about = false;
                    }
                    if($empty_about)
                    {
                        echo'
                            <p>Nothing to show here at this time</p>
                        ';
                    }
                ?>

            </div>
            <?php
                $gallery_type = $about_type == "Me" ? "" : ($about_type . ' ');

                echo '
                            <h2 class="gallery_heading"> '. $gallery_type . 'Photo gallery
                ';
            ?>
                <?php
                    if ($role != "guest")
                    {
                        echo '
                            <a href="#">
                                <i class="fa fa-edit"></i>
                                Change photo
                            </a>
                        ';
                    }
                ?>
            </h2>
            <p>Nothing to show here at this time</p>
        </div>

        <div class="sticky">
            <?php include 'includes/temps/solid_side_section.php'; ?>
        </div>

    </div>

</div>

<!--settings navbar js-->
<script src="js/jquery-3.3.1.min.js"></script>
<script>
    a_id = -1;
    f_id = -1;
    change = 1;
    old_val = -1;
    f_change = 1;

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
    // like or dislike answer
    $(function() {
        $("[title='Like']").click(function() {
            r_u_id = <?php echo $_SESSION["user_id"]; ?>;
            u_id = <?php echo $user_info["user_id"]; ?>;
            table_name = "<?php echo $a_table_name; ?>";
            a_id = $(this).val();
            l_count =  $("[title='Like'][value=" + a_id + "] + a").html();
            old_val = l_count;
            if (r_u_id != u_id) {
                $("[title='Like'][value=" + a_id + "] + a").load("includes/funcs/likeOrDislike.php", {
                    pa_1: r_u_id,
                    pa_2: u_id,
                    pa_3: table_name,
                    pa_4: a_id,
                    pa_5: l_count
                });
            }
        });
    });
    // change like button color
    $(function (){
        $("[title='Like']+ a").on("DOMSubtreeModified" ,function(){
            new_val = $("[title='Like'][value=" + a_id + "] + a").html();
            if(change  == 4 )
            {
                if(new_val != old_val)
                {
                    color = $("i", "[title='Like'][value=" + a_id + "]").css("color") == "rgb(238, 17, 68)" ? "#b2b2bb" : "#ee1144";
                    $("i", "[title='Like'][value=" + a_id + "]").css("color", color);
                }
                change = 1;
            }
            else
                change += 1;
        });
    });
    // rewards
    $(function() {
        $("[title='Reward']").click(function() {
            r_u_id = <?php echo $_SESSION["user_id"]; ?>;
            u_id = <?php echo $user_info["user_id"]; ?>;
            table_name = "<?php echo $a_table_name; ?>";
            a_id = $(this).val();
            c_count =  $("[title='Reward'][value=" + a_id + "] + a").html();
            old_val = c_count;

            if (r_u_id != u_id) {
                $("[title='Reward'][value=" + a_id + "] + a").load("includes/funcs/rewards.php", {
                    pa_1: r_u_id,
                    pa_2: u_id,
                    pa_3: table_name,
                    pa_4: a_id,
                    pa_5: c_count
                });
            }
        });
    });
    // change reward button color
    $(function (){
        $("[title='Reward']+ a").on("DOMSubtreeModified" ,function(){
            new_val = $("[title='Reward'][value=" + a_id + "] + a").html();
            if(change  == 4 )
            {
                if(new_val != old_val)
                {
                    $("i", "[title='Reward'][value=" + a_id + "]").css("color", "#e14");
                }
                change = 1;
            }
            else
                change += 1;
        });
    });
    // visible or not
    function visible_or_not() {
        if (document.getElementById("visible_or_not").innerHTML == "Offline") {
            document.getElementById("visible_or_not").innerHTML = "Online";
        } else if (document.getElementById("visible_or_not").innerHTML == "Online") {
            document.getElementById("visible_or_not").innerHTML = "Offline"
        }
    }
    // answers more dropDown list
    $(function() {
        $(".more").click(function() {
            dropdown = $(this).next("#myDropdown1");
            if (dropdown.css('display') == 'none') {
                //dropdown.css('display','block');
                dropdown.addClass('show');
            } else
                dropdown.removeClass('show');
        });
    });
    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.more')) {
            var dropdowns = document.getElementsByClassName("dropdown-content1");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
    // store user status
    $(function(){
        $('#status').click(function(){
            id = <?php echo $user_info["user_id"]; ?>;
            status = <?php echo $user_info["user_status"]; ?>;
            role = "<?php echo $role; ?>";
            $("#status").load("includes/funcs/update_status.php",
            {
                pa_1: id,
                pa_2: status,
                pa_3: role
            });
        });
    });
    // folllow or unfollow
    $(function(){
        $(".follow_link").click(function() {
            f_id = $(this).attr('value');
            table_name = "<?php echo $f_table_name; ?>";
            $(this).load("includes/funcs/followORNot.php", {
                pa_1: f_id,
                pa_2: table_name,
            });
        });
    });
    // display favorite button & followed or hide them & display follow button
    $(function (){
        $(".follow_link").on("DOMSubtreeModified" ,function(){
            if(f_change == 2)
            {
                $current_state = $(this).text().trim();
                if($current_state == "Followed")
                {
                    $(".follow_link").after(" <button class='non_btn' onclick='MakeFavOrNot()' value='" + f_id +"'> <i class='fa fa-star star_mark' title='favorite'></i> </button>");
                }
                else
                {
                    $('.follow_link').next('.non_btn').remove();
                }
                f_change = 1;
            }
            else{
                f_change += 1;
            }
        });
    });
    // add to favorite
    $(function(){
        $(".non_btn").click(function(){
            f_id = $(this).val();
            table_name = "<?php echo $f_table_name; ?>";
            color = $(".non_btn[value=" + f_id + "] i.star_mark").css("color") == "rgb(213, 213, 221)" ? "#ffff00" : "#d5d5dd";
            x = $(this).load('includes/funcs/favOrNot.php',{
                pa_1: f_id,
                pa_2: table_name,
                pa_3: color
            });
            color = $(".non_btn[value=" + f_id + "] i.star_mark").css("color") == "rgb(213, 213, 221)" ? "#ffff00" : "#d5d5dd";
            //$(".non_btn[value=" + f_id + "] i.star_mark").css("color", color);
        });
    });
    function MakeFavOrNot()
    {        
        elm = document.getElementsByClassName('non_btn');
        table_name = "<?php echo $f_table_name; ?>";
        color = $(".non_btn[value=" + f_id + "] i.star_mark").css("color") == "rgb(213, 213, 221)" ? "#ffff00" : "#d5d5dd";
        x = $(elm).load('includes/funcs/favOrNot.php',{
            pa_1: f_id,
            pa_2: table_name,
            pa_3: color
        });
        color = $(".non_btn[value=" + f_id + "] i.star_mark").css("color") == "rgb(213, 213, 221)" ? "#ffff00" : "#d5d5dd";
    }
</script>
<script src="js/functions.js"></script>
</body>
</html>