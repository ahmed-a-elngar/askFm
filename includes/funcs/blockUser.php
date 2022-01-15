<?php

    session_start();
    include '../../connection.php';

    if(isset($_POST['pa_1']))
    {
        $q_id = $_POST['pa_1'];
        $q_table_name = $_SESSION['user_id'] . '_questions';
        $b_table_name = $_SESSION['user_id'] . '_blocks';

        // question or shoutout (q_content, q_type, q_status, u_id)
        $select_stmt = "SELECT * FROM $q_table_name WHERE q_id = '$q_id'";
        $select_q = mysqli_query($con, $select_stmt);
        $q_info = mysqli_fetch_array($select_q);

        $u_name = $q_info['q_sender'];
        $u_info = mysqli_fetch_array(mysqli_query($con, "SELECT user_id FROM users WHERE user_name = '$u_name'"));

        $b_info = $q_info['q_content'] . ',' . $q_info['q_type'] . ',' . $q_info['q_status'] . ',' . $u_info['user_id'];

        // block user
        $insert_stmt = "INSERT INTO $b_table_name(b_info) VALUES('$b_info')";
        mysqli_query($con, $insert_stmt);

        // delete q
        $delete_stmt = "DELETE FROM $q_table_name WHERE q_id = '$q_id'";
        mysqli_query($con, $delete_stmt);
    }