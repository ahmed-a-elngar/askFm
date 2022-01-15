<?php

    session_start();
    include('../../connection.php');
    include('functions.php');

    if(isset($_POST['pa_1']))
    {
        $search_key = strtolower(trim($_POST['pa_1']));

        if(strlen(trim($search_key)) > 0)
        {
            // print friends
            $f_table_name = $_SESSION['user_id'] . '_friends';
            $select = " SELECT * FROM users WHERE user_id IN (SELECT f_id from $f_table_name)";   // WHERE user_full_name LIKE '$search_key'
            $query = mysqli_query($con, $select);

            echo'
                <h1 class="side_heading dark">Search Reasult</h1>
            ';

            while($frnd_info = mysqli_fetch_array($query))
            {
                // for each user split interests
                if(strlen(trim($frnd_info['user_interests'])) > 0)  // if user have interests
                {
                    $interests = preg_split("/,/", trim($frnd_info['user_interests']));
                    foreach($interests as $interest)
                    {
                        if (strlen(trim($interest)) > 0) {
                            if (stripos(strtolower(trim($interest)), $search_key) == 1) { // if matches

                                $frnd_id = $frnd_info['user_id'];
                                $f_table_name = $_POST['pa_2'] . "_friends";
                                $user_query = "SELECT * from users where user_id = '$frnd_id'";
                                # retrive friend info.
                                $run_u_query = mysqli_query($con, $user_query);
                                $frnd_info = mysqli_fetch_array($run_u_query);

                                echo
                                '<div class="friend_section big" style="border: none;">
                                    <div class="friend_pics">
                                        <a href="profile.php?user_name_='.$frnd_info["user_name"].'">
                                            <img src="'.$frnd_info["user_pic"].'">
                                        </a>
                                    </div>
                                    <div >
                                        <a href="profile.php?user_name_='.$frnd_info["user_name"].'">
                                            <span class="friend_name">'.$frnd_info["user_full_name"].'</span>
                                            <span class="friend_user_name">@'.$frnd_info["user_name"].'</span>
                                        </a>
                                    </div>
                                ';
        

                                echo'
                                    <div class="ask_friend_btn">
                                        <a href="ask_friend.php?user_id_='.$frnd_info["user_id"].'#">Ask ></a>
                                    </div>
                                    <button class="non_btn" onclick="MakeFavOrNot('.$frnd_info["user_id"].')" value="'.$frnd_id.'">
                                ';
                                if (isFav($f_table_name, $frnd_id)) {
                                    echo '<i class="fa fa-star star_mark active"></i>';
                                }
                                else {
                                    echo '<i class="fa fa-star star_mark"></i>';
                                }
                                echo '</button>
                                    </div>';

                            }
                        }

                    }
                }
            }

            // print non friends
            $select = " SELECT * FROM users WHERE user_id NOT IN (SELECT f_id from $f_table_name)";   // WHERE user_full_name LIKE '$search_key'
            $query = mysqli_query($con, $select);

            while($frnd_info = mysqli_fetch_array($query))
            {
                // for each user split interests
                if(strlen(trim($frnd_info['user_interests'])) > 0)  // if user have interests
                {
                    $interests = preg_split("/,/", trim($frnd_info['user_interests']));
                    foreach($interests as $interest)
                    {
                        if (strlen(trim($interest)) > 0) {
                            if (stripos(strtolower(trim($interest)), $search_key) == 1) { // if matches

                                $frnd_id = $frnd_info['user_id'];
                                $f_table_name = $_POST['pa_2'] . "_friends";
                                $user_query = "SELECT * from users where user_id = '$frnd_id'";
                                # retrive friend info.
                                $run_u_query = mysqli_query($con, $user_query);
                                $frnd_info = mysqli_fetch_array($run_u_query);

                                echo
                                '<div class="friend_section big" style="border: none;">
                                    <div class="friend_pics">
                                        <a href="profile.php?user_name_='.$frnd_info["user_name"].'">
                                            <img src="'.$frnd_info["user_pic"].'">
                                        </a>
                                    </div>
                                    <div >
                                        <a href="profile.php?user_name_='.$frnd_info["user_name"].'">
                                            <span class="friend_name">'.$frnd_info["user_full_name"].'</span>
                                            <span class="friend_user_name">@'.$frnd_info["user_name"].'</span>
                                        </a>
                                    </div>
                                ';
        
                                echo'
                                    <div class="ask_friend_btn" value="'.$frnd_info["user_id"].'" onclick="follow('.$frnd_info["user_id"].')">
                                        <a>Follow</a>
                                    </div>
                                    </div>
                                ';
                            }
                        }

                    }
                }
            }
        }
        /*
        else    // print friends if no input
        {
            echo'
                <h1 class="side_heading dark">Your friends</h1>
            ';
            $f_table_name = $_POST['pa_2'] . "_friends";
            $frnds_query = "SELECT * from $f_table_name ORDER BY fav_or_not DESC,  f_id DESC";
            $run_query = mysqli_query($con, $frnds_query);

            while($frnd = mysqli_fetch_array($run_query)){

                $frnd_id = $frnd['f_id'];
                $user_query = "SELECT * from users where user_id = '$frnd_id'";
                # retrive friend info.
                $run_u_query = mysqli_query($con, $user_query);
                $frnd_info = mysqli_fetch_array($run_u_query);

                #<!--Your Friends section-->
                echo
                '<div class="friend_section big" style="border: none;">
                    <div class="friend_pics">
                        <a href="profile.php?user_name_='.$frnd_info["user_name"].'">
                            <img src="'.$frnd_info["user_pic"].'">
                        </a>
                    </div>
                    <div >
                        <a href="profile.php?user_name_='.$frnd_info["user_name"].'">
                            <span class="friend_name">'.$frnd_info["user_full_name"].'</span>
                            <span class="friend_user_name">@'.$frnd_info["user_name"].'</span>
                        </a>
                </div>';

                echo'
                    <div class="ask_friend_btn">
                        <a href="ask_friend.php?user_id_='.$frnd_info["user_id"].'#">Ask ></a>
                    </div>
                    <button class="non_btn" onclick="MakeFavOrNot('.$frnd_info["user_id"].')" value="'.$frnd_id.'">';
                    if (isFav($f_table_name, $frnd_id)) {
                        echo '<i class="fa fa-star star_mark active"></i>';
                    }
                    else {
                        echo '<i class="fa fa-star star_mark"></i>';
                    }
                echo '</button>
                    </div>';

            }
        }
*/
    }