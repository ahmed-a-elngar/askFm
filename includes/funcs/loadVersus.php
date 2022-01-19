<?php
    
    session_start();
    include('../../connection.php');
    include 'functions.php';

    // load friends answers
    if (isset($_POST['pa_1'])) {
        
        // select user friends
        $user_id = $_SESSION['user_id'];
        $f_table_name = $user_id . '_friends';
        $u_id_arr = array();

        // print friends versus
        $select_u_stmt =  "SELECT * FROM users WHERE user_id in (SELECT f_id FROM $f_table_name)";
        $select_u_query = mysqli_query($con, $select_u_stmt);
        while($u_info = mysqli_fetch_array($select_u_query))
        {
            array_push($u_id_arr, $u_info['user_id']);
        }

        if (count($u_id_arr) > 0) {     // if there friends
            
            $v_table_name = $u_id_arr[0] . '_versus';
            $select_v_stmt = "SELECT  $v_table_name.* , users.user_id, users.user_name, users.user_full_name, users.user_pic, users.user_mood 
            FROM $v_table_name LEFT JOIN users ON users.user_id = '$u_id_arr[0]' ";

            for($i = 1; $i < count($u_id_arr) ; $i++)
            {
                // get all friends versus
                $v_table_name = $u_id_arr[$i] . '_versus';
                $select_v_stmt .= "UNION SELECT $v_table_name.* , users.user_id, users.user_name, users.user_full_name, users.user_pic, users.user_mood 
                FROM $v_table_name LEFT JOIN users ON users.user_id = '$u_id_arr[$i]' ";

            }

            // select friends versus order by time desc & print them
            $select_v_stmt .= "ORDER BY v_date DESC";
            $select_v_query = mysqli_query($con, $select_v_stmt);
            
            while ($v_info = mysqli_fetch_array($select_v_query)) {
                $r_u_id = $v_info['user_id'];

                echo'
                    <div class="versus_cont">
                        <h1>'. $v_info['v_head'] .'</h1>
                        <div class="friend_section big" style="border: none;">
                            <div class="friend_pics">
                                <a href="profile.php?user_name_='.$v_info["user_name"].'">
                                    <img src="'.$v_info["user_pic"].'">';
                if($v_info['user_mood'] != null and $v_info['user_mood'] != 0)
                {
                    echo'
                        <div class="user_mood">
                            <img src="pics/moods/'.$v_info['user_mood'].'.gif">
                        </div>
                    ';
                } 
                echo'                </a>
                            </div>
                            <div >
                                <a href="profile.php?user_name_='.$v_info["user_name"].'">
                                    <span class="friend_name">'.$v_info["user_full_name"].'</span>
                                    <div class="time_container">
                                        <a>' . printTime($v_info["v_date"]) . '</a>
                                    </div>
                                </a>
                            </div>
                        </div>
                ';
                include 'load_versus.php';
                echo'</div>';
            }
        }

        $u_id_arr = null;
        $u_id_arr = array();
        // print non friends versus
        $select_u_stmt =  "SELECT * FROM users WHERE user_id NOT in (SELECT f_id FROM $f_table_name) AND user_id != '$user_id'";
        $select_u_query = mysqli_query($con, $select_u_stmt);
        while($u_info = mysqli_fetch_array($select_u_query))
        {
            array_push($u_id_arr, $u_info['user_id']);
        }

        if (count($u_id_arr) > 0) {     // if there is who not friends
            
            $v_table_name = $u_id_arr[0] . '_versus';
            $select_v_stmt = "SELECT  $v_table_name.* , users.user_id, users.user_name, users.user_full_name, users.user_pic FROM $v_table_name
                              LEFT JOIN users ON users.user_id = '$u_id_arr[0]' ";

            for($i = 1; $i < count($u_id_arr) ; $i++)
            {
                // get all friends versus
                $v_table_name = $u_id_arr[$i] . '_versus';
                $select_v_stmt .= "UNION SELECT $v_table_name.* , users.user_id, users.user_name, users.user_full_name, users.user_pic FROM $v_table_name
                                   LEFT JOIN users ON users.user_id = '$u_id_arr[$i]' ";

            }

            // select friends versus order by time desc & print them
            $select_v_stmt .= "ORDER BY v_date DESC";
            $select_v_query = mysqli_query($con, $select_v_stmt);
            while ($v_info = mysqli_fetch_array($select_v_query)) {
                
                $r_u_id = $v_info['user_id'];

                echo'
                    <div class="versus_cont">
                        <h1>'. $v_info['v_head'] .'</h1>
                        <div class="friend_section big" style="border: none;">
                            <div class="friend_pics">
                                <a href="profile.php?user_name_='.$v_info["user_name"].'">
                                    <img src="'.$v_info["user_pic"].'">
                                </a>
                            </div>
                            <div >
                                <a href="profile.php?user_name_='.$v_info["user_name"].'">
                                    <span class="friend_name">'.$v_info["user_full_name"].'</span>
                                    <div class="time_container">
                                        <a>' . printTime($v_info["v_date"]) . '</a>
                                    </div>
                                </a>
                            </div>
                        </div>
                ';
                include 'load_versus.php';
                echo'</div>';

            }
        }

    }

    /*

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
                                <div class="answer_receiver">
                                    <div class="friend_pics">
                                        <a href="profile.php?user_name_='.$answer_info["user_name"].'">
                                            <img src="'.$answer_info["user_pic"].'">
                                        </a>
                                    </div>
                                    <div >
                                        <a href="profile.php?user_name_='.$answer_info["user_name"].'">
                                            <span class="friend_name">'.$answer_info["user_full_name"].'</span>
                                        </a>
                                    </div>
                                    <div class="time_container">
                                        <a>' . printTime($a_date) . '</a>
                                    </div>
                                </div>
                                <!--the answer content-->
                                <div class="answer_content" dir="'. detectDir($answer_info["a_content"]).'">
                                ' . $answer_info["a_content"] . '
                                </div>
                                <!--interactions with the answer (like, reward, share, ...)-->
                                <div class="iteractions_section">';
                if (interacted($_SESSION['user_id'], $a_table_name, $answer_info['a_id'], "likes")) {
                    echo '
                                            <button class="heart" title="Like" onclick="likeMe('.$answer_info["user_id"].',' . $answer_info["a_id"] . ')" value="' . $answer_info["a_id"] . '">
                                                <i class="fa fa-heart" style="font-size: 22px; color:#ee1144;"></i>
                                            </button>
                                        ';
                } else {
                    echo '
                                            <button class="heart" title="Like" onclick="likeMe('.$answer_info["user_id"].',' . $answer_info["a_id"] . ')" value="' . $answer_info["a_id"] . '">
                                                <i class="fa fa-heart" style="font-size: 22px; color:#b2b2bb;"></i>
                                            </button>
                                        ';
                }
                echo '
                                    <a href="answer_details.php?user_id_=' . $sender_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="likes_count">' . $answer_info["l_sum"] . '</a>';
                if (interacted($_SESSION['user_id'], $a_table_name, $answer_info['a_id'], "coins")) {
                    echo '
                                            <button class="heart reward" title="Reward" onclick="rewardMe('.$answer_info["user_id"].','.$answer_info["a_id"].')" value="' . $answer_info["a_id"] . '">
                                                <i class="fa fa-heart" style="font-size: 22px; color:#ee1144"></i>                                        
                                            </button>
                                        ';
                } else {
                    echo '
                                            <button class="heart reward" title="Reward" onclick="rewardMe('.$answer_info["user_id"].','.$answer_info["a_id"].')" value="' . $answer_info["a_id"] . '">
                                                <i class="fa fa-heart" style="font-size: 22px; color:#b2b2bb"></i>                                        
                                            </button>
                                        ';
                }
                echo 
                '
                                    <a href="answer_details.php?user_id_=' . $sender_info["user_id"] . '&a_id_=' . $answer_info["a_id"] . '" class="coins_count">' . $answer_info["c_sum"] . '</a>
                                    <button class="interacttions more" title="more">...</button>
                                    <div id="myDropdown1" class="dropdown-content1">
                                        <a href="#">                
                                            <i class="fa fa-trash" style="margin-right: 8px; color: #cdcdd9"></i>
                                            delete
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

    */