<?php

//controla si la sesión de usuario está activa
function usuarioConectado() {
	if ( isset( $_SESSION ["nombre"] ) )
	return true;
	else return false;
} //endUsuarioConectado

//cierra sesión actual y redirige a home
function logout() {
	unset ( $SESSION ['id_usuario'] );
	session_destroy();
	header( 'Location:http://localhost/restaurante/index.php' );
} //endLogout
?>
