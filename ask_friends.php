<?php
    session_start();

    $_SESSION['active_tab'] = 'ask friends';

    if (!isset($_SESSION['user_name'])) {
        # code...
        header('location: sign in.php');
        exit();
    }
    $pageTitle = "Ask Friends";
    include('init.php');
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        # code...
        $q_content = htmlentities(mysqli_real_escape_string($con, $_POST['q_content']));
        $s_status = htmlentities(mysqli_real_escape_string($con, $_POST['s_status']));
        $targets = htmlentities(mysqli_real_escape_string($con, $_POST['targets']));
        if (trim($q_content) != "" & trim($targets) != "") {
            if (sendToTargets($q_content, $targets, $user_id, $user_name, $s_status)) {
                echo'
                    <script>console.log( " back ..." );</script>
                ';
                header('location: profile.php');
            }
        }
        if (trim($q_content) == "") {
            echo'
                <script>console.log( " please enter question content ..." );</script>
            '; 
        }
        if (trim($targets) == "") {
            echo'
                <script>console.log( " please choose friends who will receive your question ..." );</script>
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
        <?php
            $f_table_name = $_SESSION['user_id'] . "_friends";
            $frnds_query = "SELECT * from $f_table_name";
            $run_query = mysqli_query($con, $frnds_query);
        ?>
        <!--ask box-->
        <div class="ask_box light">
            <h2 class="side_heading">Ask Friends</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="question_form ">
                <textarea name="q_content" id="q_content" placeholder="Ask your friends ..." class="question_txtArea"><?php if (isset($_GET['q_content'])) {echo $_GET['q_content'];}?></textarea>
                <div class="anonymously_box">
                    <label class="switch">
                    <input name="status" id="status" type="checkbox" checked="TRUE">
                        <span class="slider round"></span>
                    </label>
                    <span class="anonymously_switch">Ask anonymously</span>
                    <input type="text" name="s_status" id="s_status" value="private" style="visibility: hidden;">
                </div>
                <!--search box-->
                <div class="search_box">
                    <h3 id="targets_count" >Select friends (<span>0</span>/50)</h3>
                    <input type="search" placeholder="Search people by keywords">
                    <i class="fa fa-search search_mark"></i>
                </div>
                <div class="search_result">
                <?php
                        while($frnd = mysqli_fetch_array($run_query)){
                            $frnd_id = $frnd['f_id'];
                            $user_query = "SELECT * from users where user_id = '$frnd_id'";
                            # retrive friend info.
                            $run_u_query = mysqli_query($con, $user_query);
                            $frnd_info = mysqli_fetch_array($run_u_query);
                            #<!--Your Friends section-->
                            echo
                            '<div class="friend_section big" style="border: none;" id="'.$frnd_id.'">
                                <div class="friend_pics">
                                    <a>
                                        <img src="'.$frnd_info["user_pic"].'">
                                    </a>
                                </div>
                                <div >
                                    <a>
                                        <span class="friend_name">'.$frnd_info["user_full_name"].'</span>
                                        <span class="friend_user_name">@'.$frnd_info["user_name"].'</span>
                                    </a>
                                </div>
                            </div>';
                        }
                    ?>
                </div>
                <input type="text" name="targets" id="targets" style="visibility: hidden;">
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
    $(document).on('click', '#status', function() {
            val = document.getElementById('s_status').value;
            if (val == "private") {
                document.getElementById('s_status').value = "public";
            } else {
                document.getElementById('s_status').value = "private";
            }
    });
    // highlight friend if selected & change count
    $(function() {
        $(".friend_section").click(function() {
            old_count = $("#targets_count span").text();
            new_count = parseInt(old_count);
            removed = false;
            if (this.classList.contains('active_target')) {
                this.classList.remove('active_target');
                removed = true;
                new_count -= 1;
            }
            else if (! removed) {
                this.classList.add('active_target');
                new_count += 1;
            }
            $("#targets_count span").text(new_count);
        });
    });
    // set targets value before submit form
    $(function(){
        $('[type="submit"]').click(function(){
            var targets = document.getElementsByClassName("active_target");
            var i = 0, targetsValue = "";
            for (i = 0; i < targets.length; i++) {
                var target = targets[i];
                var val = $(target).attr('id');
                if(! val == "")
                    targetsValue += val + ",";
            }
            $('#targets').val(targetsValue);
        });
    });
</script>
<?php
    include('includes/temps/footer.php');
?>