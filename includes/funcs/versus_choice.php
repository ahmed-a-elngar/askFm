<?php

    include('../../connection.php');
    include('functions.php');

    if(isset($_POST["pa_1"]))
    {
        $r_u_id = $_POST["pa_1"];
        $table_name = $_POST["pa_2"];
        $v_id_and_choice = $_POST["pa_3"];
        $v_id_and_choice_arr = preg_split("/,/", $v_id_and_choice);
        $v_id = $v_id_and_choice_arr[0];
        $v_choice = $v_id_and_choice_arr[1];
        global $con;
        $u_id = preg_split("/_/", $table_name)[0];
        if (! isblocked($u_id, $r_u_id)) {
            $select_v_info = "SELECT * from $table_name where v_id = '$v_id'";
            $s_v_info_query = mysqli_query($con, $select_v_info);
            $v_info = mysqli_fetch_array($s_v_info_query);
    
            if ($v_info == null) {
                header('location: unReachable.php');
                exit();
            }

            if($v_choice == 1)
            {
                $r_u_list = $v_info['v_l1_users'] . ',' . $r_u_id;
                $r_u_count = $v_info['v_l1_sum'] + 1;
                $update_v_info = "UPDATE $table_name SET v_l1_sum='$r_u_count', v_l1_users='$r_u_list' where v_id = '$v_id'";
            }
            elseif($v_choice == 2)
            {
                $r_u_list = $v_info['v_l2_users'] . ',' . $r_u_id;
                $r_u_count = $v_info['v_l2_sum'] + 1;
                $update_v_info = "UPDATE $table_name SET v_l2_sum='$r_u_count', v_l2_users='$r_u_list' where v_id = '$v_id'";
            }
            $u_v_info_query = mysqli_query($con, $update_v_info);

            // remove related notifications & notify user
            $noti_table_name = $u_id . '_notifications';
            filterNotiVote($v_id, $noti_table_name);

            $n_info = 'v,' . $v_id . ',' . $v_choice . ',' . $r_u_id;
            $noti_query = "INSERT INTO  $noti_table_name (n_info) VALUES ('$n_info')";
            $run_noti_query = mysqli_query($con, $noti_query);

        }
    }

?>
