<?php

if (! isset($con)) {
    session_start();
    include('../../connection.php');
    include('functions.php');
}

if (isset($_POST['pa_1']) and isset($_POST['pa_2'])) {    // loading from home
    # code...
    $v_table_name = $_POST['pa_1'];
    $v_rec_info = $_POST['pa_2'];
    $v_rec_info_arr = preg_split("/,/" , trim($v_rec_info));
    $v_owner = $v_rec_info_arr[0];
    $v_id = $v_rec_info_arr[1];
    $choice = $v_rec_info_arr[2];

    $select = "SELECT * FROM $v_table_name where v_id = '$v_id'";
    $select_q = mysqli_query($con, $select);
    $v_info = mysqli_fetch_array($select_q);

    $select_u = "SELECT * FROM users where user_id = '$v_owner'";
    $select_u_q = mysqli_query($con, $select_u);
    $u_info = mysqli_fetch_array($select_u_q);

    if ($v_info == null or $u_info == null) {
        header('location: unReachable.php');
        exit();
    }

    $total_l = ($v_info["v_l1_sum"] + $v_info["v_l2_sum"]);
    $first_prec = round(($v_info["v_l1_sum"] / $total_l) * 100);
    $second_prec = round(($v_info["v_l2_sum"] / $total_l) * 100);
    $first_height = (($first_prec * 3.6) + 20);
    $second_height = (($second_prec * 3.6) + 20);
    $first_top_margin = (360 - $first_height) + 20;
    $second_top_margin = (360 - $second_height) + 20;

    echo'
        <h1>'. $v_info['v_head'] .'</h1>
        <div class="friend_section big" style="border: none;">
            <div class="friend_pics">
                <a href="profile.php?user_name_=' . $u_info["user_name"] . '">
                    <img src="' . $u_info["user_pic"] . '">
                </a>
            </div>
            <div >
                <a href="profile.php?user_name_=' . $u_info["user_name"] . '">
                    <span class="friend_name">' . $u_info["user_full_name"] . '</span>
                    <div class="time_container">
                        <a>' . printTime($v_info["v_date"]) . '</a>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="versus_pics view">
            <a href="#" id="choice_1" title="click to upload">
                <img src="'. $v_info["v_pic_1"].'" alt="choice 1" id="versus_1">
            </a>';
        if ($choice == 1) {
            # code...
            echo'
                <button class="precentage selected" title="'.$first_prec.'%" style="height:'. $first_height .'px; margin-top: '.$first_top_margin.'px;" value="'. $first_prec.'">
                    <i class="fa fa-heart"  style="color:#e14;">
                        <span style="color:#b2b2bb;"> '.$first_prec.'% </span>
                    </i>
                </button>
            ';
        }
        else
        {
            echo'
                <button class="precentage" title="'.$first_prec.'%" style="height:'. $first_height .'px; margin-top: '.$first_top_margin.'px;" value="'. $first_prec.'">
                    <i class="fa fa-heart">
                        <span> '.$first_prec.'% </span>
                    </i>
                </button>
            ';
        }
        echo'
            <span class="vs">VS</span>
            <a href="#" id="choice_2" title="click to upload">
                <img src="'. $v_info["v_pic_2"].'" alt="choice 2"  id="versus_2">
            </a>
        ';
        if ($choice == 2) {
            echo'
                <button class="precentage right selected" title="'.$second_prec.'%" style="height:'. $second_height .'px; margin-top: '.$second_top_margin.'px;" value="'. $first_prec.'">
                    <i class="fa fa-heart" style="color:#e14;">
                        <span style="color:#b2b2bb;"> '.$second_prec.'% </span>
                    </i>
                </button>
            ';
        }
        else
        {
            echo'
                <button class="precentage right" title="'.$second_prec.'%" style="height:'. $second_height .'px; margin-top: '.$second_top_margin.'px;" value="'. $first_prec.'">
                    <i class="fa fa-heart">
                        <span> '.$second_prec.'% </span>
                    </i>
                </button>
            ';
        }

    
    echo '
        </div>
        <div class="versus_details">
            <a class="versus_users_count" value="'.$v_owner.','.$v_info['v_id'].'">'. ($v_info["v_l1_sum"] + $v_info["v_l2_sum"]).' votes</a>
            <div class="right">
                <a class="fa fa-upload share_mark" href="#"></a>
                <a class="more" title="more">...</a>
                <div id="qDropdown" class="dropdown-content-a">
                    <a id="report_q_btn" value="'.$v_info['v_id'].'">
                        <i class="fa fa-flag"></i>
                        Report post
                    </a>
                </div>
            </div>
        </div>
    ';

}
else    // loading from view_versus
{
    $react = false;
    $choice = 0;
    if ($v_info["v_l1_sum"] >= 1) {
        $r_users_l = preg_split("/,/",$v_info["v_l1_users"]);
        foreach($r_users_l as $user)
        {
            if ($user == $user_id) {
                $react = true;
                $choice = 1;
                break;
            }
        }
    }
    if ($react == false and $v_info["v_l2_sum"] >= 1) {
        $r_users_l = preg_split("/,/",$v_info["v_l2_users"]);
        foreach($r_users_l as $user)
        {
            if ($user == $user_id) {
                $react = true;
                $choice = 2;
                break;
            }
        }
    }
    // r_u_id the owner
    if ($user_id == $r_u_id) {
        $react = true;
    }
    if ($react == true) { // if user already reacted before or is the owner
        $total_l = ($v_info["v_l1_sum"] + $v_info["v_l2_sum"]);
        $first_prec = round(($v_info["v_l1_sum"] / $total_l) * 100);
        $second_prec = round(($v_info["v_l2_sum"] / $total_l) * 100);
        $first_height = (($first_prec * 3.6) + 20);
        $second_height = (($second_prec * 3.6) + 20);
        $first_top_margin = (360 - $first_height) + 20;
        $second_top_margin = (360 - $second_height) + 20;

        echo'
            <div class="versus_pics view">
                <a href="#" id="choice_1" title="click to upload">
                    <img src="'. $v_info["v_pic_1"].'" alt="choice 1" id="versus_1">
                </a>';
            if ($choice == 1) {
                # code...
                echo'
                    <button class="precentage selected" title="'.$first_prec.'%" style="height:'. $first_height .'px; margin-top: '.$first_top_margin.'px;" value="'. $first_prec.'">
                        <i class="fa fa-heart"  style="color:#e14;">
                            <span style="color:#b2b2bb;"> '.$first_prec.'% </span>
                        </i>
                    </button>
                ';
            }
            else
            {
                echo'
                    <button class="precentage" title="'.$first_prec.'%" style="height:'. $first_height .'px; margin-top: '.$first_top_margin.'px;" value="'. $first_prec.'">
                        <i class="fa fa-heart">
                            <span> '.$first_prec.'% </span>
                        </i>
                    </button>
                ';
            }
            echo'
                <span class="vs">VS</span>
                <a href="#" id="choice_2" title="click to upload">
                    <img src="'. $v_info["v_pic_2"].'" alt="choice 2"  id="versus_2">
                </a>
            ';
            if ($choice == 2) {
                echo'
                    <button class="precentage right selected" title="'.$second_prec.'%" style="height:'. $second_height .'px; margin-top: '.$second_top_margin.'px;" value="'. $first_prec.'">
                        <i class="fa fa-heart" style="color:#e14;">
                            <span style="color:#b2b2bb;"> '.$second_prec.'% </span>
                        </i>
                    </button>
                ';
            }
            else
            {
                echo'
                    <button class="precentage right" title="'.$second_prec.'%" style="height:'. $second_height .'px; margin-top: '.$second_top_margin.'px;" value="'. $first_prec.'">
                        <i class="fa fa-heart">
                            <span> '.$second_prec.'% </span>
                        </i>
                    </button>
                ';
            }

    }
    else{   // if user did not react
        echo'
                <div class="versus_pics view">
                <a href="#" id="choice_1" title="click to upload">
                    <img src="'. $v_info["v_pic_1"].'" alt="choice 1" id="versus_1">
                </a>
                <button class="heart left" title="Like" value="'. $r_u_id . ',' . ($v_info["v_id"] . ',1').'">
                    <i class="fa fa-heart"></i>
                </button>
                <span class="vs">VS</span>
                <a href="#" id="choice_2" title="click to upload">
                    <img src="'. $v_info["v_pic_2"].'" alt="choice 2"  id="versus_2">
                </a>
                <button class="heart right" title="Like" value="'. $r_u_id . ',' . ($v_info["v_id"] . ',2').'">
                    <i class="fa fa-heart"></i>
                </button>
        ';
    }
    echo '
        </div>
        <div class="versus_details">
            <a href="#scroll_2" class="versus_users_count" value="'.$r_u_id.','.$v_info['v_id'].'">'. ($v_info["v_l1_sum"] + $v_info["v_l2_sum"]).' votes</a>
            <div class="right">
                <a class="fa fa-upload share_mark" href="#"></a>
                <a class="more" title="more">...</a>
                <div id="qDropdown" class="dropdown-content-a">
                    <a id="report_q_btn" value="'.$v_info['v_id'].'">
                        <i class="fa fa-flag"></i>
                        Report post
                    </a>
                </div>
            </div>
        </div>
    ';
}
