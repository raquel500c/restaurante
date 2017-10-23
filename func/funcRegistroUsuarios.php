<?php
/* Este fichero procesa toda la actividad de los usuarios. El registro el acceso y el cierre de sesion
 * La acción depende de las variables get y post que recibe.
 */

function visualizarContenido() {
		if ( isset( $_POST["agregarEmail"] ) )
			procRegistrarUsuario();//procesa el registro de un nuevo usuario en la bbdd

		else
			formRegistroUsuario();//visualiza el formulario de registro de usuario
}


function procRegistrarUsuario() {
		//Todos los datos recibidos por POST los mete en una consulta del tipo INSERT y la ejecuta

	//Validamos que han llegado todas las variables desde el formulario de registro, y que no estén vacías:*/
	if ( isset ( $_POST["nombre"], $_POST["apellidos"], $_POST["telefono"], $_POST["direccion"], $_POST["postal"],
				 $_POST["municipio"], $_POST["provincia"], $_POST["agregarEmail"], $_POST["pass1"] )
				 and $_POST["nombre"] <> "" and $_POST["apellidos"] <> "" and $_POST["telefono"] <> ""
				 and $_POST["direccion"] <> "" and $_POST["postal"] <> "" and $_POST["municipio"] <> ""
				 and $_POST["provincia"] <> "" and $_POST["agregarEmail"] <> "" and $_POST["pass1"] <> "" ) {
		/*Abrimos conexión para obtener un objeto $con, que pasaremos como parámetro
		a mysqli_real_escape_string($con,$_POST["string"]); */
		$con = conectarBase();
		//Traspasamos a variables locales, y controlamos posible inyección de SQL
		$nombre = mysqli_real_escape_string($con,$_POST["nombre"]);
		$apellidos = mysqli_real_escape_string($con,$_POST["apellidos"]);
		$telefono = mysqli_real_escape_string($con,$_POST["telefono"]);
		$direccion = mysqli_real_escape_string($con,$_POST["direccion"]);
		$postal = mysqli_real_escape_string($con,$_POST["postal"]);
		$municipio = mysqli_real_escape_string($con,$_POST["municipio"]);
		$provincia = mysqli_real_escape_string($con,$_POST["provincia"]);
		$email = mysqli_real_escape_string($con,$_POST["agregarEmail"]);
		$password = mysqli_real_escape_string($con,$_POST["pass1"]);
		//cerramos conexión
		mysqli_close($con);

		//Preparamos la orden SQL:
		$query = "
				INSERT INTO usuarios (nombre,apellidos,telefono,direccion,cod_postal,municipio,provincia,email,password)
				VALUES ('$nombre','$apellidos','$telefono','$direccion',$postal,'$municipio','$provincia',
					'$email','$password')";

		//Ejecutamos consulta mediante la función consultar:
		$resultado = consultar($query);
		//Al tratarse de un INSERT, la consulta devuelve el id insertado
		if( empty( $resultado ) ) {

		//si no han llegado todas las variables del formulario o están vacías
		echo "<p>Por favor, complete el <a href='/index.php?p=registro'>
					Formulario</a></p>";
		}else header( 'Location:http://localhost/restaurante/index.php?p=login'  );
	}
}


//genera el formulario de registro de usuario
function formRegistroUsuario() {
	echo '
	<div class="login">
  	<div class="login-triangle"></div>  
		<h2 class="login-header">Registro Usuario</h2>
		<form class="login-container" action="index.php?p=registro" method="post" name="formRegistro">
			<p><input type="text" name="nombre" placeholder="Nombre" required/></p>
			<p><input type="text" name="apellidos" placeholder="Apellidos" required/></p>
			<p><input type="text" name="telefono" id="telefono" placeholder="Teléfono" pattern="^[9|8|7|6]\d{8}$" required/></p>
			<p><input type="text" name="direccion" placeholder="Dirección" required/></p>
			<p><input type="text" name="postal" placeholder="Código postal" required/></p>
			<p><input type="text" name="municipio" placeholder="Municipio" required/></p>
			<p><input type="text" name="provincia" placeholder="Provincia" required/></p>
			<p><input type="email" name="agregarEmail" id="email" placeholder="Email" required/></p>
			<p>
				<input type="password" id="pass1" name="pass1" pattern="[A-Za-z0-9._-]{3,8}" title="Debe
					ingresar entre 3 y 8 caracteres alfanuméricos" placeholder="Password" required/>
			</p>
			<p><input type="submit" id="botonEnviar" value="Registrar"/></p>
			<p><input type="reset" value="Limpiar" /></p>
		</form>
	</div>
	<div id="mensajeErrorCampos"></div>
	<footer id="main-footer">
	';
}

?>
