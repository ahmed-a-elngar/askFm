<?php

    session_start();
    include '../../connection.php';
    include 'functions.php';

    if(isset($_POST['pa_1']))
    {
        $user_id = $_SESSION['user_id'];
        $q_table_name = $user_id . '_questions';
        $delete_stmt = "DELETE FROM $q_table_name";
        mysqli_query($con, $delete_stmt);

        deleteAllQuesNotis($user_id);
    }