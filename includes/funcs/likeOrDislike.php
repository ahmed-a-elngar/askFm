<?php

    session_start();
    include('../../connection.php');
    include 'functions.php';


    if(isset($_POST["pa_1"]))
    {
        $r_u_id = $_POST["pa_1"];
        $u_id = $_POST["pa_2"];
        $table_name = $_POST["pa_3"];
        $a_id = $_POST["pa_4"];
        $old_l_count = $_POST["pa_5"];
        global $con;
        $removed = false;

        if (! isBlocked($u_id, $r_u_id)) {
            $select_a_info = "SELECT * from $table_name where a_id = '$a_id'";
            $s_a_info_query = mysqli_query($con, $select_a_info);
            $a_info = mysqli_fetch_array($s_a_info_query);
            $a_count = $a_info["l_sum"];

            if ($a_info == null) {
                header('location: unReachable.php');
                exit();
            }
            # code...
    
            if ($a_info["l_sum"] >= 1) {
                $r_users_l = preg_split("/,/",$a_info["l_users"]);
                foreach($r_users_l as $user)
                {
                    if ($user == $r_u_id) {
                        $r_user = ',' . $user;
                        $replaced_u_list = str_replace(($r_user), '', $a_info["l_users"]);
                        $a_count -= 1;
                        $update_a_info = "UPDATE $table_name SET l_users='$replaced_u_list', l_sum='$a_count' where a_id = '$a_id'";
                        $u_a_info_query = mysqli_query($con, $update_a_info);
                        $removed = true;
                        // update user total likes count
                        updateLikesOrCoinsCount($u_id, "l", "r");
                        // remove noti
                        deleteNoti($u_id,$a_id,'l');
                    }
                }
            }
            if($removed == false)
            {
                $added_u_list = $a_info["l_users"] . ',' . $r_u_id;
                $a_count += 1;
                $update_a_info = "UPDATE $table_name SET l_users='$added_u_list', l_sum='$a_count' where a_id = '$a_id'";
                $u_a_info_query = mysqli_query($con, $update_a_info);
                // update user total likes count
                updateLikesOrCoinsCount($u_id, "l", "a");
                // send notification about new like
                notifyMe("l", $a_id, $u_id, $r_u_id);                
                /*$noti_table_name = $u_id . '_notifications';
                $n_info = 
                $noti_query = "INSERT INTO  $noti_table_name (n_info) VALUES ('l,$a_id')";
                $run_noti_query = mysqli_query($con, $noti_query);*/
    
            }
            echo $a_count;
        }
        else
        {
            echo $old_l_count;
        }
    }

?>
