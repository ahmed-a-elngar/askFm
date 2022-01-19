<?php
    session_start();
    $_SESSION['active_tab'] = 'log out';

    session_unset();
    session_destroy();
    header('location: sign in.php');
    exit();
?>