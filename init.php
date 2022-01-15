<?php
    
    include('connection.php');

    // routes
    $tmp = 'includes/temps/';
    $css = 'css/';
    $js = 'js/';
    //$lang = 'includes/langs/';
    $func = 'includes/funcs/';

    # structure 
    //include  $lang . 'en.php';
    include  $func . 'functions.php';
    include  $tmp . 'header.php';

    if (! isset($noNav)) {
        # code...
        include $tmp . 'navbar.php';
    }

?>