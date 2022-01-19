<?php
    /*if (! isset($_SESSION['active_tab'])) {
        $_SESSION['active_tab'] = "un set";
    }*/
?>
<!--Top menu -->

    <div class="top_menu">
        <section>
            <img src="pics/logo-red-9af653502f0b8f01022ea1aa0ab49f00b41db433c00fee35a9848e5a87a0dff9.png" style="width: 72px;padding-top:6px;" alt="">

            <a title="create" class="add">+
            </a>
            <div id="myDropdown2" class="dropdown-content2" style="width: 200px;">
                <a href="ask_people.php">
                    <div> 
                        <i class="fas fa-hand-peace"></i>
                    </div>
                    <div>
                        ask people
                        <span>Shoutout</span>
                    </div>
                </a>
                <a href="ask_friends.php">
                    <div> 
                        <i class="fas fa-question"></i>
                    </div>
                    <div >
                        ask friend
                        <span>personal question</span>
                    </div>
                </a>
                <a href="create_versus.php">
                    <div> 
                        <i class="fa fa-heart" style="color: #e14;"></i>
                        <i class="fa fa-heart" style="color: #e14;"></i>
                    </div>
                    <div>
                        Versus
                        <span>Create Poll</span>
                    </div>
                </a>
            </div>

            <a href="leaderboard.php" style="font-size: 12px; color:#fff; text-decoration: none; float: right;">
                <div style="background-color: rgba(255,255,255,0.2); box-shadow: 0 1px 2px 0 rgb(0 0 0 / 50%); padding: 2px 0px 2px 3px; border-radius: 15px 4px 4px 15px; min-width: 47px;">
                    <img src="pics/icons/coin.png" style="width: 24px; height: 24px; vertical-align: bottom;">
                    <span style="vertical-align: super; padding:0 6px; font-weight: bold;"><?php echo  number_format($_SESSION['user_c_count'], 0, '.', ' ');?></span>
                </div>
            </a>
            <ul>    
                <li class="nav_icon <?php echo $_SESSION['active_tab'] == 'home'? 'active':'';?>"><a href="home.php"><i class="fas fa-home"></i></a></li>
                <li class="nav_icon <?php echo $_SESSION['active_tab'] == 'questions'? 'active':'';?>"><a href="questions.php"><i class="fas fa-question-circle"></i></a></li>
                <li class="nav_icon"><a href="profile.php"><img id="user_pic" src="<?php echo $_SESSION['user_pic']; ?>"></a></li>
                <li class="nav_icon <?php echo $_SESSION['active_tab'] == 'friends'? 'active':'';?>"><a href="friends.php"><i class="fas fa-user-friends"></i></a></li>
                <li class="nav_icon <?php echo $_SESSION['active_tab'] == 'notifications'? 'active':'';?>"><a href="notifications.php">
                    <i class="fas fa-bolt"></i>
                    <span class="noti_count"></span>
                </a></li>
                <li class="dropdown nav_icon <?php echo $_SESSION['active_tab'] == 'settings'? 'active':'';?>">
                    <i class="fas fa-cog dropbtn" onclick="myFunction()"></i>
                    <div id="myDropdown" class="dropdown-content">
                        <a href="settings.php">settings</a>
                        <a href="log_out.php" title="log out">log out</a>
                    </div>
                </li>
            </ul>
        </section>
    </div>