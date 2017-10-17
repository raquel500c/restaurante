<?php


/***************************
*
* CARGAR DISTINTAS PÁGINAS
*
***************************/ 

function getPagina() {
	//Si la variable p se ha recibido y no está vacía, devolvemos en variable $pagina
	if ( isset( $_GET["p"] ) and $_GET["p"] != "" )
		$pagina = $_GET["p"];
	else $pagina ="inicio";
		return $pagina;
}


 //funcion que ejecutamos en un require en el index para cargar la página que corresponda según variable recibida
function cargar($pagina) { 	
	switch ($pagina) {
		case "inicio":
			$fichero = 'func/funcInicio.php';
			break;
		case "productos":
			$fichero = "func/funcProductos.php";
			break;
		case "login":
			$fichero = "func/funcAccesoUsuarios.php";
			break;
		case "logout":
			$fichero = "cerrarSesion.php";
			break;
		case "registro":
			$fichero = "func/funcRegistroUsuarios.php";
			break;
		case "pedidos":
			$fichero = "func/funcPedidos.php";
			break;
		case "resumen-solicitud":
			$fichero = "func/resumenSolicitud.php";
			break;
		case "confirmacion":
			$fichero = "func/confirmacion.php";
			break;
		default:
			$fichero = 'func/funcInicio.php';	
	}
	return $fichero;		
}

?>


