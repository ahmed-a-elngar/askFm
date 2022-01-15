<?php

    session_start();
    include '../../connection.php';

    if(isset($_POST['pa_1']))
    {
        $unblock_id = $_POST['pa_1'];
        $b_t_name = $_SESSION['user_id'] . '_blocks';

        $delete_stmt = "DELETE FROM $b_t_name WHERE b_id = '$unblock_id'";
        mysqli_query($con, $delete_stmt);

    }

?>