<?php
    session_start();
    include '../../connection.php';
    include 'functions.php';

    if(isset($_POST['pa_1']))
    {
        $q_id = $_POST['pa_1'];
        $q_table_name = $_SESSION['user_id'] . '_questions';
        $delete_stmt = "DELETE FROM $q_table_name WHERE q_id = '$q_id'";
        mysqli_query($con, $delete_stmt);

        // delete new question notification
        deleteNoti($_SESSION['user_id'], $q_id, 'q');
    }