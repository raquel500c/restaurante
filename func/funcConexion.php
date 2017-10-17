<?php

/*********************
* 
* CODIFICACION PHP
*
**********************/

// usaremos cadenas UTF-8 hasta el final
mb_internal_encoding( 'UTF-8' ); 
// generaremos cadenas UTF-8
mb_http_output( 'UTF-8' );

/*******************
* 
* CONEXION A BBDD
*
********************/
//Incluimos los datos de conexión:
require_once( "datosConexion.php" );

//funcion que realiza la conexión con la BBDD con las variables de conex definidas en conexión.php, 
//Devuelve un objeto $con si conecta con éxito
function conectarBase() {
	//definimos las variables de conex como globales para poder usar dentro contexto función
	global $host,$usuario,$clave,$base;
	if ( !$con = mysqli_connect($host,$usuario,$clave,$base) ) {
		return false;
	} else {
		//establecemos juego de caracteres
		mysqli_set_charset( $con,"utf8" );
		//si puede conectarse devuelve el link a esa conexión
		return $con;
	}
}

?>

