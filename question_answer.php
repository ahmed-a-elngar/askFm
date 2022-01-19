<?php
session_start();

$_SESSION['active_tab'] = 'Answer the question';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $a_table_name = $user_id . '_answers';
    $a_content = htmlentities(mysqli_real_escape_string($con, $_POST['a_content']));

    $pic_name = $_FILES["answer_pic_"]["name"];
    $pic_tempname = $_FILES["answer_pic_"]["tmp_name"];
    $pic_path = "pics/".$pic_name;

    $uploading_pic_result = move_uploaded_file($pic_tempname, $pic_path);

    if (trim($a_content) != "" or $uploading_pic_result) {

        if ($uploading_pic_result) {
            $inserting_answer = "INSERT INTO $a_table_name(q_content, q_sender, a_content, q_type, q_status, a_pic)
                                        VALUES('$q_content', '$q_sender', '$a_content', '$q_type', '$q_status', '$pic_path')";
        } else {
            $inserting_answer = "INSERT INTO $a_table_name(q_content, q_sender, a_content, q_type, q_status)
                                        VALUES('$q_content', '$q_sender', '$a_content', '$q_type', '$q_status')";
        }

        if (mysqli_query($con, $inserting_answer)) {
            $select = "SELECT MAX(a_id) from $a_table_name";
            $a_id = mysqli_fetch_array(mysqli_query($con, $select))[0];
            updateLikesOrCoinsCount($user_id, "c", "a");
            
            if (notifyFriend($user_id, $a_id, $q_sender)) {
                $delete = "DELETE FROM $q_table_name where q_id = '$q_id'";
                if (mysqli_query($con, $delete)) {
                    deleteNoti($user_id, $q_id, 'q');
                    header('location: profile.php');
                }
            }
        }
    } else {
        echo '
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
            <h2 class="side_heading" dir="<?php echo detectDir($q_content);?>"><?php echo $q_content; ?></h2>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="question_form" enctype="multipart/form-data">
                <textarea name="a_content" id="a_content" placeholder="your answer is ..." class="question_txtArea"></textarea>
                <!--<input type="text" name="s_status" id="s_status" value="private" style="visibility: hidden;">-->
                <div style="display: block; height: 36px; float: left; margin-top: 10px;" title="upload image">
                    <i class="fas fa-image" style="font-size: 36px; color:#b2b2bb"></i>
                </div>
                <input name="answer_pic_" id="answer_pic" type="file" class="file_input photos" accept="image/*" 
                    onchange='document.getElementById("pic_output").style.backgroundImage = "url("+window.URL.createObjectURL(this.files[0])+")", document.getElementById("pic_output").style.display = "block"'>
                <div id="pic_output">
                    <span id="cancel_pic">x</span>
                </div>
                <div style="display: block; margin-top: 10px; float: left; padding:8px 20px; border-left: 1px solid #b2b2bb; margin-left: 20px;">
                    <i class="fab fa-facebook" style="color: #b2b2bb; font-size:20px;" title="share to facebook"></i>
                    <i class="fab fa-twitter" style="color: #b2b2bb; font-size:20px; padding: 0px 10px;" title="share to twitter"></i>
                    <i class="fab fa-vk" style="color: #b2b2bb; font-size:20px;" title="share to vk"></i>
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
    $(document).on('click', '#cancel_pic', function() {
        $('#pic_output').css('display', 'none');
        $('#pic_output').css('background-image', '');
        $('#answer_pic').val('');
    });
    $(document).on('click', '#answer_pic', function() {
        $('#pic_output').css('display', 'none');
        $('#pic_output').css('background-image', '');
        $('#answer_pic').val('');
    });
</script>
<script src="js/functions.js"></script>