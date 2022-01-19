<?php

session_start();
$_SESSION['active_tab'] = 'home';

$pageTitle = 'ASK fm | Home';
include('init.php');

unset($_SESSION['active_tab']);

#if user is loged in
loged('home');


?>
<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section dark">

        <!--ask box-->
        <div class="ask_box no_margin">

            <div class="ask_box_head">
                <i class="fas fa-hand-peace"></i>
                <h2 class="side_heading grey">Ask people</h2>
                <i class="fas fa-question"></i>
                <h2 class="side_heading">Ask Friend</h2>
                <i class="fa fa-heart"></i>
		<i class="fa fa-heart"></i>
                <h2 class="side_heading grey">Versus</h2>
            </div>
            <form class="question_form">
                <textarea placeholder="What, when, why… ask" class="question_txtArea"></textarea>
                <div class="anonymously_box">
                    <label class="switch">
                        <input type="checkbox" checked="true">
                        <span class="slider round"></span>
                    </label>
                    <span class="anonymously_switch">Ask anonymously</span>
                </div>
                <div style="float: right;">
                    <button class="ask_btn">
                        >
                    </button>
                </div>
            </form>

        </div>

        <!--nav header-->
        <div class="main_container_menu big stick">
            <nav class="dark">
                <a href="#" id="defaultOpen" class="tablinks" onclick="openCity(event, 'Wall')">
                    Wall
                </a>
                <a href="#" class="tablinks" onclick="openCity(event, 'Versus')">
                    Versus
                    <i class="fa fa-circle circle_mark"></i>
                </a>
                <a href="#" class="tablinks" onclick="openCity(event, 'Discover')">
                    Discover
                </a>
            </nav>
        </div>

        <!--Wall tab-->
        <div id="Wall" class="tabcontent">

        </div>

        <!--Versus tab-->
        <div id="Versus" class="tabcontent">
        </div>

        <!--Discover tab-->
        <div id="Discover" class="tabcontent">
            <!--answer-->

        </div>
        <a id="x"></a>
    </div>

    <!--side section-->
    <div class="side_section">

        <?php
            include 'includes/temps/leaderboard_side_section.php';
        ?>

        <div class="sticky">
            <?php include 'includes/temps/solid_side_section.php'; ?>
        </div>
    </div>

</div>

