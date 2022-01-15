<?php
    include 'connection.php';

    echo'
        <h2 class="light">🇪🇬 Leaderboard</h2>
    ';
    $rank = 1;
    $run_query = WeeklyLeaderboard(5);
    while ($users = mysqli_fetch_array($run_query)) {
        // <!--user coins info-->
        echo '
        <a href="profile.php?user_name_='.$users['user_name'].'" class="user_coins_container small"> 
            <span class="order">'.$rank.'</span>
            <img src="'.$users['user_pic'].'" alt="" class="user_pic">
            <div class="info">
                <span class="user_name">'.$users['user_full_name'].'</span>
                <div class="likes_count">
                    <i class="fa fa-heart"></i>
                    <span>'.$users['user_l_count'].'</span>
                </div>
            </div>
            <div class="coins_info">
                <div>
                    <img src="pics/icons/coin.png" alt="">
                    <span>+'.$users['user_weekly_c_count'].'</span>
                </div>
                <p>+'.$users['user_today_c_count'].' today</p>
            </div>
        </a>';
        $rank += 1;
    }
?>




<!--see all "link to Leaderboard page"-->
<p class="see_all narrow">
    <a href="#">See all</a>
</p>

<!--market-->
<a href="#" class="market">
    <img src="pics/icons/market.png" alt="">
    <div>
        <p>Market</p>
        <p class="offers_count">36 active offers</p>
    </div>
    <i class="fa fa-arrow-right"></i>
</a>