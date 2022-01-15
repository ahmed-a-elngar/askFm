<?php
    session_start();
    if (isset($_SESSION['reload'])) {
        $link = $_SESSION['reload'];
        $_SESSION['reload'] = null;
        header('location:../../' . $link);
    }
?>