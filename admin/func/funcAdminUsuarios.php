<?php
/* Este fichero procesa toda la actividad de los usuarios. Los muestra, crea y modifica.
 * La acción depende de las variables get y post que recibe.
 */

function visualizarContenido() {
	if ( isset( $_POST[ "nombre" ] ) )
		procEditarUsuario();//Procesa la modificación de un usuario
	if ( isset( $_POST[ "agregarNombre" ] ) )
		procAgregarUsuario();//Procesa el registro de un usuario
	if ( isset( $_GET[ "id" ] ) )
		if ( $_GET[ "id" ] == "agregar" )
			agregarUsuario();//visualiza la página de registrar un usuario
		else
			editarUsuario( $_GET[ "id" ] );//Visualiza la página de edición de un usuario
	else
		mostrarListadoUsuarios();//Visualiza la lista de usuarios
}


function procEditarUsuario() {
	//Todos los datos recibidos por POST los mete en una consulta del tipo UPDATE y la ejecuta
	$consulta = "
	UPDATE usuarios SET
	nombre='" . $_POST["nombre"] . "',
	apellidos='" . $_POST["apellidos"] . "',
	telefono='" . $_POST["telefono"] . "',
	email='" . $_POST["email"] . "',
	direccion='" . $_POST["direccion"] . "',
	cod_postal=" . $_POST["codigoPostal"] . ",
	municipio='" . $_POST["municipio"] . "',
	provincia='" . $_POST["provincia"] . "',
	rol=" . $_POST["rol"] . ",
	is_active=" . $_POST["activo"] . "
	WHERE id_usuario=" . $_POST["id"];

	if ( consultar( $consulta ) )
		echo "<p class=\"exito\" >El usuario ". $_POST["nombre"] . " ". $_POST["apellidos"] . " ha sido modificado con éxito.</span>";
	else
		echo "<p class=\"error\">Oh no!, ha habido un error</span>. La consulta enviada es: $consulta";
}


function procAgregarUsuario() {
	//Todos los datos recibidos por POST los mete en una consulta del tipo INSERT y la ejecuta
	$consulta = "
	INSERT INTO usuarios(
	nombre,
	apellidos,
	telefono,
	email,
	password,
	direccion,
	cod_postal,
	municipio,
	provincia,
	rol,
	is_active
	)
	VALUES(
	'" . $_POST["agregarNombre"] . "',
	'" . $_POST["apellidos"] . "',
	'" . $_POST["telefono"] . "',
	'" . $_POST["email"] . "',
	'" . $_POST["password"] . "',
	'" . $_POST["direccion"] . "',
	" . $_POST["codigoPostal"] . ",
	'" . $_POST["municipio"] . "',
	'" . $_POST["provincia"] . "',
	" . $_POST["rol"] . ",
	" . $_POST["activo"] . "
	)
	";

	if ( consultar( $consulta ) )
		echo "<p class=\"exito\">El usuario " . $_POST[ "agregarNombre" ] . " ha sido insertado con éxito.</span>";
	else
		echo "<p class=\"error\">ERROR</span>. La consulta enviada es: $consulta";
}


//Genera la página para registrar un nuevo usuario
function agregarUsuario() {
	echo "<h1>Registro de nuevo usuario</h1>";
	echo "
		<form method=\"post\" action=\"index.php?opcion=usuarios\">
		<table>
		<tr>
			<td>Nombre</td>
			<td><input name=\"agregarNombre\" type=\"text\" required /></td>
		</tr>
		<tr>
			<td>Apellidos</td>
			<td><input name=\"apellidos\" type=\"text\" required  /></td>
		</tr>
		<tr>
			<td>Teléfono</td>
			<td><input name=\"telefono\" type=\"text\" pattern=\"^[9|8|7|6]\d{8}$\" required /></td>
		</tr>
		<tr>
			<td>E-mail</td>
			<td><input name=\"email\" type=\"email\" required /></td>
		</tr>
		<tr>
			<td>Contraseña</td>
			<td><input name=\"password\" type=\"password\" required /></td>
		</tr>
		<tr>
			<td>Dirección</td>
			<td><input name=\"direccion\" type=\"text\" size=\"30\" required /></td>
		</tr>
		<tr>
			<td>Código postal</td>
			<td><input name=\"codigoPostal\" type=\"text\" required /></td>
		</tr>
		<tr>
			<td>Municipio</td>
			<td><input name=\"municipio\" type=\"text\" required /></td>
		</tr>
		<tr>
			<td>Provincia</td>
				<td><input name=\"provincia\" type=\"text\" required /></td>
			</tr>
			<tr>
			<td>Tipo</td>
			<td>
				<input type=\"radio\" name=\"rol\" value=\"1\" />Administrador
				<input type=\"radio\" name=\"rol\" value=\"2\" checked />Trabajador
				<input type=\"radio\" name=\"rol\" value=\"3\" />Cliente
			</td>
		</tr>
		<tr>
			<td>¿Activo?</td>
			<td><input type=\"radio\" name=\"activo\" value=\"1\" checked/>Sí
			<input type=\"radio\" name=\"activo\" value=\"0\"  />No </td>
		</tr>
		</table>
			<input type=\"Submit\" value=\"Crear usuario\">
		</form>
	";
}


