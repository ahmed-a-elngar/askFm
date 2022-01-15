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

// add & remove active_mood class
$(function() {
    $(".mood").click(function() {
        var moods = document.getElementsByClassName("mood");
        var i;
        for (i = 0; i < moods.length; i++) {
            var mood = moods[i];
            if (mood.classList.contains('active_mood')) {
                mood.classList.remove('active_mood');
            }
        }
        this.classList.add('active_mood');
        $("#user_mood").val( $(this).attr('id'));
    });
});
$(function(){
    $('.active_mood').click(function(){
        this.classList.remove('active_mood');
        $("#user_mood").val( 0);
    });
});
// add interest if press enter
$(function(){
    $("#u_interests").keypress(function(event){
        var key = (event.keyCode? event.keyCode : event.which);
        if(key == '13')
        {
            val = $(this).val();
            $(this).val = "";
            interest = '<span class="interest"> #'+ val +'</span>';
            $(interest).insertAfter(this);
            $(this).val('');
        }
    });
});
// remove interest if clicked
$(function(){
    $(document.body).on('click', '.interest', function(){
        $(this).remove();
    });
});
// submit form
$(function(){
    $('#submits').click(function(){
        f_name = $('#u_full_name').val();
        f_name = f_name.trim();
        if (f_name.length != 0) {
            var interests = document.getElementsByClassName("interest");
            var i = 0, interestsValue = "";
            for (i = 0; i < interests.length; i++) {
                var interest = interests[i];
                var val = $(interest).text();
                if(! val == "")
                    interestsValue += val + ",";
            }
            $('#interests').val(interestsValue);
            $('form#form').submit();
        }

    });
});
// view birthday
$(function(){
    day = $('#u_birth_day').attr("title");
    $('option[value='+day+']', '#u_birth_day').attr('selected', 'true');
    month = $('#u_birth_month').attr("title");
    $('option[value='+month+']', '#u_birth_month').attr('selected', 'true');
    year = $('#u_birth_year').attr("title");
    $('option[value='+year+']', '#u_birth_year').attr('selected', 'true');
});

$(document).on('keyup input', '#u_full_name', function(){
        f_name = $('#u_full_name').val();
        f_name = f_name.trim();
        if (f_name.length == 0) {
            $('#u_full_name').css('border', '1px solid #e14');
            $('#u_full_name').attr('placeholder', 'please complete me'); 
        }
        else
        $('#u_full_name').css('border', 'none');
});