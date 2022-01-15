<?php
    session_start();
    if (!isset($_SESSION['user_name'])) {
        # code...
        header('location: sign in.php');
        exit();
    }
    $pageTitle = "Ask Friends";
    include('init.php');
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
    if ($_SESSION["target_id"] == "") {
        $_SESSION["target_id"] = $_GET["user_id_"];
    }
    if (isset($_GET["user_id_"])) {
        $_SESSION["target_id"] = $_GET["user_id_"];
    }
    $target_id = $_SESSION["target_id"];

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        # code...
        $q_content = htmlentities(mysqli_real_escape_string($con, $_POST['q_content']));
        $s_status = htmlentities(mysqli_real_escape_string($con, $_POST['s_status']));
        if (trim($q_content) != "" & trim($target_id) != "") {
            if (sendToTargets($q_content, $target_id, $user_id, $user_name, $s_status)) {
                $q_table_name = $target_id . '_questions';
                $select_q_id  = "SELECT MAX(q_id) from $q_table_name";
                $q_id = mysqli_fetch_array(mysqli_query($con, $select_q_id))[0];
                notifyMe("q", $q_id, $target_id);
                header('location: profile.php');
            }
        }
        if (trim($q_content) == "") {
            echo'
                <script>console.log( " please enter question content ..." );</script>
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
            <?php
                $select = "SELECT user_full_name from users where user_id = '$target_id'";
                $user_full_name = mysqli_fetch_array(mysqli_query($con, $select))[0];
                echo'
                    <h2 class="side_heading">Ask '.$user_full_name.'</h2>
                ';
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="question_form ">
                <textarea name="q_content" id="q_content" placeholder="Ask your friends ..." class="question_txtArea"></textarea>
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

        <h1 class="side_heading">Your Friends</h1>

        <!--Your Friends section-->
        <div class="friend_section" style="border: none;">
            <div class="friend_pics">
                <a href="#">
                    <img src="pics/rTv3Sea.jpg">
                </a>
            </div>
            <div >
                <a href="#">
                    <span class="friend_name">User Name</span>
                    <span class="friend_user_name">User Name</span>
                </a>
            </div>
            <div class="ask_friend_btn">
                <a href="#">Ask ></a>
            </div>
        </div>
        <!--Your Friends section-->
        <div class="friend_section">
            <div class="friend_pics">
                <a href="#">
                    <img src="pics/solitary.png">
                </a>
            </div>
            <div >
                <a href="#">
                    <span class="friend_name">User Name</span>
                    <span class="friend_user_name">User Name</span>
                </a>
            </div>
            <div class="ask_friend_btn">
                <a href="#">Ask ></a>
            </div>
        </div>
        <!--Your Friends section-->
        <div class="friend_section">
            <div class="friend_pics">
                <a href="#">
                    <img src="pics/index.jpg">
                </a>
            </div>
            <div >
                <a href="#">
                    <span class="friend_name">User Name</span>
                    <span class="friend_user_name">User Name</span>
                </a>
            </div>
            <div class="ask_friend_btn">
                <a href="#">Ask ></a>
            </div>
        </div>

        <!--see all friends "link to friends page"-->
        <p class="see_all">
            <a href="#">See all friends</a>
        </p>

        <div class="sticky">
            <?php include 'includes/temps/solid_side_section.php'; ?>
        </div>
    </div>

</div>

<!--settings navbar js-->
<script src="js/jquery-3.3.1.min.js"></script>
<script>
    // set q sender status whether private | public
    $(function(){
        $('#status').click(function(){
            val = document.getElementById('status').checked;
            if(val)
            {
                document.getElementById('s_status').value = "private";
            }
            else{
                document.getElementById('s_status').value = "public";
            }
            console.log(val);
        });
    });
</script>
<?php
    include('includes/temps/footer.php');
?>