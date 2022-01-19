<?php

    session_start();
	
    include 'connection.php';

    $noNav = '';
    $pageTitle = 'ASK fm';
    include('init.php');

    #if user is loged in
    loged('index');


?>

    <div id="intro_main">
        <img id="logo" src="pics/logo-red-9af653502f0b8f01022ea1aa0ab49f00b41db433c00fee35a9848e5a87a0dff9.png">
        <p>
            <small> Curious? </small>just ask!<br>
            Openly or anonymously.
        </p>
        <button id="sign_up" onclick="window.location.href='sign up.php';">Sign up</button>
        <button id="sign_in" onclick="window.location.href='sign in.php';">Log in</button>
        <section>
            <img src="pics/badge_app_store-ea132fe397a81c14b9aac8b5e5233f1f98c407a62505818967c0e3be1e022e6e.png" alt="">
            <img src="pics/badge_google_play-74d21407f74f075a184fdefd5c36e7486b12af8f899d27d1f34d178dc3cb59ec.png" alt="">
            <img src="pics/badge_huawei_appgallery-7bec8ed91828102ac17f842e2c602fef97a73970301283d9b2f1c08f206b5c14.png" alt="">
        </section>
    </div>
    <div id="intro_footer">
        <section>
            <?php // select most 18 
            
                $select = "SELECT COUNT(user_id) As users_count, MIN(user_id) AS minimum, MAX(user_id) AS maximum FROM users";
                $query = mysqli_query($con, $select);
                $select_res = mysqli_fetch_array($query);
                $users_count = $select_res['users_count'];
                $minimum_id = $select_res['minimum'];
                $maximum_id = $select_res['maximum'];

                if ($users_count > 18) {
                    # random

                    $f_count = 0;
                    $id_arr = array();
                    $timer = 1;

                    while ($f_count < 18) {
                        $id = rand($minimum_id, $maximum_id);
                        $timer += 1;
                        if (idExists($id)) {
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
                        if ($timer == 1000) {
                            break;
                        }
                    }
                    
                    $in_condition = '( ' . $id_arr[0];
                    for ($i= 1; $i < count($id_arr); $i++) { 
                        $in_condition .= ' , ';
                        $in_condition .= $id_arr[$i];
                    }
                    $in_condition .= ' )';
                    $select_users = "SELECT user_name, user_full_name, user_pic FROM users WHERE user_id IN $in_condition";
                    $users_query = mysqli_query($con, $select_users);
                    while ($users_info = mysqli_fetch_array($users_query)) {
                        echo'
                            <a href="profile.php?user_name_='.$users_info['user_name'].'">
                                <img src="'.$users_info['user_pic'].'" alt="'.$users_info['user_full_name'].'" title="'.$users_info['user_full_name'].'">
                            </a>
                        ';
                    }
                }
                else {
                    // get all 
                    $select_users = "SELECT user_name, user_full_name, user_pic FROM users";
                    $users_query = mysqli_query($con, $select_users);

                    while ($users_info = mysqli_fetch_array($users_query)) {
                        echo'
                            <a href="profile.php?user_name_='.$users_info['user_name'].'">
                                <img src="'.$users_info['user_pic'].'" alt="'.$users_info['user_full_name'].'" title="'.$users_info['user_full_name'].'">
                            </a>
                        ';
                    }
                }
                
            ?>
        </section>

        <a href="">About ASKfm </a>
        <a href="">Safety center </a>
        <a href="">Help </a>
        <a href="">Community Guidelines </a>
        <a href="">Terms of use </a>
        <a href="">Privacy policy </a>
        <a href="">Cookies policy</a>
        <a href="">Advertising </a>
        <a href="">Professionals </a>

        <hr style="width: 60vw; margin: 42px auto 24px; opacity: .6;">
        <section style="width: 60vw; margin: auto; padding-bottom: 36px; font-size: 12px;">
            <p style="float: left; margin-top: 4px;">Language <span style="color: #000;"> English</span></p>
            <section style="float: right; margin-top: -3px;">
                <img src="pics/download (1).jpg" style="width: 2vw; padding: 0;">
                <img src="pics/download (1).jpg" style="width: 2vw; padding: 0;">
                <img src="pics/download (1).jpg" style="width: 2vw; padding: 0;">
                <img src="pics/download (1).jpg" style="width: 2vw; padding: 0;">
                <img src="pics/download (1).jpg" style="width: 2vw; padding: 0;">
                <p style="margin-top: -24px; margin-left: 18vw;">
                    @ Ask fm 2022
                </p>
            </section>
        </section>
    </div>