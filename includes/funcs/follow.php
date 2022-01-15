<?php
    session_start();    
    include('../../connection.php');
    include 'functions.php';

    if(isset($_POST['pa_1']))
    {
        $u_id = $_POST['pa_1'];     // owner id
        $f_id = $_POST['pa_2'];     // target
        $f_table_name = $u_id . '_friends';
        $insert_stmt = "INSERT INTO $f_table_name (f_id, fav_or_not) VALUES('$f_id', 0)";
        mysqli_query($con, $insert_stmt);
        changeFollowersCount($f_id, 'a');
        echo
        '
            <a href="ask_friend.php?user_id_='.$f_id.'#">Ask ></a>
        ';
    }