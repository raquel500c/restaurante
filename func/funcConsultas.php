<?php
global $id_insertado;
/**************
*
* CONSULTAS
*
***************/

//Función para realizar todo tipo de consultas SQL
function consultar( $consulta ) {
	//definimos la variable  para poder usar dentro contexto función
	global $resultado;
	//almacenamos en variable el objeto $con devuelto al ejecutar conectarBase
	$con = conectarBase();
	//verificamos que hay conexión, enviamos consulta y almacenamos resultado
	if ( $con ) {
		$resultado = mysqli_query( $con,$consulta );
		//si hay errores en la consulta
		if ( !$resultado ) {
		echo "Error de Base de Datos: " . mysqli_error( $con );
		//si la consulta es correcta
		} else {
			//Verificamos si obtenemos un objeto y es de tipo mysqli_result (resultado de un SELECT)
			if ( is_object( $resultado ) && get_class( $resultado ) == "mysqli_result" ) {
				//Si el SELECT no devuelve resultados
				if ( mysqli_num_rows( $resultado ) < 1 ) {
					echo "No se encontraron registros para esa consulta";
					return false;
				} else {
					//print "La consulta ha producido ".mysqli_affected_rows($con)." resultados<br /><br />";
					return $resultado;
				}
			}

			//si no es de tipo SELECT
			else {
				//si la consulta es de tipo INSERT devuelve el id_insertado asignado por la BBDD con autoincrement
				if ($id_insertado = mysqli_insert_id($con)){
				return $id_insertado;
				//si la consulta es de tipo UPDATE, ALTER el resultado es TRUE
				}else return $resultado;
			}
		//liberamos memoria
		mysqli_free_result($resultado);
		//cerramos conexión
		mysqli_close( $con );
		}
	}
}


 /**************
 *
 * PRUEBAS
 *
 ***************/


 // Función básica que muestra todas las filas resultantes de una consulta sin formato para pruebas
function listar( $resultado ){
	while ( $fila = mysqli_fetch_row( $resultado ) ) {
		echo "<p>";
			foreach( $fila  as $celda ) { echo $celda . " - " }
    echo "</p>";
	}
}

?>
