<?php
    
    include '../../connection.php';

    echo'
        <div class="recommend-cont">
        <div class="recommend-tag">
            <i class="fa fa-trash"></i>
            People you may know
            <a class="close" title="Close" id="close_sug"> x </a>
        </div>
    ';

    // select user friends to select shared friends
    $user_id = $_SESSION['user_id'];
    $id_arr = recommendFriends($user_id);

    // print suggestions
    $select_sug_stmt = "SELECT * FROM users WHERE user_id IN (" . $id_arr[0] . "," . $id_arr[1] . "," . $id_arr[2] . ")";
    $select_sug_q = mysqli_query($con, $select_sug_stmt);
    while ($select_sug_val = mysqli_fetch_array($select_sug_q)) {

        echo '
            <div class="friend_section big" style="border: none;">
                <div class="friend_pics">
                    <a href="profile.php?user_name_=' . $select_sug_val["user_name"] . '">
                        <img src="' . $select_sug_val["user_pic"] . '">
                    </a>
                </div>
                <div>
                    <a href="profile.php?user_name_=' . $select_sug_val["user_name"] . '">
                        <span class="friend_name">' . $select_sug_val["user_full_name"] . '</span>
                        <span class="friend_user_name">@' . $select_sug_val["user_name"] . '</span>
                    </a>
                </div>

                <div class="recommend-actions">
                    <div class="btn sec_btn" id="remove_sug">
                        <a>Remove</a>
                    </div>
                    <div class="btn pri_btn" id="add_sug" value="'.$select_sug_val['user_id'].'">
                        <a>Follow</a>
                    </div>
                </div>
        </div>
        ';
    }
    echo'
        </div>
    ';