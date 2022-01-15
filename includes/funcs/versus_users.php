<div class="versus_user">

    <div class="main_container_menu big" id="scroll_1">
        <nav class="dark">
            <a href="#scroll_1" id="defaultOpen" class="tablinks" onclick="openCity(event, 'Left')">
                Left Option
            </a>
            <a href="#scroll_1" class="tablinks" onclick="openCity(event, 'Right')">
                Right Option
            </a>
        </nav>
    </div>

        <div id="Left" class="tabcontent">
            <?php
            if ($v_info["v_l1_sum"] > 0) {

                $r_users_l = preg_split("/,/",$v_info["v_l1_users"]);

                foreach($r_users_l as $user)
                {
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
            }

            ?>
        </div>

        <div id="Right" class="tabcontent">
            <?php
                if ($v_info["v_l2_sum"] > 0) {

                    $r_users_l = preg_split("/,/",$v_info["v_l2_users"]);

                    foreach($r_users_l as $user)
                    {
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
                                <div>
                                    <a href="profile.php?user_name_='.$r_user_info["user_name"].'" class="friend_info">
                                        <span class="friend_name">'.$r_user_info["user_full_name"].'</span>
                                        <span class="friend_user_name">@'.$r_user_info["user_name"].'</span>
                                    </a>
                                </div>
                            </div>';
                        }
                    }
                }
            ?>
        </div>
</div>