//Genera la página de edición de un usuario
function editarUsuario( $usuario ) {
	echo "<h1>Modificación de datos del Usuario $usuario </h1>";

	//Consultamos en base de datos los datos actuales y los ponemos en un formulario para editarlos
	$resultado = consultar( "SELECT * FROM usuarios WHERE id_usuario = " . $_GET [ "id" ] );
	$usuario = mysqli_fetch_array( $resultado, MYSQLI_ASSOC );

	echo "
		<form method=\"post\" action=\"index.php?opcion=usuarios\">
		<input type=\"hidden\" name=\"id\" value=\"" . $_GET["id"] . "\" />
		<table>
		<tr>
			<td>Nombre</td>
			<td><input name=\"nombre\" type=\"text\" value=\"" . $usuario["nombre"] . "\" required ></td>
		</tr>
		<tr>
			<td>Apellidos</td>
			<td><input name=\"apellidos\" type=\"text\" value=\"" . $usuario["apellidos"] . "\" required ></td>
		</tr>
		<tr>
			<td>Teléfono</td>
			<td><input name=\"telefono\" type=\"text\" value=\"" . $usuario["telefono"] . "\" pattern=\"^[9|8|7|6]\d{8}$\" required ></td>
		</tr>
		<tr>
			<td>E-mail</td>
			<td><input name=\"email\" type=\"email\" value=\"" . $usuario["email"] . "\" required ></td>
		</tr>
		<tr>
			<td>Dirección</td>
			<td><input name=\"direccion\" type=\"text\" size=\"30\" value=\"" . $usuario["direccion"] . "\" required ></td>
		</tr>
		<tr>
			<td>Código postal</td>
			<td><input name=\"codigoPostal\" type=\"text\" value=\"" . $usuario["cod_postal"] . "\" required ></td>
		</tr>
		<tr>
			<td>Municipio</td>
			<td><input name=\"municipio\" type=\"text\" value=\"" . $usuario["municipio"] . "\" required ></td>
		</tr>
		<tr>
			<td>Provincia</td>
			<td><input name=\"provincia\" type=\"text\" value=\"" . $usuario["provincia"] . "\" required ></td>
		</tr>
		<tr>
			<td>Tipo</td>
			<td><input type=\"radio\" name=\"rol\" value=\"1\" "; if ( $usuario["rol"] == 1 ) echo "checked";
			echo" />Administrador
			<input type=\"radio\" name=\"rol\" value=\"2\" "; if ( $usuario["rol"] == 2 ) echo "checked";
			echo" />Trabajador
			<input type=\"radio\" name=\"rol\" value=\"3\" "; if ( $usuario["rol"] == 3 ) echo "checked";
			echo" />Cliente
			</td>
		</tr>
		<tr>
			<td>¿Activo?</td>
			<td><input type=\"radio\" name=\"activo\" value=\"1\" "; if ( $usuario["is_active"] == 1 ) echo "checked";
			echo" />Sí
			<input type=\"radio\" name=\"activo\" value=\"0\" "; if ( $usuario["is_active"] == 0 ) echo "checked";
			echo" />No </td>
		</tr>
		</table>
		<input type=\"Submit\" value=\"Guardar modificaciones\">
		</form>
	";
}


function mostrarListadoUsuarios() {
	echo "
		<h1>Usuarios registrados</h1>
		<br/>
	";
	if ( usuarioEsAdmin() )
	echo "
		<p><a class=\"boton\" href=\"index.php?opcion=usuarios&id=agregar\">Agregar usuario</a></p>
		<br/>
	";

	$resultado = consultar( "SELECT * FROM usuarios" );

	echo "
		<table>
		<thead>
		<td>id</td>
		<td>Nombre</td>
		<td>Apellidos</td>
		<td>Rol</td>
		<td>Email</td>
		<td>Activo</td>
	";

	if ( usuarioEsAdmin() )
		echo "<td></td>";

	echo "</thead>";

	while( $usuario = mysqli_fetch_array( $resultado, MYSQLI_ASSOC ) ) {
		echo "
			<tr>
			<td>" . $usuario["id_usuario"] . "</td>
			<td>" . $usuario["nombre"] . "</td>
			<td>" . $usuario["apellidos"] . "</td>
			<td>" . rolUsuario($usuario["rol"]) . "</td>
			<td>" . $usuario["email"] . "</td>
			<td>"; if ( $usuario["is_active"] == 1 ) echo "sí"; else echo "no"; echo "</td>
		";

		if ( usuarioEsAdmin() )
			echo "
			<td><a class=\"boton\" href=\"index.php?opcion=usuarios&id=" . $usuario["id_usuario"] . "\">Editar</a></td>
			";
			echo "
			</tr>
		";
	}
	echo "
		</table>
	";
}


function rolUsuario( $rol ) {
	//Transformamos el número de rol en una cadena
	switch ( $rol ) {
		case 1:
			$resultado = "Administrador";
			break;
		case 2:
			$resultado = "Trabajador";
			break;
		case 3:
			$resultado = "Cliente";
			break;
	}
	return $resultado;
}

?>
