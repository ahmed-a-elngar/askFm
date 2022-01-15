<?php
    include('../../connection.php');
    if (isset($_POST["pa_1"])) {
        $id = $_POST["pa_1"];
        $status = $_POST["pa_2"];
        $role = $_POST["pa_3"];
        if ($role == "owner") {
            if ($status == 0) {
                # code...
                $update_q = "UPDATE users SET user_status = '1' WHERE user_id = '$id'";
                mysqli_query($con, $update_q);
            }
            elseif($status == 1)
            {
                $update_q = "UPDATE users SET user_status = '0' WHERE user_id = '$id'";
                mysqli_query($con, $update_q); 
            }
        }
    }

?>