<script src="js/jquery-3.3.1.min.js"></script>
<!--settings navbar js-->
<script>
    a_id = -1;
    change = 1;
    old_val = -1;
    a_owner = -1;
    value = "";

    // wall , versus & discover navbar
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
    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

    // load wall answers , versus & discover
    $(function() {
        $('#Wall').load("includes/funcs/loadWall.php", {
            pa_1: "wall"
        });
    });
    $(function() {
        $('.tablinks').click(function() {

            txt = $(this, "a").text().trim();

            if (txt == "Wall") {
                $('#Wall').load("includes/funcs/loadWall.php", {
                    pa_1: "wall"
                });
            } else if (txt == "Discover") {
                $('#Discover').load("includes/funcs/loadDiscover.php", {
                    pa_1: "Discover"
                });
            } else {
                $('#Versus').load("includes/funcs/loadVersus.php", {
                    pa_1: "Versus"
                });
            }

        });
    });
    // like or dislike answer
    function likeMe(u_id, ans_id) {
        a_id = ans_id
        r_u_id = <?php echo $_SESSION['user_id']; ?>;
        table_name = u_id + '_answers';
        value = u_id + ',' + ans_id;

        l_count = $("[title='Like'][value='" + value + "'] + a").html();
        if (r_u_id != u_id) {
            x = $("[title='Like'][value='" + value + "'] + a").load("includes/funcs/likeOrDislike.php", {
                pa_1: r_u_id,
                pa_2: u_id,
                pa_3: table_name,
                pa_4: a_id,
                pa_5: l_count
            });
        }
        old_val = l_count;
        a_owner = u_id;
    }
    // give reward
    function rewardMe(u_id, ans_id) {
        a_id = ans_id
        r_u_id = <?php echo $_SESSION['user_id']; ?>;
        table_name = u_id + '_answers';
        value = u_id + ',' + ans_id;

        c_count = $("[title='Reward'][value='" + value + "'] + a").html();

        if (r_u_id != u_id) {
            $("[title='Reward'][value='" + value + "'] + a").load("includes/funcs/rewards.php", {
                pa_1: r_u_id,
                pa_2: u_id,
                pa_3: table_name,
                pa_4: a_id,
                pa_5: c_count
            });
        }
    }
    // change like button color
    $(document).on("DOMSubtreeModified", "[title='Like']+ a", function(){
        new_val = $("[title='Like'][value='" + value + "'] + a").html();

        if(new_val != old_val && new_val != "")
        {
            old_color = $("i", "[title='Like'][value='" + value + "']").css("color");
            if(old_color == "rgb(178, 178, 187)")
            {
                color = "#ee1144";
            }
            else if(old_color == "rgb(238, 17, 68)")
            {
                color = "#b2b2bb";
            }
            $("i", "[title='Like'][value='" + value + "']").css("color", color);
            old_val = new_val;
        }

    });
    // change reward button color    
    $(document).on("DOMSubtreeModified", "[title='Reward']+ a", function(){

        new_val = $("[title='Reward'][value='" + value + "'] + a").html();
        if(change  == 2 )
        {
            if(new_val != old_val)
            {
                $("i", "[title='Reward'][value='" + value + "']").css("color", "#e14");
            }
            change = 1;
        }
        else
            change += 1;
    });
    // view versus users
    $(document).on("click", ".versus_users_count", function(){

        values = $(this).attr('value');
        val_arr = values.split(",");

        redirect_link = document.createElement('a');
        redirect_link.target = '_blank';
        redirect_link.href = "view_versus.php?user_id_=" + val_arr[0] + "&v_id_=" + val_arr[1];
        redirect_link.click();

    });

    // versus 
    $(document).on("click", "#Versus [title='Like']", function(){

        r_u_id = <?php echo $_SESSION["user_id"]; ?>;
        u_id_v_id_choice = $(this).val();
        u_id = u_id_v_id_choice.split(",")[0];
        table_name = u_id_v_id_choice.split(",")[0] + '_versus';
        v_id_choice = u_id_v_id_choice.split(",")[1] + ',' + u_id_v_id_choice.split(",")[2];
        if (r_u_id != u_id) {
            $("i", "[title='Like'][value='" + u_id_v_id_choice + "']").load("includes/funcs/versus_choice.php", {
                pa_1: r_u_id,
                pa_2: table_name,
                pa_3: v_id_choice
            });
            //location.href="includes/funcs/reload.php";
        }
    });

    // view versus users
    $(document).on("click", "#Versus [title='Like']", function(){
        u_id_v_id_choice = $(this).val();
        u_id = u_id_v_id_choice.split(",")[0];
        table_name = u_id_v_id_choice.split(",")[0] + '_versus';

        $(this).parentsUntil('#Versus').load("includes/funcs/load_versus.php", {
            pa_1: table_name,
            pa_2: u_id_v_id_choice
        });
    });

    // remove suggestion
    $(document).on('click', '#remove_sug', function(){
        $(this).parentsUntil('.recommend-cont').remove();
    });
    // follow suggestion
    $(document).on('click', '#add_sug', function(){
        t_id = ($(this).attr('value'));
        $(this).load('includes/funcs/followMe.php', {
            pa_1: t_id
        });
        $(this).parentsUntil('.recommend-cont').remove();
    });
    // close suggestion
    $(document).on('click', '#close_sug', function(){
        $(this).parentsUntil('#Wall').remove();
    });

</script>

<script src="js/functions.js"></script>
</body>

</html>