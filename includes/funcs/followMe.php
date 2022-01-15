<?php

    session_start();
    include '../../connection.php';
    include 'functions.php';

    if (isset($_POST['pa_1'])) {

        $target_id = $_POST['pa_1'];
        $user_id = $_SESSION['user_id'];
        $f_table_name = $user_id . '_friends';

        $insert_stmt = "INSERT INTO $f_table_name(f_id) VALUES('$target_id')";
        mysqli_query($con, $insert_stmt);

        changeFollowersCount($target_id, 'a');
    }