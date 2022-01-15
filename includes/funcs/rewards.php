<?php
    session_start();
    include('../../connection.php');
    include('functions.php');

    if(isset($_POST["pa_1"]))
    {
        $r_u_id = $_POST["pa_1"];
        $u_id = $_POST["pa_2"];
        $table_name = $_POST["pa_3"];
        $a_id = $_POST["pa_4"];
        $old_c_count = $_POST["pa_5"];
        global $con;

        if (! isBlocked($u_id, $r_u_id)) {

            $user_id = $_SESSION['user_id'];
            $select_u_stmt = "SELECT * FROM users WHERE user_id = '$user_id'";
            $my_coins = mysqli_fetch_array(mysqli_query($con, $select_u_stmt))['user_c_count'];

            if ($my_coins >= 5) {
                $select_a_info = "SELECT * from $table_name where a_id = '$a_id'";
                $s_a_info_query = mysqli_query($con, $select_a_info);
                $a_info = mysqli_fetch_array($s_a_info_query);
                $a_count = $a_info["c_sum"];

                if ($a_info == null) {
                    header('location: unReachable.php');
                    exit();
                }

                # code...
        
                $exists = false;
                $added_u_list = $a_info["c_users"];
        
                if ($a_info["c_sum"] >= 1) {
                    $r_users_l = preg_split("/,/",$a_info["c_users"]);
                    foreach($r_users_l as $user)
                    {
                        if ($user == $r_u_id) {
                            $exists = true;
                            break;
                        }
                    }
                }
                if (!$exists) {
                    $added_u_list = $a_info["c_users"] . ',' . $r_u_id;
                }
                $a_count += 5;
                $update_a_info = "UPDATE $table_name SET c_users='$added_u_list', c_sum='$a_count' where a_id = '$a_id'";
                $u_a_info_query = mysqli_query($con, $update_a_info);
                // update user total likes count
                updateLikesOrCoinsCount($u_id, "c", "a");
                updateLikesOrCoinsCount($r_u_id, "c", "r");

                $noti_table_name = $u_id . '_notifications';
                // delete old notis & return oins sum for this user
                $c_count = filterNotiCoins($noti_table_name, $a_id, $r_u_id);
                // send notification about new like
                $noti_query = "INSERT INTO  $noti_table_name (n_info) VALUES ('c,$a_id,$c_count,$r_u_id')";
                $run_noti_query = mysqli_query($con, $noti_query);
                
                echo $a_count;
            }
            else
                echo $old_c_count;
        }
        else{
            echo $old_c_count;
        }
    }

?>
