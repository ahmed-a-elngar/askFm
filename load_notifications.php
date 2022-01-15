<?php
    session_start();
    include 'connection.php';

    try {
        if(isset($_SESSION['user_id']))
        {
            $table_name = $_SESSION['user_id'] . '_notifications';
            $stmt = "SELECT * FROM $table_name WHERE read_or_not = '0'";
            $query = mysqli_query($con ,$stmt);
            $count = 0;
            while($res = mysqli_fetch_array($query))
            {
                $count += 1;
            }
            if($count != 0)
                echo $count;
        }
    } catch (Exception $e) {
        header('location: unReachable.php');
        exit();
    }
