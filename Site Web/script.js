var cases = [
	$('#case_1'),
	$('#case_2'), 
	$('#case_3'), 
	$('#case_4'), 
	$('#case_5'), 
	$('#case_6'), 
	$('#case_7'), 
	$('#case_8'), 
	$('#case_9'), 
];
var case_none = 1;

if ($(window).width() < 1000) { // Taille mobile
	var cw = $('.case').width();
	$('.case').css({'height': cw+'px'});
	$('.case').css({'margin': '22px'});

	var cp = $('.popup').width();
	$('.popup').css({'height': cp+40+'px'});
	
	var cpn = $('.popup_none').width();
	$('.popup_none').css({'height': cpn+'px'});
	$('.popup_none').css({'margin-top': -cpn+20+'px'});

	var cg = $('.container_greenhouse').width();
	$('.container_greenhouse').css({'height': cp+'px'});

	$('body').append('<div class="button_mobile"><a href="new_legume.php"><button>Créer un légume</button></a><a href="action.php?action=calibration"><button>Calibrer la serre</button></a><a href="action.php?action=deco"><button>Se déconnecter</button></a></div>');

}

showUser($('.popup_none select').val());
for(i = 0; i < 9; i++){
	if($(cases[i]).attr("planted") == 1){
		$(cases[i]).css("background-color",  "rgb(35,30,50)");
	}
}
$('.case .case_img').click(function(e){
	if($(this).parent().attr("planted") == 1){
		$(this).parent().children('.popup').fadeIn();
		$(".popup .name").html($(this).attr("name"));
		$(".popup img").attr("src", "img/legume/"+$(this).parent().attr("name")+".jpg");
		$(".popup .link_recolte").attr("href", "action.php?action=recolte&case="+$(this).parent().attr("id"));
		$(".popup .link_arrosage").attr("href", "action.php?action=arrosage&case="+$(this).parent().attr("id"));
	}else{
		$(".popup_none").fadeIn();
		$(".popup_none .link_planter").attr("href", "action.php?action=planter&case="+$(this).parent().attr("id")+"&name="+$('.popup_none select').val());
		$('.popup_none select').attr("id", $(this).parent().attr("id"));
	}
});
$('.popup .validate').click(function(e){
	$(this).parent().fadeOut();
});
$('.popup_none .close').click(function(e){
	$(".popup_none").fadeOut();
});
$('.popup_none select').click(function(e){
	showUser($(this).val());
	$(".popup_none .link_planter").attr("href", "action.php?action=planter&case="+$(this).attr("id")+"&name="+$('.popup_none select').val());
	$(".popup_none img").attr("src", "img/legume/"+$(this).val()+".jpg");
});

function showUser(str) {
    if (str == "") {
        document.getElementById("info_legume").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("info_legume").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","action.php?action=getInfo&legume="+str,true);
        xmlhttp.send();
    }
}