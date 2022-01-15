    <!--Top menu -->
    <div class="top_menu">
        <section>
            <img src="pics/logo-red-9af653502f0b8f01022ea1aa0ab49f00b41db433c00fee35a9848e5a87a0dff9.png" style="width: 72px;padding-top:6px;" alt="">

            <a title="create" class="add">+
            </a>
            <div id="myDropdown2" class="dropdown-content2">
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
                        <i class="fa fa-heart"></i>
                        <i class="fa fa-heart"></i>
                    </div>
                    <div>
                        Versus
                        <span>Create Poll</span>
                    </div>
                </a>
            </div>

            <a href="leaderboard.php" style="font-size: 12px; color:#fff; text-decoration: none; float: right;">
                <div style="background-color: rgba(255,255,255,0.2); box-shadow: 0 1px 2px 0 rgb(0 0 0 / 50%); padding: 2px 0px 2px 3px; border-radius: 15px 4px 4px 15px;">
                    <img src="pics/icons/coin.png" style="width: 24px; height: 24px; vertical-align: bottom;">
                    <span style="vertical-align: super; padding:0 6px; font-weight: bold;">9 999+</span>
                </div>
            </a>
            <ul>
                <li><a href="home.php"><img src="pics/icons/home.png"></a></li>
                <li><a href="questions.php"><img src="pics/icons/questions.png"></a></li>
                <li><a href="profile.php"><img id="user_pic" src="<?php echo $_SESSION['user_pic']; ?>"></a></li>
                <li><a href="friends.php"><img src="pics/icons/friends.png"></a></li>
                <li><a href="notifications.php">
                    <img src="pics/icons/notifications.png">
                    <span class="noti_count"></span>
                </a></li>
                <li class="dropdown">
                    <img onclick="myFunction()" class="dropbtn" src="pics/icons/settings.png">
                    <div id="myDropdown" class="dropdown-content">
                        <a href="settings.php">settings</a>
                        <a href="log_out.php" title="log out">log out</a>
                    </div>
                </li>
            </ul>
        </section>
    </div>