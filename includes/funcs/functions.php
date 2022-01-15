<?php

    if (! $con) {
        include('../user/connection.php');
    }

    function loged($target)
    {
        if (isset($_SESSION['user_name'])) {
            if ($target == 'sign')  // if sign in or sign up
            {
                # code...
                header('location: profile.php');
                exit();
            }
            if ($target == 'index')
            {
                # code...
                header('location: home.php');
                exit();
            }
        }
        else    // if needs to sign in
        {
            if ($target != 'sign' and $target != 'index')
            {
                $_SESSION["destination_"] = $target;
                header('location: sign in.php?');
                exit();
            }
        }
    }

    function printTitle()
    {
        global $pageTitle;
        if (isset($pageTitle)) {
            # code...
            echo $pageTitle;
        }
    }

    function printTime( $date) // need to be repaired
    {
        $a_time_arr =preg_split("/\s/", $date);
        $a_year = substr($a_time_arr[0], 0, 4);
        $a_month = substr($a_time_arr[0], 5, 2);
        $a_day = substr($a_time_arr[0], 8);
        $a_min = (int)preg_split("/:/", $a_time_arr[1])[1];

        $c_year = getdate()["year"];
        $c_month = getdate()["mon"];
        $c_day = getdate()["mday"];
        $a_hours = ((int)preg_split("/:/", $a_time_arr[1])[0]);
        $c_hours = idate("H") + 1;
        $c_min = idate("i");

        // years handler
        if (($c_year - $a_year) > 1) {

            if (($c_month - $a_month) > 1) {
                $more_less = ($c_year - $a_year) == 1 ?  'less than ' : 'more than ';
                $year_s = ' years ago';
                $total_years = ($c_year - $a_year);
            }
            else if ($a_month > $c_month) {
                $more_less = ($c_year - $a_year) == 1 ?  'less than ' : 'more than ';
                $year_s = ($c_year - $a_year) == 2 ? ' year ago' : ' years ago';
                $total_years = ($c_year - $a_year) - 1;
            }
            else
            {
                if (($c_day - $a_day) > 1) {
                    $more_less = ($c_year - $a_year) == 1 ?  'less than ' : 'more than ';
                    $year_s = ' years ago';
                    $total_years = ($c_year - $a_year);
                }
                else {
                    $more_less = ($c_year - $a_year) == 1 ?  'less than ' : 'more than ';
                    $year_s = ($c_year - $a_year) == 2 ? ' year ago' : ' years ago';
                    $total_years = ($c_year - $a_year) - 1;
                }
            }
            return $more_less . $total_years . $year_s;

        }
        elseif (($c_year - $a_year) ==  1) {    // 1 year age

            if ($c_month > $a_month) {
                $more_less = 'more than ';
                $year_s = ' year ago';
                $total_years = 1;
            }
            else if($c_month == $a_month) {
                $more_less = 'about ';
                $year_s = ' year ago';
                $total_years = 1;
            }
            else
            {
                $month_val = abs(12 - abs($c_month - $a_month));

                if ($month_val > 5) {    // less than year & more than 5 months
                    $more_less = 'more than ';
                    $year_s = ' months ago';
                    $total_years = 6;
                }
                else{
                    $more_less = 'about ';
                    $year_s = ' months ago';
                    $total_years = $month_val;
                }   
            }
            return $more_less . $total_years . $year_s;
        }
        elseif (($c_year - $a_year) ==  0) {    // same year
            
            $month_val = abs(12 - abs($c_month - $a_month));

            if ($c_month > $a_month) {
                $more_less = 'about ';
                $year_s = ' months ago';
                $total_years = $month_val;
            }
            else    // same month
            {
                $days_val =$c_day - $a_day;

                if ($c_day >= $a_day) {

                    if (($c_day - $a_day) > 1) {
                        $more_less = 'about ';
                        $year_s = ' days ago';
                        $total_years = $c_day - $a_day;
                    }
                    else {  // look for hours

                        $total_hours = 24 - (abs($c_hours - $a_hours));
                        if ($c_day == $a_day and $c_hours > $a_hours and ($c_hours - $a_hours) > 1) {
                            $more_less = 'about ';
                            $year_s = ' hours ago';
                            $total_years = $c_hours - $a_hours;
                        }
                        elseif ($c_day != $a_day and $total_hours < 24) {
                            $more_less = 'about ';
                            $year_s = ' hours ago';
                            $total_years = $total_hours;
                        }
                        else {

                            // look for minuites
                            if ($c_hours != $a_hours) {
                                $more_less = 'about ';
                                $year_s = ' minuites ago';
                                $total_years = $c_min + (60 - $a_min);
                            }
                            else if ($c_min > $a_min) {
                                $more_less = 'about ';
                                $year_s = ' minuites ago';
                                $total_years = $c_min - $a_min;
                            }
                            else {
                                $more_less = 'less than';
                                $year_s = ' minuite ago';
                                $total_years = 1;
                            }
                        }
                    }
                }
            }
            return $more_less . $total_years . $year_s;
        }
    }

    function interacted( $r_u_id,  $table_name,  $a_id,  $type)
    {
        global $con;
        $select_a_info = "SELECT * from $table_name where a_id = '$a_id'";
        $s_a_info_query = mysqli_query($con, $select_a_info);
        $a_info = mysqli_fetch_array($s_a_info_query);

        if ($type == "likes") {
            if (strlen($a_info["l_users"]) >= 1) {
                # code...
                $r_users_l = preg_split("/,/",$a_info["l_users"]);
                foreach($r_users_l as $user)
                {
                    if ($user == $r_u_id) {
                        # code...
                        return true;
                    }
                }
            }
            return false;
        }
        if (strlen($a_info["c_users"]) >= 1) {
            # code...
            $r_users_l = preg_split("/,/",$a_info["c_users"]);
            foreach($r_users_l as $user)
            {
                if ($user == $r_u_id) {
                    # code...
                    return true;
                }
            }
        }
        
        return false;
    }

    function uploadPhoto( $u_id,  $destination ,  $img_name,  $img_tmp_name)
    {
        global $con;
        $folder = '../pics/';
        $path = $folder . $img_name;
        $query = "INSERT Into users ('$destination') VALUES ($img_name) where user_id = '$u_id'";
        if (move_uploaded_file($img_tmp_name, $folder)) {
            # code...
            $run_query = mysqli_query($con, $query);
        }
        else {
            echo '<p style="position:absolute; background-color:red; top: 75px;">Error</p>';
        }

    }

    function isFav( $table_name,  $f_id)
    {
        global $con;
        $select_f_info = "SELECT * from $table_name where f_id = '$f_id'";
        $s_f_info_query = mysqli_query($con, $select_f_info);
        $f_info = mysqli_fetch_array($s_f_info_query);
        if ($f_info["fav_or_not"] == 1) {
            # code...
            return true;
        }
        return false;
    }

    function WeeklyLeaderboard($limit)
    {
        global $con;
        $query = "SELECT * FROM users ORDER BY user_weekly_c_count DESC, user_today_c_count DESC, user_c_count DESC LIMIT $limit";
        $run_query = mysqli_query($con, $query);
        return $run_query;
    }

    function separateBirthday( $date,  $destination)
    {
        $dateArr = preg_split("/-/", $date);
        if ($destination == "d") {
            $val = $dateArr[2];
            if($val[0] == '0')
            {
                return $val[1];
            }
            else
                return $val;
        }
        else if ($destination == "m") {
            $val = $dateArr[1];
            if($val[0] == '0')
            {
                return $val[1];
            }
            else
            return $val;
        }
        else
            return $dateArr[0];

    }

    function idExists( $id)
    {
        global $con;
        $check = "SELECT user_name from users where user_id ='$id'";
        $check_query = mysqli_query($con, $check);
        if (strlen(mysqli_fetch_array($check_query)[0]) > 0) {

            return true;
        }
        return false;
    }

    function qSent( $q_content,  $s_user_name, $type,  $s_status,  $r_id)
    {
        global $con;
        $table_name = $r_id . "_questions";
        if ($type == "s") {
            $insert = "INSERT INTO $table_name (q_content, q_sender, q_type, q_status)
                                    VALUES('$q_content', '$s_user_name', 's', '$s_status')";
        }
        else {
            $insert = "INSERT INTO $table_name (q_content, q_sender, q_type, q_status)
            VALUES('$q_content', '$s_user_name', 'q', '$s_status')";
        }

        if (mysqli_query($con, $insert)) {
            return true;
        }
        return false;
    }

    function sendToTargets( $question, $targets,  $s_id,  $user_name, $status){
        global $con;
        $question = trim($question);
        // if shoutout
        if (trim($targets) == "") {
            $i = 1;
            $targets = array($s_id);
            $select_range = "SELECT MAX(user_id) AS maximum, MIN(user_id) AS minimum FROM users";
            $select_query = mysqli_query($con, $select_range);
            $select_result = mysqli_fetch_array($select_query);
            $maximum = $select_result["maximum"];
            $minimum = $select_result["minimum"];
            while ($i <= 3) {
                $id = rand($minimum, $maximum);
                $targetable = true;
                foreach($targets as $target)
                {
                    if ($target == $id) {
                        $targetable = false;
                        break;
                    }
                }
                if ($targetable) {
                    if (idExists($id)) {
                        if (qSent($question, $user_name, "s", $status, $id)) {
                            array_push($targets, $id);
                            $i += 1;
                        }
                    }
                }
            }
            if ($i = 3)
                return true;
            return false;
        }
        // if question to friends
        else {
            $targets = preg_split("/,/", $targets);
            foreach($targets as $target)
            {
                $r_id = (int) $target;
                qSent($question, $user_name, "q",$status, $r_id);
            }
            return true;
        }
    }

    function notifyFriends( $id,  $v_id) // when create versus
    {
        global $con;
        $f_table_name = $id . '_friends';
        $selecting = "SELECT f_id FROM $f_table_name";
        $selecting_q = mysqli_query($con, $selecting);
        while ($frnd = mysqli_fetch_array($selecting_q)) {
            # code...
            $f_id = $frnd[0];
            $n_table_name = $f_id . '_notifications';
            $n_info = $v_id . ',' . $id . '_versus';
            $inserting = "INSERT INTO $n_table_name (n_info) VALUES('$n_info')";
            mysqli_query($con, $inserting);
        }
    }

    function notifyFriend( $id,  $a_id,  $target_name) // when answer a q
    {
        global $con;
        $select = "SELECT user_id FROM users WHERE user_name = '$target_name'";
        $target_id = mysqli_fetch_array(mysqli_query($con, $select))[0];
        $n_table_name = $target_id . '_notifications';
        $n_info = $a_id . ',' . $id . '_answers';
        $inserting = "INSERT INTO $n_table_name (n_info) VALUES ('$n_info')";
        if (mysqli_query($con, $inserting)) {
            return true;
        }
        return false;
    }

    function notifyMe( $n_type,  $target_n_id,  $target_user_id,  $r_id = null) // when receive q | get like
    {
        global $con;
        $n_table_name = $target_user_id . '_notifications';
        if($n_type == "q")
            $n_info = $target_n_id . ',' . $target_user_id . '_questions';
        else
            $n_info = $target_n_id . ',' . $target_user_id . '_answers' . ',' . $r_id;
        $inserting = "INSERT INTO $n_table_name (n_info) VALUES ('$n_info')";
        if (mysqli_query($con, $inserting)) {
            return true;
        }
        return false;
    }

    function randomPic()
    {
        $id = rand(1, 2);
        return "pics/private/" . $id . ".jpeg";
    }

    function separatePermissions( $permissions)
    {
        return preg_split("/,/", $permissions);
    }

    function create_user_tables($user_id)
    {
        global $con;

        #tables names
        $q_table_name = $user_id .'_' . 'questions';
        $a_table_name = $user_id .'_' . 'answers';
        $f_table_name = $user_id .'_' . 'friends';
        $n_table_name = $user_id .'_' . 'notifications';
        $b_table_name = $user_id .'_' . 'blocks';
        $v_table_name = $user_id .'_' . 'versus';

        #table creation
        #questions table
        $q_table_s = "CREATE TABLE $q_table_name(
                    q_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    q_content VARCHAR(255) NOT NULL,
                    q_sender VARCHAR(100) NOT NULL,
                    q_type VARCHAR(2) DEFAULT 'q',
                    q_status VARCHAR(8) DEFAULT 'public',
                    q_date TIMESTAMP DEFAULT current_timestamp()                   
                )";
        $q_table_query = mysqli_query($con, $q_table_s);
        #answers table
        $a_table_s = "CREATE TABLE $a_table_name(
                    a_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    q_content VARCHAR(255) NOT NULL,
                    q_sender VARCHAR(100) NOT NULL,
                    a_content VARCHAR(255) NOT NULL,
                    l_sum INT(11) DEFAULT '0',
                    l_users varchar(255),   #user_1, user_2
                    c_sum INT(11) DEFAULT '0',
                    c_users varchar(255),   #user_1, user_2
                    q_type VARCHAR(2) DEFAULT 'q',
                    q_status VARCHAR(8) DEFAULT 'public',
                    a_date TIMESTAMP DEFAULT current_timestamp()                   
                )";
        $a_table_query = mysqli_query($con, $a_table_s);
        #friends table
        $f_table_s = "CREATE TABLE $f_table_name(
                    f_id INT NOT NULL ,
                    fav_or_not TINYINT(1) DEFAULT '0'                    
                )";
        $f_table_query = mysqli_query($con, $f_table_s);
        #Notifications table
        $n_table_s = "CREATE TABLE $n_table_name(
                    n_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    n_info VARCHAR(100),   #(n_type, n_id, u_id)
                    read_or_not TINYINT(1) DEFAULT '0',
                    n_date TIMESTAMP DEFAULT current_timestamp()                   
                )";
        $n_table_query = mysqli_query($con, $n_table_s);
        #Blocks table
        $b_table_s = "CREATE TABLE $b_table_name(
                    b_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    b_info VARCHAR(100) NOT NULL  #(b_type, b_id, u_id)
                )";
        $b_table_query = mysqli_query($con, $b_table_s);
        #Versus table
        $v_table_s = "CREATE TABLE $v_table_name(
                    v_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    v_head VARCHAR(100) NULL,
                    v_pic_1 VARCHAR(255) NOT NULL,
                    v_pic_2 VARCHAR(255) NOT NULL,
                    v_l1_sum INT(11) DEFAULT '0',
                    v_l1_users varchar(255),   #user_1, user_2
                    v_l2_sum INT(11) DEFAULT '0',
                    v_l2_users varchar(255),   #user_1, user_2
                    v_date TIMESTAMP DEFAULT current_timestamp() 
                )";
        $v_table_query = mysqli_query($con, $v_table_s);
    }

    function isBlocked($u_id, $r_u_id)
    {
        global $con;
        $b_table_name = $u_id . '_blocks';
        $select_stmt = "SELECT * FROM $b_table_name";
        $query = mysqli_query($con, $select_stmt);

        while($res = mysqli_fetch_array($query))
        {
            $b_info = preg_split("/,/", $res['b_info']);
            if (count($b_info) == 1) { // profile (u_id)
                if ($b_info[0] == $r_u_id) {
                    return true;
                }
            }
            else    // question or shoutout (q_content, q_type, q_status, u_id)
            {
                if ($b_info[3] == $r_u_id) {
                    return true;
                }
            }
        }
        return false;
    }

    function detectDir($word)
    {
        $dir = "rtl";
        if ( (ord($word[0]) > 49 and ord($word[0]) < 57) or (ord($word[0]) > 65 and ord($word[0]) < 90) or (ord($word[0]) > 97 and ord($word[0]) < 122)) {
            $dir = "ltr";
        }
        if (strlen($word) > 1) {
            if ( (ord($word[1]) > 49 and ord($word[1]) < 57) or (ord($word[1]) > 65 and ord($word[1]) < 90) or (ord($word[1]) > 97 and ord($word[1]) < 122)) {
                $dir = "ltr";
            }
        }
        return $dir;
    }

    function filterNotiVote($v_id , $n_table_name)
    {
        global $con;
        $select_stmt = "SELECT * FROM $n_table_name ";
        $select_query = mysqli_query($con, $select_stmt);
        $notis = mysqli_fetch_all($select_query);
        $id_arr = array();

        foreach($notis as $noti){

            $noti_info = preg_split("/,/", $noti[1]);
            if (count($noti_info) == 4) // noti type is versus vote
            {
                $n_v_id = $noti_info[1];
                if ($n_v_id == $v_id) {
                    array_push($id_arr, $noti[0]);
                }
            }
        }
        if (count($id_arr) > 0) {
            $delete_stmt = "DELETE FROM $n_table_name WHERE n_id = '$id_arr[0]'";
            $escape = true;
            foreach($id_arr as $id)
            {
                if ($escape == false) {
                    $delete_stmt .= " OR n_id = '$id'";
                }
                else 
                    $escape = false;
            }
            mysqli_query($con, $delete_stmt);
        }
    }

    function notiReaded($n_table_name)
    {
        global $con;
        $select_stmt = "SELECT * FROM $n_table_name WHERE read_or_not = 0";
        $select_query = mysqli_query($con, $select_stmt);
        while($select_result = mysqli_fetch_array($select_query))
        {
            $id = $select_result["n_id"];
            $update_stmt = "UPDATE $n_table_name SET read_or_not = '1' WHERE n_id = $id";
            mysqli_query($con, $update_stmt);
        }
    }

    function updateLikesOrCoinsCount($user_id, $likeOrCoins, $op_type)
    {
        global $con;

        if ($likeOrCoins == "l") {   // update like sum
            $select_stmt = "SELECT user_l_count FROM users WHERE user_id = '$user_id'";
            $l_count = mysqli_fetch_array(mysqli_query($con, $select_stmt))["user_l_count"];

            if ($op_type == "r") {
                $l_count -= 1;
            }
            else
            {
                $l_count += 1;
            }
            $update_stmt = "UPDATE users SET user_l_count = '$l_count' WHERE user_id = '$user_id'";
            mysqli_query($con, $update_stmt);
        }
        elseif($likeOrCoins == "c")  // update coins sum
        {
            $select_stmt = "SELECT user_c_count, user_today_c_count, user_weekly_c_count FROM users WHERE user_id = '$user_id'";
            $c_count_res = mysqli_fetch_array(mysqli_query($con, $select_stmt));
            $c_count = $c_count_res['user_c_count'];
            $c_t_count = $c_count_res['user_today_c_count'];
            $c_w_count = $c_count_res['user_weekly_c_count'];

            if ($op_type == "a") {
                $c_count += 5;
                $c_t_count += 5;
                $c_w_count += 5;
            }
            else if ($op_type == "r") {
                $c_count -= 5;
                $c_t_count -= 5;
                $c_w_count -= 5;
            }
            
            $update_stmt = "UPDATE users SET user_c_count = '$c_count', user_today_c_count = '$c_t_count',
                                user_weekly_c_count = '$c_w_count' WHERE user_id = '$user_id'";
            mysqli_query($con, $update_stmt);
        }
    }
    
    function isFriend($table_name,  $f_id)
    {
        global $con;
        $select_f_info = "SELECT * from $table_name where f_id = '$f_id'";
        $s_f_info_query = mysqli_query($con, $select_f_info);
        $f_info = mysqli_fetch_array($s_f_info_query);
        if (is_null($f_info)) {
            # code...

            return false;
        }
        return true;
    }

    function filterNotiCoins($n_table_name, $a_id, $user_id)
    {
        global $con;
        $select_stmt = "SELECT * FROM $n_table_name";
        $select_query = mysqli_query($con, $select_stmt);
        $select_result = mysqli_fetch_all($select_query);
        $id_arr = array();
        $c_count = 5;

        foreach($select_result as $noti)
        {
            $noti_info = preg_split("/,/", $noti[1]);
            $n_read_state = $noti[2];
            $n_id = $noti[0];

            if($noti_info[0] == "c") // coins noti
            {
                if ($noti_info[1] == $a_id) { // same answer
                    if($noti_info[3] == $user_id)   // same user
                    {
                        array_push($id_arr, $n_id);
                        $c_count += $noti_info[2];
                    }       
                }
            }
        }
        // delete old notis
        if (count($id_arr) > 0) {
            $delete_stmt = "DELETE FROM $n_table_name WHERE n_id = '$id_arr[0]'";
            $escape = true;
            foreach($id_arr as $id)
            {
                if ($escape == false) {
                    $delete_stmt .= " OR n_id = '$id'";
                }
                else 
                    $escape = false;
            }
            mysqli_query($con, $delete_stmt);
        }
        return $c_count;
    }

    function deleteNoti($user_id, $q_id, $del_type)
    {
        global $con;

        if ($del_type == 'q') {     // del new q noti

            $n_table_name = $user_id .'_notifications';
            $select_noti = "SELECT * FROM $n_table_name";
            $select_noti_q = mysqli_query($con, $select_noti);
            $n_id = -1;

            while ($noti_info = mysqli_fetch_array($select_noti_q)) {
                $noti_info_info = preg_split("/,/", $noti_info['n_info']);
                if ($noti_info_info[0] == $q_id) {
                    $n_id = $noti_info['n_id'];
                    break;
                }
            }
    
            $delete_noti = "DELETE FROM $n_table_name where n_id = '$n_id'";
            mysqli_query($con, $delete_noti);

        }

        if ($del_type == 'a') {     // del user answer your q noti

            // user_id is the q_sender  & q_id is the a_id
            $select_u = "SELECT * FROM users WHERE user_name = '$user_id'";
            $select_u_q = mysqli_query($con, $select_u);
            $u_info = mysqli_fetch_array($select_u_q);

            $n_table_name = $u_info['user_id'] .'_notifications';
            $select_noti = "SELECT * FROM $n_table_name";
            $select_noti_q = mysqli_query($con, $select_noti);
            $n_id = -1;

            $t_n_info = $q_id . ',' . $_SESSION['user_id'] . '_answers';

            while ($noti_info = mysqli_fetch_array($select_noti_q)) {
                if ($noti_info['n_info'] == $t_n_info) {
                    $n_id = $noti_info['n_id'];
                    break;
                }
            }

            $delete_noti = "DELETE FROM $n_table_name where n_id = '$n_id'";
            mysqli_query($con, $delete_noti);

        }

        if($del_type == 'l')        // del user like your answer
        {

            $n_table_name = $user_id .'_notifications';
            $select_noti = "SELECT * FROM $n_table_name";
            $select_noti_q = mysqli_query($con, $select_noti);
            $n_id = -1;

            $t_n_info = $q_id . ',' . $user_id . '_answers' . ',' . $_SESSION['user_id'];

            while ($noti_info = mysqli_fetch_array($select_noti_q)) {
                if ($noti_info['n_info'] == $t_n_info) {
                    $n_id = $noti_info['n_id'];
                    break;
                }
            }

            $delete_noti = "DELETE FROM $n_table_name where n_id = '$n_id'";
            mysqli_query($con, $delete_noti);

        }
    }

    function deleteNotisL($a_id)
    {
        global $con;
        $user_id = $_SESSION['user_id'];
        $n_table_name = $user_id . '_notifications';
        $a_table_name = $user_id . '_answers';
        $n_id_arr = array();

        $select_noti = "SELECT * FROM $n_table_name";
        $select_noti_q = mysqli_query($con, $select_noti);

        $t_n_info = $a_id . ',' . $a_table_name;

        while ($noti_info = mysqli_fetch_array($select_noti_q)) {

            $noti_info_info = preg_split("/,/", $noti_info['n_info']);
            $noti_info_test = $noti_info_info[0] . ',' . $noti_info_info[1];

            if ($noti_info_test == $t_n_info) {
                array_push($n_id_arr, $noti_info['n_id']);
            }

            else if (count($noti_info_info) == 4) {
                if ($noti_info_info[0] == 'c') {
                    if ($noti_info_info[1] == $a_id) {
                        array_push($n_id_arr, $noti_info['n_id']);
                    }
                }
            }
        }

        foreach($n_id_arr as $n_id)
        {
            $delete_noti = "DELETE FROM $n_table_name where n_id = '$n_id'";
            mysqli_query($con, $delete_noti);
        }

        // c , a_id , c_cout , u_id

    }

    function deleteAllQuesNotis($user_id)
    {
        global $con;
        $n_table_name = $user_id . '_notifications';
        $n_info = $user_id . '_questions';
        $id_arr = array();

        $select_stmt = "SELECT * FROM $n_table_name";
        $select_q = mysqli_query($con, $select_stmt);
        while ($select_res = mysqli_fetch_array($select_q)) {
            $select_res_info = preg_split("/,/", $select_res['n_info']);
            if(count($select_res_info) == 2)
            {
                if ($select_res_info[1] == $n_info) {
                    array_push($id_arr, $select_res['n_id']);
                }
            }
        }

        if (count($id_arr) > 0) {
            $in_condition = '( ' . "'$id_arr[0]'";
            $i = 1;
            if (count($id_arr) > 2) {
                while ( $i < count($id_arr)  ) {
                    $in_condition .= ',';
                    $in_condition .= "'$id_arr[$i]'";
                    $i += 1;
                }
            }
            elseif (count($id_arr) == 2) {
                $in_condition .= ',';
                $in_condition .= "'$id_arr[$i]'";
            }
            $in_condition .= ' )';

            $delete_stmt = "DELETE FROM $n_table_name WHERE n_id IN $in_condition";
            mysqli_query($con, $delete_stmt);
        }

    }

    function updateMyLikes($l_sum)
    {
        global $con;
        $user_id = $_SESSION['user_id'];
        $select_u_stmt= "SELECT * FROM users WHERE user_id = '$user_id'";
        $select_u_q = mysqli_query($con, $select_u_stmt);
        $u_info = mysqli_fetch_array($select_u_q);

        $old_l_sum = $u_info['user_l_count'];
        
        $new_l_sum = $old_l_sum - $l_sum;

        $update_u_stmt = "UPDATE users SET user_l_count = $new_l_sum WHERE user_id = '$user_id'";
        mysqli_query($con, $update_u_stmt);

        return $new_l_sum;
    }

    function recommendFriends($user_id)
    {
        global $con;

        $f_table_name = $user_id . '_friends';
        $select_f_stmt = "SELECT * FROM $f_table_name";
        $select_f_q = mysqli_query($con, $select_f_stmt);
        
        $select_shared_stmt = "SELECT users.user_id FROM users";
        $f_count = 0;
        $id_arr = array();
    
        while ($f_info = mysqli_fetch_array($select_f_q)) {
            
            $f_id_table_name = $f_info['f_id'] . '_friends';
            $select_shared_stmt .= " INNER JOIN $f_id_table_name ON users.user_id = $f_id_table_name.f_id";
        }
    
        if ($f_count > 0) {
            $select_shared_q = mysqli_query($con, $select_shared_stmt);
            while ($select_shared_val = mysqli_fetch_array($select_shared_q)) {
                array_push($id_arr, $select_shared_val['user_id']);
                $f_count += 1;
            }
        }
    
        if ($f_count < 3) {
            $select_mm_stmt = "SELECT MIN(user_id) AS minimum, MAX(user_id) AS maximum FROM users";
            $select_mm_val = mysqli_fetch_array(mysqli_query($con, $select_mm_stmt));
            $timer = 0;

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
                    $timer += 1;
                }
                if ($timer == 1000) {
                    break;
                }
            }
        }

        return $id_arr;
    }

    function changeFollowersCount($user_id, $c_type)
    {
        global $con;
        $select_stmt = "SELECT user_f_count FROM users WHERE user_id = '$user_id'";
        $old_f_count = mysqli_fetch_array(mysqli_query($con, $select_stmt))['user_f_count'];

        if ($c_type == 'a') {   // add follower
            $new_f_count = $old_f_count + 1;
        }
        elseif ($c_type == 'r') {
            $new_f_count = $old_f_count - 1;
        }

        $update_stmt = "UPDATE users SET user_f_count = '$new_f_count' WHERE user_id = '$user_id'";
        mysqli_query($con, $update_stmt);
    }
?>