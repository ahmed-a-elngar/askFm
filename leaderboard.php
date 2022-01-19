<?php
    session_start();
    if (!isset($_SESSION['user_name'])) {
        # code...
        $_SESSION["destination_"] = "leaderboard";
        header('location: sign in.php');
        exit();
    }
    $pageTitle = "Wallet";
    include('init.php');
?>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section dark">
        <h1>Weekly 🇪🇬 Leaderboard</h1>
        <p>
            Leaderboard is based on user coin earnings for the last 7 days. 
            Keep earning and get to the top.
        </p>
    <?php
        $rank = 1;
        $run_query = WeeklyLeaderboard(100);
        while ($users = mysqli_fetch_array($run_query)) {
            // <!--user coins info-->
            echo '
            <a href="profile.php?user_name_='.$users['user_name'].'" class="user_coins_container"> 
                <span class="order">'.$rank.'</span>
                <img src="'.$users['user_pic'].'" alt="" class="user_pic">
                <div class="info">
                    <span class="user_name">'.$users['user_full_name'].'</span>
                    <div class="likes_count">
                        <i class="fa fa-heart"></i>
                        <span>'.number_format($users['user_l_count'], 0, '.', ' ').'</span>
                    </div>
                </div>
                <div class="coins_info">
                    <div>
                        <img src="pics/icons/coin.png" alt="">
                        <span>+'.number_format($users['user_weekly_c_count'], 0, '.', ' ').'</span>
                    </div>
                    <p>+'.number_format($users['user_today_c_count'], 0, '.', ' ').' today</p>
                </div>
            </a>';
            $rank += 1;
        }
    ?>
        <div class="moreMore">
            <h1>More friends - more fun!</h1>
            <a href="friends.php" title="Add friends" class="btn pri_btn">Add friends</a>
        </div>
    </div>

    <!--side section-->
    <div class="side_section">

        <!-- wallet-->
        <div class="user_wallet_section">
            <!--header-->
            <div class="user_wallet_header">
                <img src="<?php echo $_SESSION["user_pic"];?>" alt="">
                <div>
                    <p>In your wallet:</p>
                    <p class="coins_count">
                        <span><?Php echo number_format($_SESSION["user_c_count"], 0, '.', ' ') ;?></span>
                        coins
                    </p>
                </div>
            </div>
            <!--footer-->
            <div class="user_wallet_footer">
                <p>Read more about ASKfm coins:</p>
                <a href="#">How can I get coins?</a>
                <a href="#">Why were my coins removed?</a>
                <a href="#">Weekly leaderboard</a>
                <a href="#">Answer rewards</a>
                <a href="#">Commission</a>
            </div>
        </div>
        <!--market-->
        <a href="#" class="market">
            <img src="pics/icons/market.png" alt="">
            <div>
                <p>Market</p>
                <p class="offers_count">36 active offers</p>
            </div>
            <i class="fa fa-arrow-right"></i>
        </a>
        <?php include 'includes/temps/solid_side_section.php'; ?>           
    </div>

</div>

<?php
    include('includes/temps/footer.php');
?>