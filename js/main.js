//función para nav menu adaptativo
$(function() {
			var pull = $('#pull');
			menu = $('nav ul');
			menuHeight = menu.height();

			$(pull).on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});
		});

		$(window).resize(function() {
			var w = $(window).width();
			if ( w > 500 && menu.is(':hidden') ) {
				menu.removeAttr('style');
			}
		});

//*función que comprueba si el usuario introduce la confirmación de pass coincidente
/*$(function(){
    var mensajeError="";
    
    if ($("#pass1").val() != $("#pass2").val())
    {
         mensajeError = mensajeError+"<p>¡Las contraseñas no coinciden!</p>";       
    }    

});*/

//función que comprueba si el usuario introduce la confirmación de pass coincidente
/*function comprobarPassword(){
	pass1=document.formRegistro.pass1.value;
	pass2=document.formRegistro.pass2.value;
	if(!pass1==pass2){
		alert("Las contraseñas deben coincidir");
		document.getElementsByTagName('pass2').focus();
	}
}*/

