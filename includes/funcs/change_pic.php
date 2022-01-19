<?php
    session_start();
    include '../../connection.php';
    include 'functions.php';

    if (isset($_POST['pa_1'])) {

        $pic_name = $_FILES["user_pic"]["name"];    //  the selected name
        $pic_tempname = $_FILES["user_pic"]["tmp_name"]; 
        $pic_path = "pics/".$pic_name;  

        $uploading_pic_result = move_uploaded_file($pic_tempname, $pic_path);


    }
?>