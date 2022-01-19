<?php
    session_start();

    $_SESSION['active_tab'] = 'create versus';

    if (!isset($_SESSION['user_name'])) {
        # code...
        $_SESSION["destination_"] = "create_versus";
        header('location: sign in.php');
        exit();
    }
    $pageTitle = "Create Versus";
    include('init.php');
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_FILES["input_1"]) & isset($_FILES["input_2"])) {
            # code...
            $v_head = htmlentities(mysqli_real_escape_string($con, $_POST['v_head']));
            $pic_1_name = $_FILES["input_1"]["name"];
            $pic_1_tempname = $_FILES["input_1"]["tmp_name"]; 
            $pic_1_path = "pics/".$pic_1_name;  
            $pic_2_name = $_FILES["input_2"]["name"];
            $pic_2_tempname = $_FILES["input_2"]["tmp_name"]; 
            $pic_2_path = "pics/".$pic_2_name;
            $uploading_pic_1 = move_uploaded_file($pic_1_tempname, $pic_1_path);
            $uploading_pic_2 = move_uploaded_file($pic_2_tempname, $pic_2_path);
            $v_table_name = $user_id . '_versus';

            if ($uploading_pic_1 & $uploading_pic_2) {

                $inserting = "INSERT INTO $v_table_name (v_head, v_pic_1, v_pic_2)
                                           VALUES('$v_head', '$pic_1_path', '$pic_2_path')";
                if (mysqli_query($con, $inserting)) {
                    $select = "SELECT MAX(v_id) from $v_table_name";
                    $v_id = mysqli_fetch_array(mysqli_query($con, $select))[0];
                    notifyFollowers($user_id, $v_id);
                    header('location: profile.php');
                }
                else {
                    echo'
                        <script>console.log("error, try again");</script>
                    ';
                }
            }
        }

    }
?>

<!--main container-->
<div class="main_container">
    
    <!--main section-->
    <div class="main_section transparent">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="POST" class="versus_cont">
            <h1>Create photo poll ❤❤</h1>
            <textarea name="v_head" id="q_content" placeholder="What, when, why… " class="question_txtArea"></textarea>
            <p>300</p>
            <div class="versus_pics">
                <a id="choice_1" title="click to upload">
                        <img src="pics/choice 1.png" alt="choice 1" id="versus_1">
                </a>
                <input name="input_1" type="file" id="input_1" accept="image/*" onchange='document.getElementById("versus_1").src = window.URL.createObjectURL(this.files[0])' style="visibility: hidden; position: absolute; width: 0px;">
                <span class="vs">VS</span>
                <a id="choice_2" title="click to upload">
                    <img src="pics/choice 2.png" alt="choice 2"  id="versus_2">
                </a>
                <input name="input_2" type="file" id="input_2" accept="image/*" onchange='document.getElementById("versus_2").src = window.URL.createObjectURL(this.files[0])' style="visibility: hidden; position: absolute; width: 0px; margin-left: 233px;">
            </div>
            <div class="disclaimer">
                It’s not recommended to make a poll with photos of your friends without their consent. 
                We will remove such a poll, if it’s reported. 
                <a href="#">Community Guidelines.</a>
            </div>
            <div class="share_on">
                <a href="#" title="share on facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" title="share on twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" title="share on instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
            <div style="float: right;">
                <button type="submit" class="ask_btn">
                    >
                </button>
            </div>
        </form>
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
    // fire file input when photo clicked
    $(function(){
        $('#choice_1').click(function(){
            $('#input_1').click();
        });
    });
    $(function(){
        $('#choice_2').click(function(){
            $('#input_2').click();
        });
    });
</script>
<?php
    include('includes/temps/footer.php');
?>