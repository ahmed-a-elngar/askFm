<?php

    include('../../connection.php');
    include 'functions.php';

    if(isset($_POST["pa_1"]))
    {
        $f_id = $_POST["pa_1"];
        $table_name = $_POST["pa_2"];
        $return_val = "";
        global $con;

        $select_a_info = "SELECT * from $table_name where f_id = '$f_id'";
        $s_f_info_query = mysqli_query($con, $select_a_info);
        $f_info = mysqli_fetch_array($s_f_info_query);

        // fet user followers count
        $target_id = preg_split("/_/", $table_name)[0];

        $select_f_count_stmt = "SELECT user_f_count FROM users WHERE user_id = '$target_id'";
        $f_count = mysqli_fetch_array(mysqli_query($con, $select_f_count_stmt))["user_f_count"];

        if (is_null($f_info))
        {
            // add to friends
            $insert_f_stmt = "INSERT INTO $table_name(f_id, fav_or_not)
                                            VALUES('$f_id', 0)";
            mysqli_query($con, $insert_f_stmt);

            $return_val = "Followed";
            changeFollowersCount( $f_id, 'a');
        }
        else
        {
            // remove from friends
            $delete_f_stmt = "DELETE FROM $table_name WHERE f_id = '$f_id'";
            mysqli_query($con, $delete_f_stmt);

            $return_val = '<i class="fa fa-plus" style="font-size:11px; padding-right:4px;"></i>Follow';
            changeFollowersCount( $f_id, 'r');
        }

        echo $return_val;
    }

?>
