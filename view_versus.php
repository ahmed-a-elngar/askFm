<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    # code...
    $_SESSION["destination_"] = "create_versus";
    header('location: sign in.php');
    exit();
}
$pageTitle = "View Versus";
include('init.php');
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

$r_u_id = $_GET["user_id_"];
$v_id = $_GET["v_id_"];
$v_table_name = $r_u_id . '_versus';
$select = "SELECT * FROM $v_table_name where v_id = '$v_id'";
$select_q = mysqli_query($con, $select);
$v_info = mysqli_fetch_array($select_q);

$select_u = "SELECT * FROM users WHERE user_id = '$r_u_id'";
$query = mysqli_query($con, $select_u);
$u_info = mysqli_fetch_array($query);
$_SESSION['reload'] = "view_versus.php?v_id_=" . $v_id . "&user_id_=" . $r_u_id;

if ($v_info == null or $u_info == null) {
    header('location: unReachable.php');
    exit();
}
?>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section transparent">
        <div class="versus_cont">
            <h1 dir="<?php echo detectDir($v_info['v_head']);?>"><?php echo $v_info['v_head']; ?></h1>
            <?php
            echo
            '<div class="friend_section big" style="border: none;">
                    <div class="friend_pics">
                        <a href="profile.php?user_name_=' . $u_info["user_name"] . '">
                            <img src="' . $u_info["user_pic"] . '">';
            if($u_info['user_mood'] != null and $u_info['user_mood'] != 0)
            {
                echo'
                    <div class="user_mood">
                        <img src="pics/moods/'.$u_info['user_mood'].'.gif">
                    </div>
                ';
            } 
            echo'       
                </a>
                    </div>
                    <div >
                        <a href="profile.php?user_name_=' . $u_info["user_name"] . '">
                            <span class="friend_name">' . $u_info["user_full_name"] . '</span>
                            <div class="time_container">
                                <a>' . printTime($v_info["v_date"]) . '</a>
                            </div>
                        </a>
                    </div>
                </div>
            ';
            include 'includes/funcs/load_versus.php';
            ?>
        </div>
        <div class="versus_users_cont" id="scroll_2">
            <?php include 'includes/funcs/versus_users.php'; ?>
        </div>
    </div>

    <!--side section-->
    <div class="side_section">
        <?php
            include 'includes/temps/friends_side_section.php';
        ?>
        <div class="sticky">
            <?php include 'includes/temps/solid_side_section.php'; ?>
        </div>
    </div>

</div>

<!--settings navbar js-->
<script src="js/jquery-3.3.1.min.js"></script>
<script>
    // to chooce a versus pic
    $(function() {
        $("[title='Like']").click(function() {
            r_u_id = <?php echo $_SESSION["user_id"]; ?>;
            u_id = <?php echo $r_u_id; ?>;
            table_name = "<?php echo $v_table_name; ?>";
            v_id = $(this).val();
            v_id_choice = v_id.split(",")[1] + ',' + v_id.split(",")[2];
            if (r_u_id != u_id) {
                $("i", "[title='Like'][value='" + v_id + "']").load("includes/funcs/versus_choice.php", {
                    pa_1: r_u_id,
                    pa_2: table_name,
                    pa_3: v_id_choice
                });
                //location.href = "includes/funcs/reload.php";
            }
        });
    });

    // view versus users
    $(document).on("click", "[title='Like']", function() {

        u_id_v_id_choice = $(this).val();
        u_id = u_id_v_id_choice.split(",")[0];
        table_name = u_id_v_id_choice.split(",")[0] + '_versus';

        $(this).parentsUntil('.main_section').load("includes/funcs/load_versus.php", {
            pa_1: table_name,
            pa_2: u_id_v_id_choice
        });
        
    });

    // display versus users
    $(document).on('click', '.versus_users_count', function(){
        $('.versus_users_cont').css("display", "block");
    });

    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
        //document.getElementById("scroll").scrollIntoView();
        //location.href = "#scroll";
        //$('.main_container_menu').scrollTop(200);
    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>
<?php
include('includes/temps/footer.php');
?>