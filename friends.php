<?php
session_start();

$pageTitle = "Friends";
include('init.php');

#if user is loged in
loged('friends');

?>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section">

        <?php
        $user_name = $_SESSION['user_name'];
        $f_table_name = $_SESSION['user_id'] . "_friends";
        $frnds_query = "SELECT * from $f_table_name ORDER BY fav_or_not DESC,  f_id DESC";
        $run_query = mysqli_query($con, $frnds_query);
        ?>

        <!--nav header-->
        <div class="main_container_menu">
            <nav>
                <a href="#" id="defaultOpen" class="tablinks" onclick="openCity(event, 'Friends')">
                    Friends
                </a>
                <a href="#" class="tablinks" onclick="openCity(event, 'Interests')">
                    Interests
                </a>
                <a href="#" class="tablinks" onclick="openCity(event, 'Social')">
                    Social
                </a>
            </nav>
        </div>

        <!--Friends tab-->
        <div id="Friends" class="tabcontent">

            <form id="search_users" class="search_form">
                <!--search box-->
                <input type="search" placeholder="Search people by keywords">
                <i class="fa fa-search search_mark"></i>

                <!--friends & search output-->
                <div class="result_1">
                    <h1 class="side_heading dark">Your friends</h1>
                    <?php
                    while ($frnd = mysqli_fetch_array($run_query)) {
                        $frnd_id = $frnd['f_id'];
                        $user_query = "SELECT * from users where user_id = '$frnd_id'";
                        # retrive friend info.
                        $run_u_query = mysqli_query($con, $user_query);
                        $frnd_info = mysqli_fetch_array($run_u_query);
                        #<!--Your Friends section-->
                        echo
                        '<div class="friend_section big" style="border: none;">
                                    <div class="friend_pics">
                                        <a href="profile.php?user_name_=' . $frnd_info["user_name"] . '">
                                            <img src="' . $frnd_info["user_pic"] . '">
                                        </a>
                                    </div>
                                    <div >
                                        <a href="profile.php?user_name_=' . $frnd_info["user_name"] . '">
                                            <span class="friend_name">' . $frnd_info["user_full_name"] . '</span>
                                            <span class="friend_user_name">@' . $frnd_info["user_name"] . '</span>
                                        </a>
                                    </div>
                                    <div class="ask_friend_btn">
                                        <a href="ask_friend.php?user_id_=' . $frnd_info["user_id"] . '#">Ask ></a>
                                    </div>
                                    <button class="non_btn" value="' . $frnd_id . '">';
                        if (isFav($f_table_name, $frnd_id)) {
                            echo '<i class="fa fa-star star_mark active"></i>';
                        } else {
                            echo '<i class="fa fa-star star_mark"></i>';
                        }
                        echo '</button>
                                </div>';
                    }
                    ?>
                </div>
            </form>

        </div>

        <!--Interests tab-->
        <div id="Interests" class="tabcontent">

            <form id="search_interests" class="search_form">
                <!--search box-->
                <input type="search" placeholder="Search people by interests">
                <i class="fa fa-search search_mark"></i>

                <div class="result_2"></div>
            </form>

        </div>

        <!--Social tab-->
        <div id="Social" class="tabcontent">

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

<script src="js/jquery-3.3.1.min.js"></script>
<script>
    // settings navbar js
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
    // search users by name
    $(function() {
        $('#search_users input[type="search"]').on("keyup input", function() {
            /* Get input value on change */
            var inputVal = $(this).val();
            u_id = <?php echo $_SESSION['user_id']; ?>;
            $('.result_1').load('includes/funcs/loadSearchReasult.php', {
                pa_1: inputVal,
                pa_2: u_id
            });
        });
    });
    // search users by interests
    $(function() {
        $('#search_interests input[type="search"]').on("keyup input", function() {
            /* Get input value on change */
            var inputVal = $(this).val();
            u_id = <?php echo $_SESSION['user_id']; ?>;
            $('.result_2').load('includes/funcs/searchInterestsResult.php', {
                pa_1: inputVal,
                pa_2: u_id
            });
        });
    });
    // add to fav or remove
    $(function() {
        $(".non_btn").click(function() {
            f_id = $(this).val();
            table_name = "<?php echo $f_table_name; ?>";
            color = $(".non_btn[value=" + f_id + "] i.star_mark").css("color") == "rgb(213, 213, 221)" ? "#ffff00" : "#d5d5dd";
            $(this).load('includes/funcs/favOrNot.php', {
                pa_1: f_id,
                pa_2: table_name,
                pa_3: color
            });
        });
    });

    function MakeFavOrNot(f_id) {
        elm = document.getElementsByClassName('non_btn');
        table_name = "<?php echo $f_table_name; ?>";
        color = $(".non_btn[value=" + f_id + "] i.star_mark").css("color") == "rgb(213, 213, 221)" ? "#ffff00" : "#d5d5dd";
        $(elm).load('includes/funcs/favOrNot.php', {
            pa_1: f_id,
            pa_2: table_name,
            pa_3: color
        });
    }
    // add user to friends
    function follow(f_id) {
        u_id = <?php echo $_SESSION['user_id']; ?>;
        $(".ask_friend_btn[value=" + f_id + "]").load("includes/funcs/follow.php", {
            pa_1: u_id,
            pa_2: f_id
        });
        $(".ask_friend_btn[value=" + f_id + "]").after("<button class='non_btn' onclick='MakeFavOrNot(" + f_id + ")' value='" + f_id + "'><i class='fa fa-star star_mark'></i></button>");
    }

    // to load notifications
    notiCount = document.querySelector(".noti_count");

    $(function() {
        setInterval(() => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../user/load_notifications.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        if (data.length > 0) {
                            notiCount.style.display = "block";
                            notiCount.innerHTML = data;
                        }
                    }
                }
            }
            xhr.send();
        }, 1000);
    });
</script>
<script src="js/functions.js"></script>
</body>

</html>