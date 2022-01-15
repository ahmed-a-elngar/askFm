<?php

    include 'connection.php';
    $user_id = $_SESSION['user_id'];
    $f_table_name = $user_id . '_friends';

    $select_f_stmt = "SELECT $f_table_name.f_id, users.* FROM $f_table_name INNER JOIN users 
                                                            ON $f_table_name.f_id = users.user_id";
    $select_f_q = mysqli_query($con, $select_f_stmt);

    if (mysqli_num_rows($select_f_q) < 4) {
        if (mysqli_num_rows($select_f_q) > 0) {
            echo'
                <h1 class="side_heading">Your Friends</h1>
            ';
            while ($select_f_res = mysqli_fetch_array($select_f_q)) {

                echo'
                    <div class="friend_section" style="border: none;">
                        <div class="friend_pics">
                        <a href="profile.php?user_name_=' . $select_f_res["user_name"] . '">
                            <img src="' . $select_f_res["user_pic"] . '">
                        </a>
                        </div>
                        <div >
                        <a href="profile.php?user_name_=' . $select_f_res["user_name"] . '">
                            <span class="friend_name">' . $select_f_res["user_full_name"] . '</span>
                            <span class="friend_user_name">@' . $select_f_res["user_name"] . '</span>
                        </a>
                        </div>
                        <div class="ask_friend_btn">
                            <a href="ask_friend.php?user_id_=' . $select_f_res["user_id"] . '#">Ask ></a>
                        </div>
                    </div>
                ';
            }
            echo'
                <!--see all friends "link to friends page"-->
                <p class="see_all">
                    <a href="friends.php">See all friends</a>
                </p>
            ';
        }

    }
    else
    {
        $select_mm_stmt = "SELECT MIN(f_id) AS minimum, MAX(f_id) AS maximum FROM $f_table_name";
        $select_mm_val = mysqli_fetch_array(mysqli_query($con, $select_mm_stmt));
        $f_count = 0;
        $id_arr = array();

        while ($f_count < 3) {
    
            $id = rand($select_mm_val['minimum'], $select_mm_val['maximum']);
            if (idExists($id)) {
                if ($id != $user_id) {
                    $choosen = false;
                    foreach($id_arr as $id_)
                    {   
                        if ($id == $id_) {
                            $choosen = true;
                        }
                    }
                    if ($choosen == false) {
                        array_push($id_arr, $id);
                        $f_count += 1;
                    } 
                }
            }
        }

        $in_condition = '( ' . $id_arr[0] . ',' . $id_arr[1] . ',' . $id_arr[2] . ')' ;
        $select_u_stmt = "SELECT * FROM users WHERE  user_id IN $in_condition";
        $select_u_q = mysqli_query($con, $select_u_stmt);

        echo'
            <h1 class="side_heading">Your Friends</h1>
        ';
        while ($select_u_res = mysqli_fetch_array($select_u_q)) {
            echo'
                <div class="friend_section" style="border: none;">
                    <div class="friend_pics">
                    <a href="profile.php?user_name_=' . $select_u_res["user_name"] . '">
                        <img src="' . $select_u_res["user_pic"] . '">
                    </a>
                    </div>
                    <div >
                    <a href="profile.php?user_name_=' . $select_u_res["user_name"] . '">
                        <span class="friend_name">' . $select_u_res["user_full_name"] . '</span>
                        <span class="friend_user_name">@' . $select_u_res["user_name"] . '</span>
                    </a>
                    </div>
                    <div class="ask_friend_btn">
                        <a href="ask_friend.php?user_id_=' . $select_u_res["user_id"] . '#">Ask ></a>
                    </div>
                </div>
            ';
        }
        echo'
            <!--see all friends "link to friends page"-->
            <p class="see_all">
                <a href="friends.php">See all friends</a>
            </p>
        ';
    }
?>