<?php
    session_start();

    $_SESSION['active_tab'] = 'settings';

    $pageTitle = "Settings";
    include('init.php');

    unset($_SESSION['active_tab']);
    #if user is loged in
    loged('settings');

    // open tab
    if(isset($_GET['target_']))
    {

        $_SESSION['target_tab'] = $_GET['target_'];

        if(! (strtolower($_SESSION['target_tab']) == "account" or strtolower($_SESSION['target_tab']) == "notifications" or strtolower($_SESSION['target_tab']) == "social"))
        {
            $_SESSION['target_tab'] = "profile";
        }
    }
    else
    {
        $_SESSION['target_tab'] = "profile";
    }

    $user_id = $_SESSION["user_id"];
    $select_user_info = "SELECT * FROM users where user_id = '$user_id'";
    $run_select_user_info = mysqli_query($con, $select_user_info);
    $user_info = mysqli_fetch_array($run_select_user_info);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        
        # code...
        $user_full_name = htmlentities(mysqli_real_escape_string($con, $_POST['u_full_name']));
        $user_location = htmlentities(mysqli_real_escape_string($con, $_POST['u_location']));
        $user_bio = htmlentities(mysqli_real_escape_string($con, $_POST['u_bio']));
        $user_web = htmlentities(mysqli_real_escape_string($con, $_POST['u_web']));
        $user_inerests = htmlentities(mysqli_real_escape_string($con, $_POST['interests']));
        $user_birth_day = isset($_POST['u_birth_day'])? htmlentities(mysqli_real_escape_string($con, $_POST['u_birth_day'])): separateBirthday($user_info["user_DB"], "d");
        $user_birth_month = isset($_POST['u_birth_month'])? htmlentities(mysqli_real_escape_string($con, $_POST['u_birth_month'])): separateBirthday($user_info["user_DB"], "m");
        $user_birth_year = isset($_POST['u_birth_year'])? htmlentities(mysqli_real_escape_string($con, $_POST['u_birth_year'])): separateBirthday($user_info["user_DB"], "y");
        $db = date("Y-d-m", mktime(0,0,0,(int)$user_birth_month,(int) $user_birth_day, (int)$user_birth_year));
        $user_gendar = htmlentities(mysqli_real_escape_string($con, $_POST['u_gendar']));
        $pic_name = $_FILES["user_pic"]["name"];
        $pic_tempname = $_FILES["user_pic"]["tmp_name"]; 
        $pic_path = "pics/".$pic_name;  
        $bg_name = $_FILES["user_bg"]["name"];
        $bg_tempname = $_FILES["user_bg"]["tmp_name"];    
        $bg_path = "pics/".$bg_name;
        $permission_1 = isset($_POST['ch1'])? "1" : "0";
        $permission_2 = isset($_POST['ch2'])? "1" : "0";
        $permission_3 = isset($_POST['ch3'])? "1" : "0";
        $permission_4 = isset($_POST['ch4'])? "1" : "0";
        $permission_5 = isset($_POST['ch5'])? "1" : "0";
        $permissions = $permission_1 . "," . $permission_2 . "," . $permission_3 . "," . $permission_4;
        $user_mood = htmlentities(mysqli_real_escape_string($con, $_POST['user_mood']));
        
        $uploading_pic_result = move_uploaded_file($pic_tempname, $pic_path);
        $uploading_bg_result = move_uploaded_file($bg_tempname, $bg_path);
        if ($uploading_pic_result | (!$uploading_pic_result)) {

            if ($uploading_bg_result & !$uploading_pic_result) {

                $update_query = "UPDATE users SET user_full_name = '$user_full_name', user_DB = '$db', user_bg = '$bg_path', user_mood = '$user_mood', user_bio = '$user_bio',
                                 user_location = '$user_location', user_web = '$user_web', user_interests='$user_inerests', user_permissions='$permissions', user_status = '$permission_5'
                                 WHERE user_id = '$user_id'";

                if (mysqli_query($con, $update_query)) {
                    header('location: profile.php');
                }
                else
                {
                    echo'
                        <div class="alert danger">error in updating</div>
                    ';
                }
            }
            elseif(!$uploading_bg_result)
            {
                if ($uploading_pic_result) {

                    $update_query = "UPDATE users SET user_full_name = '$user_full_name', user_DB = '$db', user_pic = '$pic_path', user_mood = '$user_mood', user_bio = '$user_bio',
                                     user_location = '$user_location', user_web = '$user_web', user_interests='$user_inerests', user_permissions = '$permissions', user_status = '$permission_5'
                                     WHERE user_id = '$user_id'";

                    if (mysqli_query($con, $update_query)) {
                        $_SESSION['user_pic'] = $pic_path;
                        header('location: profile.php');
                    }
                    else
                    {
                        echo'
                            <div class="alert danger">error in updating</div>
                        ';
                    }
                }
                else
                {
                    $update_query = "UPDATE users SET user_full_name = '$user_full_name', user_DB = '$db', user_mood = '$user_mood', user_bio = '$user_bio',
                                     user_location = '$user_location', user_web = '$user_web', user_interests='$user_inerests', user_permissions = '$permissions', user_status='$permission_5'
                                     WHERE user_id = '$user_id'";
    
                    if (mysqli_query($con, $update_query)) {
                       header('location: profile.php');
                    }
                    else
                    {
                        echo'
                            <div class="alert danger">error in updating</div>
                        ';
                    }
                }
            }
            elseif($uploading_pic_result & $uploading_bg_result)
            {

                $update_query = "UPDATE users SET user_full_name = '$user_full_name', user_DB = '$db', user_pic = '$pic_path', user_bg = '$bg_path', user_mood = '$user_mood',user_bio = '$user_bio', 
                                user_location = '$user_location', user_web = '$user_web', user_interests='$user_inerests', user_permissions = '$permissions', user_status = '$permission_5'
                                WHERE user_id = '$user_id'";

                if (mysqli_query($con, $update_query)) {
                    $_SESSION['user_pic'] = $pic_path;
                    header('location: profile.php');
                }
                else
                {
                    echo'
                        <div class="alert danger">error in updating</div>
                    ';
                }
            }
        }
    }

