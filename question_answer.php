<?php
    session_start();
    if (!isset($_SESSION['user_name'])) {
        # code...
        header('location: sign in.php');
        exit();
    }
    $pageTitle = "Answer the question";
    include('init.php');
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];

    if (isset($_GET["q_id_"])) {
        $_SESSION["q_id"] = $_GET["q_id_"];
    }
    $q_id = $_SESSION["q_id"];
    $q_table_name = $user_id . '_questions';
    $question_selecting = "SELECT * FROM $q_table_name WHERE q_id = '$q_id'";
    $question_selecting_q = mysqli_query($con, $question_selecting);
    $question_info = mysqli_fetch_array($question_selecting_q);
    $q_content = $question_info['q_content'];
    $q_sender = $question_info['q_sender'];
    $q_type = $question_info['q_type'];
    $q_status = $question_info['q_status'];
    
    if (is_null($question_info)) {
        header('location: unReachable.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $a_table_name = $user_id . '_answers';
        $a_content = htmlentities(mysqli_real_escape_string($con, $_POST['a_content']));
        if (trim($a_content) != "") {
            $inserting_answer = "INSERT INTO $a_table_name(q_content, q_sender, a_content, q_type, q_status)
                                              VALUES('$q_content', '$q_sender', '$a_content', '$q_type', '$q_status')";
            if (mysqli_query($con, $inserting_answer)) {
                $select = "SELECT MAX(a_id) from $a_table_name";
                $a_id = mysqli_fetch_array(mysqli_query($con, $select))[0];
                updateLikesOrCoinsCount($user_id, "c", "a");

                if (notifyFriend($user_id, $a_id, $q_sender))
                {
                    $delete = "DELETE FROM $q_table_name where q_id = '$q_id'";
                    if (mysqli_query($con, $delete))
                    {
                        deleteNoti($user_id, $q_id, 'q');
                        header('location: profile.php');
                    }
                }
            }
        }
        else {
            echo'
                <script>console.log( "please enter an answer ..." );</script>
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
            <h2 class="side_heading"><?php echo $q_content; ?></h2>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="question_form">
                <textarea name="a_content" id="a_content" placeholder="your answer is ..." class="question_txtArea"></textarea>
                    <input type="text" name="s_status" id="s_status" value="private" style="visibility: hidden;">
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
<?php
    include('includes/temps/footer.php');
?>