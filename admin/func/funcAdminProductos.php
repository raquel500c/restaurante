<?php
/* Este fichero procesa toda la actividad de los productos. Los muestra, crea y modifica.
 * La acción depende de las variables get y post que recibe.
 */

function visualizarContenido() {
	if ( isset( $_POST["nombre"] ) )
		procEditarProducto();//procesa la modificación de un producto
	if ( isset( $_POST["agregarNombre"] ) )
		procAgregarProducto();//procesa el registro de un producto
	if ( isset( $_GET["id"] ) )
		if ( $_GET["id"] == "agregar" )
			formAgregarProducto();//visualiza la página de registrar un producto
		else
			formEditarProducto( $_GET["id"] );//Visualiza la página de edición de un producto
	else
		mostrarListadoProductos();//Visualiza la lista de productos
}

function procEditarProducto() {
	//Todos los datos recibidos por POST los mete en una consulta del tipo UPDATE y la ejecuta
	$consulta = "
		UPDATE productos SET
		nombre_prod='" . $_POST["nombre"] . "',
		descripcion='" . $_POST["descripcion"] . "',
		precio_prod='" . $_POST["precio"] . "',
		ruta_imagen='" . $_POST["imagen"] . "',
		is_active='" . $_POST["activo"] . "'
		WHERE id_prod='" . $_POST["id"] . "';
		";

	if ( consultar( $consulta ) )
		echo "<p class=\"exito\">El producto ".$_POST["nombre"]." ha sido modificado con éxito.</span>";
	else
		echo "<p class=\"error\">Oh no!, ha habido un error</span>. La consulta enviada es: $consulta";
}

function procAgregarProducto() {
	//Todos los datos recibidos por POST los mete en una consulta del tipo INSERT y la ejecuta
	$consulta = "
		INSERT INTO productos ( nombre_prod,descripcion,precio_prod,ruta_imagen,is_active )
	 	VALUES ( '" . $_POST["agregarNombre"] . "', '" . $_POST["descripcion"] . "', '" . $_POST["precio"] . "',
	 			 '" . $_POST["imagen"] . "', '" . $_POST["activo"] . "' )";

	if ( consultar( $consulta ) )
		echo "<p class=\"exito\">" . $_POST["agregarNombre"] . " ha sido insertado con éxito.</span>";
	else
		echo "<p class=\"error\">ERROR</span>. La consulta enviada es: $consulta";
}

function formAgregarProducto() {
	echo "<h1>Registro de nuevo producto</h1>";
	echo "
		<form method=\"post\" action=\"index.php?opcion=productos\">
		<table>
		<tr>
			<td>Nombre del producto</td>
			<td><input name=\"agregarNombre\" type=\"text\" /></td>
		</tr>
		<tr>
			<td>Descripción</td>
			<td><textarea  name=\"descripcion\" cols=\"30\" rows=\"10\"></textarea></td>
		</tr>
		<tr>
			<td>Precio</td>
			<td><input  name=\"precio\" type=\"number\" step=\"any\" min=\"0\" />€</td>
		</tr>
		<tr>
			<td>Imagen en repositorio</td>
			<td><input  name=\"imagen\" type=\"text\" placeholder=\"Nombre del fichero\"/></td></td>
		</tr>
		<tr>
			<td>¿Activo?</td>
			<td><input type=\"radio\" name=\"activo\" value=\"1\" checked /> Sí
			<input type=\"radio\" name=\"activo\" value=\"0\" /> No
		</tr>
		</table>
		<input type=\"Submit\" value=\"Agregar producto\">
		</form>
		";

}


function formEditarProducto( $producto ) {
	echo "<h1>Modificación de datos del producto $producto </h1>";

	//consultamos en base de datos los datos actuales y los ponemos en un formulario para editarlos
	$resultado = consultar( "SELECT * FROM productos WHERE id_prod=".$_GET["id"] );
	$plato = mysqli_fetch_array( $resultado, MYSQLI_ASSOC );
	echo "
		<form method=\"post\" action=\"index.php?opcion=productos\">
		<input type=\"hidden\" name=\"id\" value=\"" . $_GET["id"] . "\" />
		<table>
		<tr>
			<td>Nombre</td>
			<td><input name=\"nombre\" type=\"text\" value=\"" . $plato["nombre_prod"] . "\" ></td>
		</tr>
		<tr>
			<td>Descripción</td>
			<td><textarea  name=\"descripcion\" cols=\"30\" rows=\"10\">". $plato["descripcion"] . "</textarea></td>
		</tr>
		<tr>
			<td>Precio</td>
			<td><input  name=\"precio\" type=\"number\" value=\"" . $plato["precio_prod"] . "\" step=\"any\">€</td>
		</tr>
		<tr>
			<td>Imagen en repositorio</td>
			<td><input  name=\"imagen\" type=\"text\" value=\"" . $plato["ruta_imagen"] . "\" ></td></td>
		</tr>
		<tr>
			<td>¿Activo?</td>
			<td><input type=\"radio\" name=\"activo\" value=\"1\" "; if ( $plato["is_active"] == 1 ) echo "checked";
			echo" />Sí </td>
			<td><input type=\"radio\" name=\"activo\" value=\"0\" "; if ( $plato["is_active"] == 0 ) echo "checked";
			echo" />No </td>
		</tr>
		</table>
		<input type=\"Submit\" value=\"Guardar modificaciones\">
		</form>
	";
}


function mostrarListadoProductos() {
	echo "
		<h1>Productos disponibles</h1>
		<br/>
	";
	if ( usuarioEsAdmin() )
	echo "	<p><a class=\"boton\" href=\"index.php?opcion=productos&id=agregar\">Agregar producto</a></p>
		<br/>
	";

	$resultado = consultar( "SELECT * FROM productos" );

	echo "
		<table>
		<thead>
		<td>id</td>
		<td>Nombre</td>
		<td>Descripción</td>
		<td>Precio</td>
		<td>Imagen</td>
		<td>Activo</td>
	";

	if ( usuarioEsAdmin() )
		echo "<td></td>";

	echo "</thead>";

	while ( $plato = mysqli_fetch_array( $resultado, MYSQLI_ASSOC ) ) {
		echo "
			<tr>
			<td>" . $plato["id_prod"] . "</td>
			<td>" . $plato["nombre_prod"] . "</td>
			<td>" . $plato["descripcion"] . "</td>
			<td>" . $plato["precio_prod"] . "€</td>
			<td><img class=\"producto\" src=\"../images/" . $plato["ruta_imagen"] . "\" alt=\"imagen\" /></td>
			<td>"; if ( $plato["is_active"] == 1 ) echo "sí"; else echo "no"; echo"</td>
		";

			if ( usuarioEsAdmin() )
				echo "<td><a class=\"boton\" href=\"index.php?opcion=productos&id=" . $plato["id_prod"] . "\">Editar</a></td>";

		echo "
			</tr>
		";
	}
	echo "
		</table>
	";

}

?>
