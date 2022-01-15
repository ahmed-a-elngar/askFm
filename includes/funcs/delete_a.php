<?php
    session_start();
    include '../../connection.php';
    include 'functions.php';

    if(isset($_POST['pa_1']))
    {
        $a_id = $_POST['pa_1'];
        $a_table_name = $_SESSION['user_id'] . '_answers';
        $select_a_stmt = "SELECT * FROM $a_table_name WHERE a_id = '$a_id'";
        $a_info = mysqli_fetch_array(mysqli_query($con, $select_a_stmt));

        $q_content = $a_info['q_content'];
        $q_sender = $a_info['q_sender'];
        $q_type = $a_info['q_type'];
        $q_status = $a_info['q_status'];

        $q_table_name = $_SESSION['user_id'] . '_questions';
        $insert_q_stmt = "INSERT INTO $q_table_name(q_content, q_sender, q_type, q_status)
                                            VALUES('$q_content', '$q_sender', '$q_type', '$q_status')";
        mysqli_query($con, $insert_q_stmt);
                                
        // remove answer likes & coins from my total
        $a_l_sum = $a_info['l_sum'];
        $new_l_count = updateMyLikes($a_l_sum);

        $delete_a_stmt = "DELETE FROM $a_table_name WHERE a_id = '$a_id'";
        mysqli_query($con, $delete_a_stmt);

        deleteNoti($q_sender, $a_id, 'a');
        deleteNotisL($a_id);

    }