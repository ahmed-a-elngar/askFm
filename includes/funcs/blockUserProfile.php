<?php

    session_start();
    include '../../connection.php';

    if(isset($_POST['pa_1']))
    {
        $u_id = $_POST['pa_1'];
        $b_table_name = $_SESSION['user_id'] . '_blocks';

        // block user
        $insert_stmt = "INSERT INTO $b_table_name(b_info) VALUES('$u_id')";
        mysqli_query($con, $insert_stmt);

        echo'
            <i class="fa fa-flag"></i>Block
        ';
    }