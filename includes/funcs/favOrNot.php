<?php
    session_start();
    include('../../connection.php');
    include('functions.php');

    if(isset($_POST["pa_1"]))
    {
        $timer = 0;
        $f_id = $_POST["pa_1"];
        $table_name = $_POST["pa_2"];
        $color = $_POST["pa_3"];
        $u_id = $_SESSION['user_id'];
        global $con;

        $select_a_info = "SELECT * from $table_name where f_id = '$f_id'";
        $s_f_info_query = mysqli_query($con, $select_a_info);
        $f_info = mysqli_fetch_array($s_f_info_query);

        if (! isBlocked($f_id, $u_id)) {

            if (is_null($f_info))
            {
                // add to friends
                $insert_f_stmt = "INSERT INTO $table_name(f_id, fav_or_not)
                                                VALUES('$f_id', 1)";
                mysqli_query($con, $insert_f_stmt);
            }
            else
            {
                $f_status = $f_info["fav_or_not"];
                # code...

                if ($f_status == 0) {
                    $f_status = 1;
                }
                else
                {
                    $f_status = 0;
                }
                if($timer == 0)
                {
                    $update_f_info = "UPDATE $table_name SET fav_or_not='$f_status' where f_id = '$f_id'";
                    $u_f_info_query = mysqli_query($con, $update_f_info);
                    $timer = 1;
                }
            }
    
            echo '<i class="fa fa-star star_mark" style="color:'.$color.'"></i>';
        }
        //echo '<i class="fa fa-star star_mark"></i>';
    }

?>
