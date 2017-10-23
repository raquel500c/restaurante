<?php
session_start();

//Incluimos las funciones necesarias:
require_once ("../func/datosConexion.php");
require_once ("../func/funcConexion.php");
require_once ("../func/funcConsultas.php");
require_once ( "../func/funcSesiones.php" );
require_once ( "func/funcAdmin.php" );
?>


<html>
	<head>
		<!--controlamos que el viewport se adapte a todos los dispositivos-->
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<!-- enlaze con la hoja de estilos-->
		<link rel="stylesheet" href="css/estilos.css">
		<!-- enlaze con las fuentes a utilizar-->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	</head>
	<body>

<?php

if ( usuarioConectado() ) {
	//Comprobación de si tiene privilegios para estar aquí
	if ( usuarioEsAdmin() || usuarioEsTrabajador() ) {
		$usuarioAutorizado = true;
	} else {
		//usuario conectado como cliente
		$usuarioAutorizado = false;

		echo "<p>" . $_SESSION [ "nombre" ] . ". NO TIENES PRIVILEGIOS PARA ESTAR AQUÍ.</p>
			 <img src=\"images/pistolero.jpg\" alt=\"Pistolero\"/>
			 <p>¡FUERA!</p>";
	}
} else {
	//si se trata de acceder desde el navegador a la url de admin sin estar conectado
	$usuarioAutorizado = false;
	// Redirigimos a la página login para que no se entre sin logearse en restaurante
	header( 'Location:http://localhost/restaurante/index.php?p=login'  );

	exit; // Evitamos que se siga ejecutando código de ésta página
}
?>

		<nav>
			<ul>
				<li><a href="index.php?opcion=restaurante">Ir a Restaurante</a></li>
				<?php if ( $usuarioAutorizado ) : ?>
				<li><a href="index.php?opcion=productos">Productos</a></li>
				<li><a href="index.php?opcion=pedidos">Pedidos</a></li>
				<li><a href="index.php?opcion=usuarios">Gestión de usuarios</a></li>
				<li><a href="cerrarSesion.php">Cerrar sesion</a></li>
				<?php endif ?>
			</ul>
		</nav>
		<div id="contenedor">

<?php
if ( !isset( $_GET ["opcion"] ) )
	$pagina = "Inicio";
else
	switch ( $_GET ["opcion"] ) {
		case "restaurante":
			header('Location:http:../index.php');
			break;
		case "productos":
			$pagina = "Productos";
			break;
		case "usuarios":
			$pagina = "Usuarios";
			break;
		case "pedidos":
			$pagina = "Pedidos";
			break;
		default:
			$pagina = "404";
	}

	require_once ("func/funcAdmin$pagina.php");
	if ( $usuarioAutorizado ) visualizarContenido();

?>

		</div>
	</body>
</html>