?>

<!--main container-->
<div class="main_container">

    <!--main section-->
    <div class="main_section">
            <!--settings top bar-->
            <div style="padding:5px 20px 15px; color:#b2b2bb; margin-top:20px;">
                <nav class="settings_bar" style="background-color: #f2f2f9; border: 3px solid #f2f2f9; color:#b2b2bb; display: table; table-layout: fixed; 
                    box-sizing: border-box; width: 100%; border-radius: 7px; overflow: hidden;">
                    <a href="#" style="color: #b2b2bb; vertical-align: middle; text-align: center; font-weight: bold; font-size: 12px; line-height: 22px;
                        display: table-cell; vertical-align: middle; padding: 4px 3px 3px;" class="tablinks" onclick="openCity(event, 'Profile')" <?php $openTab = $_SESSION['target_tab'] == "profile" ? 'id="defaultOpen"' : ""; echo $openTab;?> >
                        Profile
                    </a>
                    <a href="#" style="color: #b2b2bb; vertical-align: middle; text-align: center; font-weight: bold; font-size: 12px; line-height: 22px;
                        display: table-cell; vertical-align: middle; padding: 4px 3px 3px;" class="tablinks" onclick="openCity(event, 'Notifications')" <?php $openTab = $_SESSION['target_tab'] == "notifications" ? 'id="defaultOpen"' : ""; echo $openTab;?>>Notifications</a>
                    <a href="#" style="color: #b2b2bb; vertical-align: middle; text-align: center; font-weight: bold; font-size: 12px; line-height: 22px;
                        display: table-cell; vertical-align: middle; padding: 4px 3px 3px;" class="tablinks" onclick="openCity(event, 'Social')" <?php $openTab = $_SESSION['target_tab'] == "social" ? 'id="defaultOpen"' : ""; echo $openTab;?>>Social</a>
                    <a href="#" style="color: #b2b2bb; vertical-align: middle; text-align: center; font-weight: bold; font-size: 12px; line-height: 22px;
                        display: table-cell; vertical-align: middle; padding: 4px 3px 3px;" class="tablinks" onclick="openCity(event, 'Account')" <?php $openTab = $_SESSION['target_tab'] == "account" ? 'id="defaultOpen"' : ""; echo $openTab;?>>Account</a>
                </nav>
            </div>

            <!--                profile             -->
            <div id="Profile" class="tabcontent" style = "<?php $openTab = $_SESSION['target_tab'] == "profile" ? 'display:block;' : 'display:none;'; echo $openTab;?>">
                <!--settings "form"-->
                <form id="form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data" style="padding:10px 20px;" autocomplete="off">
                    <div class="inp_sec">
                        <p>Full Name</p>
                        <div class="inp_holder">
                            <input type="text" id="u_full_name" name="u_full_name" maxlength="30" size="30" value="<?php echo $user_info['user_full_name'];?>">
                        </div>
                    </div>
                    <div class="inp_sec">
                        <p>Location</p>
                        <div class="inp_holder">
                            <input type="text" class="u_location" name="u_location" maxlength="30" size="30" value="<?php echo $user_info['user_location'];?>">
                        </div>
                    </div>
                    <div class="inp_sec">
                        <p>Bio</p>
                        <div class="inp_holder">
                            <textarea type="text" class="u_bio" name="u_bio" maxlength="300" rows="3"><?php echo $user_info['user_bio'];?></textarea>
                        </div>
                    </div>
                    <div class="inp_hint">A little information about you</div>
                    <div class="inp_sec">
                        <p>Web</p>
                        <div class="inp_holder">
                            <textarea type="text" class="u_bio" name="u_web" maxlength="150"><?php echo $user_info['user_web'];?></textarea>
                        </div>
                    </div>
                    <div class="inp_sec">
                        <p>Add interests</p>
                        <div class="inp_holder" style="overflow:auto; height:40px;">
                            <input type="text" id="interests" name="interests">
                            <span style="padding:2px;">#</span>
                            <input type="text" class="u_location" name="u_interests" id="u_interests" maxlength="60" size="30" >
                                   <?php
                                        if(strlen($user_info["user_interests"]) > 1)
                                        {
                                            $interests = preg_split("/,/", $user_info["user_interests"]);
                                            foreach($interests as $interest)
                                            {
                                                if (strlen($interest) > 0) {
                                                    echo'
                                                        <span class="interest">'.$interest.'</span>
                                                    ';
                                                }
                                            }
                                        }
                                   ?>
                        </div>
                    </div>
                    <div class="inp_hint">Don’t post personal info in interests such as your home address or phone number</div>
                    <div class="inp_sec">
                        <p>Username</p>
                        <div class="inp_sec_trans">
                            <input style="background: transparent;" disabled="TRUE" type="text" id="u_login" maxlength="30" size="30" value="<?php echo $user_info['user_name'];?>">
                        </div>
                    </div>
                    <div class="inp_hint">Username cannot be changed</div>
                    <div class="inp_sec">
                        <p>E-mail</p>
                        <div class="inp_sec_trans">
                            <input disabled="TRUE" type="email" id="u_mail" maxlength="90" size="30" value="<?php echo $user_info['user_email'];?>"
                                   style="background:transparent;">
                        </div>
                    </div>
                    <div style="font-size: 12px; margin-top: -5px; padding-bottom: 15px;">
                        <a href="#" style="color: #ee1144;">Change Email</a>
                    </div>
                    <div class="inp_sec">
                        <p>Birthday</p>
                        <table style="width:100%;">
                            <tr style="display:table-row;">
                                <td style=" display: table-cell; vertical-align: inherit;">
                                    <select title="<?php echo separateBirthday($user_info["user_DB"], "d");?>" name="u_birth_day" id="u_birth_day" style="font-size: 16px; background: #fff; padding: 10px 5px; border: 1px solid #e6e6f0; display: block; width: 100%; border-radius: 2px;">
                                        <option>Day</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>
                                </td>
                                <td style=" display: table-cell; vertical-align: inherit; padding:0px 5px;">
                                    <select title="<?php echo separateBirthday($user_info["user_DB"], "m");?>" name="u_birth_month" id="u_birth_month" style="font-size: 16px; background: #fff; padding: 10px 5px; border: 1px solid #e6e6f0; display: block; width: 100%; border-radius: 2px;">
                                        <option>Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">Mars</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">Septamber</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </td>
                                <td style=" display: table-cell; vertical-align: inherit;">
                                    <select title="<?php echo separateBirthday($user_info["user_DB"], "y");?>" name="u_birth_year" id="u_birth_year" style="font-size: 16px; background: #fff; padding: 10px 5px; border: 1px solid #e6e6f0; display: block; width: 100%; border-radius: 2px;">
                                        <option>Year</option>
                                        <option value="2003">2003</option>
                                        <option value="2002">2002</option>
                                        <option value="2001">2001</option>
                                        <option value="2000">2000</option>
                                        <option value="1999">1999</option>
                                        <option value="1998">1998</option>
                                        <option value="1997">1997</option>
                                        <option value="1996">1996</option>
                                        <option value="1995">1995</option>
                                        <option value="1994">1994</option>
                                        <option value="1993">1993</option>
                                        <option value="1992">1992</option>
                                        <option value="1991">1991</option>
                                        <option value="1990">1990</option>
                                        <option value="1989">1989</option>
                                        <option value="1988">1988</option>
                                        <option value="1987">1987</option>
                                        <option value="1986">1986</option>
                                        <option value="1985">1985</option>
                                        <option value="1984">1984</option>
                                        <option value="1983">1983</option>
                                        <option value="1982">1982</option>
                                        <option value="1981">1981</option>
                                        <option value="1980">1980</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="inp_hint">Your age will not be visible to other users </div>
                    <div class="inp_sec">
                        <p>Gendar</p>
                        <table style="width:25%;">
                            <tr style="display:table-row;">
                                <td style=" display: table-cell; vertical-align: inherit;">
                                    <select name="u_gendar" id="u_gendar" style="font-size: 16px; background: #fff; padding: 10px 5px; border: 1px solid #e6e6f0; display: block; width: 100%; border-radius: 2px;">
                                        <?php
                                            if ($user_info["user_gendar"] == "male") {
                                                echo'
                                                <option >Gendar</option>
                                                <option selected="TRUE" value="male">male</option>
                                                <option value="female">female</option>';
                                            }
                                            else{
                                                echo'
                                                <option >Gendar</option>
                                                <option value="male">male</option>
                                                <option selected="TRUE" value="female">female</option>';  
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--</form>-->
                    <!--allow-->
                    <div class="permissions_cont">
                        <?php $permissions = separatePermissions($user_info["user_permissions"]); ?>
                        <div>
                            <a>Allow anonymous questions</a>
                            <input type="checkbox" name="ch1" <?php if($permissions[0] == "1") echo 'checked';?> id="ch1">
                        </div>
                        <div>
                            <a>Allow other users to share my posts</a>
                            <input type="checkbox" name="ch2" <?php if($permissions[1] == "1") echo 'checked';?> id="ch2">
                        </div>
                        <div>
                            <a>Allow others to subscribe to my activities</a>
                            <input type="checkbox" name="ch3" <?php if($permissions[2] == "1") echo 'checked';?>  id="ch3">
                        </div>
                        <div>
                            <a>Allow showing my answers on Discover feed</a>
                            <input type="checkbox" name="ch4" <?php if($permissions[3] == "1") echo 'checked';?>  id="ch4">
                        </div>
                        <div>
                            <a>Show status</a>
                            <input type="checkbox" name="ch5" <?php if($user_info["user_status"] == "1") echo 'checked';?> id="ch5">
                        </div>
                    </div>
                    <!--change profile photo-->
                    <div style="padding:15px 0px 5px; overflow:hidden;">
                        <h3 style="font-size: 12px; color:#2b3237; font-weight:bold;">photo</h3>
                        <div id="pic_output" style="background-image: url(<?php echo $user_info['user_pic'];?>); border-radius: 50%; background-position: 50% 50%; background-size: cover; 
                            display: inline-block; height: 45px; margin: 10px 10px 10px 0; position: relative; vertical-align: middle; width: 45px;">
                        </div>
                        <input name="user_pic" type="file" style="visibility: hidden;margin-top:20px;" class="file_input photo" accept="image/*" onchange='document.getElementById("pic_output").style.backgroundImage = "url("+window.URL.createObjectURL(this.files[0])+")"'>
                    </div>
                    <!--change background-->
                    <div style="padding:15px 0px 5px; border-top: solid 1px #d5d5dd;">
                        <h3 style="font-size: 12px; color:#2b3237; font-weight:bold;">Background</h3>
                        <div id="bg_output" style="background-image: url(<?php echo $user_info['user_bg'];?>); border-radius: 50%; background-position: 50% 50%; background-size: cover; 
                            display: inline-block; height: 45px; margin: 10px 10px 10px 0; position: relative; vertical-align: middle; width: 45px;">
                        </div>
                        <input name="user_bg" type="file" style="visibility: hidden;margin-top:20px;" class="file_input background" accept="image/*" onchange='document.getElementById("bg_output").style.backgroundImage = "url("+window.URL.createObjectURL(this.files[0])+")"'>
                    </div>
                    <!--set mood-->
                    <div style="padding:15px 0px 5px ; border-top: solid 1px #d5d5dd; overflow: hidden;">
                        <h3 style="font-size: 12px; color:#2b3237; font-weight:bold;">Set my mood</h3>
                        <div style="display: table; padding-top:10px;">
                            <h4 style="background-color: #FFCC00; border-radius: 2px; color: #fff; float: left; font-size: 9px; letter-spacing: 2.1px; 
                                margin: 0 0 9px 0; padding: 4px 5px 3px; text-indent: 2.1px;">
                                PREMIUM MOODS
                            </h4>
                            <span style="display: table-row; font-size: 12px; color: #b2b2bb; text-align: left;">
                                Get for 50 🔥 coins
                            </span>
                        </div>
                        <input type="text" name="user_mood" id="user_mood" value="<?php echo $user_info["user_mood"];?>" style="visibility: hidden;">
                        <a href="#!" >
                            <img class="mood" id="1" src="pics/moods/1.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="2" src="pics/moods/2.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="3" src="pics/moods/3.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="4" src="pics/moods/4.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="5" src="pics/moods/5.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="6" src="pics/moods/6.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="7" src="pics/moods/7.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="8" src="pics/moods/8.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="9" src="pics/moods/9.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="10" src="pics/moods/10.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="11" src="pics/moods/11.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="12" src="pics/moods/12.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="13" src="pics/moods/13.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="14" src="pics/moods/14.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="15" src="pics/moods/15.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="16" src="pics/moods/16.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="17" src="pics/moods/17.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="18" src="pics/moods/18.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="19" src="pics/moods/19.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="20" src="pics/moods/20.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="21" src="pics/moods/21.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="22" src="pics/moods/22.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="23" src="pics/moods/23.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="24" src="pics/moods/24.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="25" src="pics/moods/25.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="26" src="pics/moods/26.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="27" src="pics/moods/27.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="28" src="pics/moods/28.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="29" src="pics/moods/29.gif" alt="">
                        </a>
                        <a href="#!" >
                            <img class="mood" id="30" src="pics/moods/30.gif" alt="">
                        </a>
                    </div>
                    <!--confirm-->
                    <div class="confirm_sec">
                        <input type="click" id="submits" class="btn pri_btn" value="Save">
                        <input type="button" class="btn sec_btn" value="Cancel">
                    </div>
                    <!--hint-->
                    <p class="hint" style="border-top: solid 1px #d5d5dd;">
                        Information you provide (other than your password) may be shared with affiliates, 
                        third-party search engines, and other third parties as explained in our 
                        <a href="#">Privacy policy</a>. 
                        If you do not want us to share your information, please do not provide it.
                    </p>
                </form>
            </div>
            
            <!--                notifications             -->
            <div id="Notifications" class="tabcontent" style="padding:15px 20px; <?php $openTab = $_SESSION['target_tab'] == "notifications" ? 'display:block;' : 'display:none;'; echo $openTab;?>">
                <h3 style="font-size: 12px; color:#2b3237; font-weight:bold; padding-bottom: 10px;"> Email notifications </h3>
                <div style="padding: 10px 0px; font-size: 14px; line-height:18px; border-top: solid 1px #d5d5dd;">
                    <a href="#" style="color: #ee1144;">Allow anonymous questions</a>
                    <input type="checkbox" style="float:right; border-radius: 5px; background: #fff; border: 1px solid #cdcdd9; color: #fff; font-size: 11px; height: 18px; line-height: 20px; text-align: center; width: 18px;">
                </div>
                <div style="padding: 10px 0px; font-size: 14px; line-height:18px; border-top: solid 1px #d5d5dd;">
                    <a href="#" style="color: #ee1144;">Allow anonymous questions</a>
                    <input type="checkbox" style="float:right; border-radius: 5px; background: #fff; border: 1px solid #cdcdd9; color: #fff; font-size: 11px; height: 18px; line-height: 20px; text-align: center; width: 18px;">
                </div>
                <!--confirm-->
               <div style=" border-top: solid 1px #d5d5dd;padding:10px 0px; padding-top:20px;">
                        <input type="submit" class="btn pri_btn" value="Save">
                        <input type="button" class="btn sec_btn" value="Cancel">
                </div>
            </div>

            <!--                social             -->
            <div id="Social" class="tabcontent" style="padding:0px 20px; <?php $openTab = $_SESSION['target_tab'] == "social" ? 'display:block;' : 'display:none;'; echo $openTab;?>">
                <div style="padding: 10px 0px;">
                        <img src="pics/avatar-01.jpg" alt="" style="height: 45px; width: 45px; border-radius:50%;">
                        <h3 style="font-size: 14px; color:#2b3237; font-weight:normal; padding-bottom: 10px; vertical-align:top; margin-top:6px; display:inline-block; margin-left:8px;">
                            Facebook
                        </h3>
                        <a href="#" style="position:absolute; display: inline-block; margin-left:-62px; margin-top:24px; color: #ee1144; font-size:14px;">Disconnect</a>
                </div>
                <div style="padding: 10px 0px; border-top: solid 1px #d5d5dd;">
                        <img src="pics/avatar-01.jpg" alt="" style="height: 45px; width: 45px; border-radius:50%;">
                        <h3 style="font-size: 14px; color:#2b3237; font-weight:normal; padding-bottom: 10px; vertical-align:top; margin-top:6px; display:inline-block; margin-left:8px;">
                            Facebook
                        </h3>
                        <a href="#" style="position:absolute; display: inline-block; margin-left:-62px; margin-top:24px; color: #ee1144; font-size:14px;">Disconnect</a>
                </div>
                <div style="padding: 10px 0px; border-top: solid 1px #d5d5dd;">
                    <img src="pics/avatar-01.jpg" alt="" style="height: 45px; width: 45px; border-radius:50%;">
                    <h3 style="font-size: 14px; color:#2b3237; font-weight:normal; padding-bottom: 10px; vertical-align:top; margin-top:6px; display:inline-block; margin-left:8px;">
                        Twitter
                    </h3>
                    <a href="#" style="position:absolute; display: inline-block; margin-left:-40px; margin-top:24px; color: #ee1144; font-size:14px;">Connect</a>
                </div>
            </div>

            <!--                account             -->
            <div id="Account" class="tabcontent" style="padding: 0px 20px 10px; <?php $openTab = $_SESSION['target_tab'] == "account" ? 'display:block;' : 'display:none;'; echo $openTab;?>">
                <a  href="change_password.php?" style="color: #ee1144; font-size:14px; padding: 10px 0px; display:block;">Change password</a>
                <a href="#" style="color: #ee1144; font-size:14px; border-top: solid 1px #d5d5dd; display:block; padding: 10px 0px;">Deactive account</a>
                <a href="blockList.php?" style="color: #ee1144; font-size:14px; border-top: solid 1px #d5d5dd; display:block; padding: 10px 0px;">View blocked list</a>
                <a href="log_out.php?" style="color: #ee1144; font-size:14px; border-top: solid 1px #d5d5dd; display:block; padding: 10px 0px;">Log out</a>
            </div>
    </div>

    <!--side section-->
    <div class="side_section">

        <?php include 'includes/temps/solid_side_section.php'; ?>

    </div>

</div>


<?php
    // select user mood
    if($user_info["user_mood"] != "")
    {
        echo'
        <script> 
            x = ' . $user_info["user_mood"] . ";
            document.getElementById(". "x).classList.add('"."active_mood');
        </script>";
    }
?>

<!--settings navbar js-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/settings.js"> </script>
<script src="js/functions.js"></script>
</body>