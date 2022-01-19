<?php
session_start();

$_SESSION['active_tab'] = 'ask people';

if (!isset($_SESSION['user_name'])) {
    # code...
    header('location: sign in.php');
    exit();
}
$pageTitle = "Ask People";
include('init.php');
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # code...
    $q_content = htmlentities(mysqli_real_escape_string($con, $_POST['q_content']));
    $s_status = htmlentities(mysqli_real_escape_string($con, $_POST['s_status']));
    if (trim($q_content) != "") {
        if (sendToTargets($q_content, " ", $user_id, $user_name, $s_status)) {
            echo '
                    <script>console.log( " back ..." );</script>
                ';
            header('location: profile.php');
        }
    } else {
        echo '
                <script>alert( "please enter a question ..." );</script>
            ';
    }
}

?>

<!--background gradient-->
<div class="backgroundWrap withImage">
    <img alt="">
    <div class="gradient"></div>
</div>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section transparent details">
        <!--ask box-->
        <div class="ask_box light">
            <h2 class="side_heading">Ask People</h2>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="question_form">
                <textarea name="q_content" id="q_content" placeholder="Ask people nearby ..." class="question_txtArea"></textarea>
                <div class="anonymously_box">
                    <label class="switch">
                        <input name="status" id="status" type="checkbox" checked="TRUE">
                        <span class="slider round"></span>
                    </label>
                    <span class="anonymously_switch">Ask anonymously</span>
                    <input type="text" name="s_status" id="s_status" value="private" style="visibility: hidden;">
                </div>
                <div style="float: right;">
                    <button type="submit" class="ask_btn">
                        >
                    </button>
                </div>
            </form>
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
    // set q sender status whether private | public
    $(function() {
        $('#status').click(function() {
            val = document.getElementById('status').checked;
            if (val) {
                document.getElementById('s_status').value = "private";
            } else {
                document.getElementById('s_status').value = "public";
            }
            console.log(val);
        });
    });
</script>
<?php
include('includes/temps/footer.php');
?>