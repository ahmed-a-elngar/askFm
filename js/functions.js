function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}
// create question, versus dropDown list
$(function() {
    $(".add").click(function() {
        dropdown = $(this).next("#myDropdown2");
        if (dropdown.css('display') == 'none') {
            dropdown.addClass('show');
        } else
            dropdown.removeClass('show');
    });
});
// Close the dropdown menu (settings, add +) if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.add')) {
        var dropdowns = document.getElementsByClassName("dropdown-content2");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (!event.target.matches('.more')) {
        var dropdowns = document.getElementsByClassName("dropdown-content-q");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    if (!event.target.matches('.more')) {
        var dropdowns = document.getElementsByClassName("dropdown-content-a");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// more display on / off
$(document).on('click', '.more', function(){
    $(this).next().toggleClass("show");
});
// delete this question
$(document).on('click', '#delete_q_btn', function(){
    q_id = $(this).attr('value');
    $(this).load('includes/funcs/delete_q.php?', {
        pa_1: q_id
    });
    $(this).parentsUntil('.main_section').remove();
});
// report this question / user
$(document).on('click', '#report_q_btn', function(){
    console.log('report');
});
// block this question user
$(document).on('click', '#block_q_u_btn', function(){
    q_id = $(this).attr('value');
    $(this).load('includes/funcs/blockUser.php?', {
        pa_1: q_id
    });
    $(this).parentsUntil('.main_section').remove();
});

// delete answer & return it to questions
$(document).on('click', '#delete_a_btn', function(){
    a_id = $(this).attr('value');
    $(this).load('includes/funcs/delete_a.php?', {
        pa_1: a_id
    });
    $(this).parentsUntil('.tabcontent').remove();
    // change user total posts & likes count
    vv = $('#user_stics_likes').text();
    ov = $('button[value="'+a_id+'"][title="Like"]').next('a').html();
    $('#user_stics_likes').text((vv.trim())-(ov.trim()));
    pp = $('#user_stics_posts').text();
    $('#user_stics_posts').text((pp.trim())-1);
});
// delete answer from answer_details
$(document).on('click', '#delete_a_a_btn', function(){
    a_id = $(this).attr('value');
    $(this).load('includes/funcs/delete_a.php?', {
        pa_1: a_id
    });
    window.location.href  = "profile.php";
});

// block this user
$(document).on('click', '#block_u_btn', function(){
    u_id = $(this).attr('value');
    $(this).load('includes/funcs/blockUserProfile.php?', {
        pa_1: u_id
    });
});

// to load notifications
notiCount = document.querySelector(".noti_count");

$(function(){
    setInterval(() =>{
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../user/load_notifications.php", true);
        xhr.onload = ()=>{
          if(xhr.readyState === XMLHttpRequest.DONE){
              if(xhr.status === 200){
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

// display shoutouts or hide them
$(document).on('click', '#switch_q', function(){

    var shoutouts = document.getElementsByClassName("shout");
    var i;
    type = 'h';

    for (i = 0; i < shoutouts.length; i++) {
        var thisShout = shoutouts[i];

        if (thisShout.style.display == 'block') {
            thisShout.style.display = 'none';
            type = 'h';
        }
        else if (thisShout.style.display == '') {
            thisShout.style.display = 'none';
            type = 'h';
        }
        else
        {
            thisShout.style.display = 'block';
            type = 's';
        }
    }
    
    old_val = parseInt($('#ques_count').text());
    if (type == 'h') {
        new_val = old_val - shoutouts.length;
    }
    else if (type == 's') {
        new_val = old_val + shoutouts.length;
    }
    $('#ques_count').text(new_val);
});

// delete all questions
$(document).on('click', '#delete_all', function(){
    $('.question_block').load('includes/funcs/deleteAllQues.php',{
        pa_1: 'ok'
    });
    $('.question_block').remove();
    $('#ques_count').html('0');
});