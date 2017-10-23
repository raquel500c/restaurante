<?php
/* Este fichero procesa  el acceso de usuarios inicio de variables de sesion y el cierre de sesion
 * La acción depende de las variables get y post que recibe.
 */

function visualizarContenido() {
	if ( isset( $_POST["validarEmail"] ) )
		procValidarUsuario();//procesa el registro de un nuevo usuario en la bbdd
	else
		formAccesoUsuario();//visualiza el formulario de login de usuario
}

//verifica el login de un usuario, si es correcto muestra usuario conectado
function procValidarUsuario() {
	global $resultado, $codigo;
	//Validamos que han llegado las variables desde el formulario de login, y que no están vacías
	if ( isset( $_POST ["validarEmail"], $_POST ["pass1"] ) and $_POST ["validarEmail"] <> ""
		and $_POST ["pass1"] <> "" ) {
		/*Abrimos conexión para obtener un objeto $con, que pasaremos como parámetro
		a mysqli_real_escape_string($con,$_POST["string"]); */
		$con = conectarBase();
		//Traspasamos a variables locales, y controlamos posible inyección de SQL
		$email = mysqli_real_escape_string( $con, $_POST [ "validarEmail" ] );
		$password = mysqli_real_escape_string( $con, $_POST [ "pass1" ] );
		//cerramos conexión
		mysqli_close($con);

		//Preparamos la orden SQL:
		$query = "
			SELECT id_usuario, rol, nombre, email, password
			FROM usuarios
			WHERE email = '$email' and password = '$password' and is_active = 1
			";

		//Ejecutamos función consultar:
		consultar( $query );
		//Al tratarse de un SELECT, la consulta devolverá un array $resultado, si encuentra registro
		if( mysqli_num_rows( $resultado ) == 1 ) {
			//almacenamos datos del usuario en variables de sesión
			while ( $fila = mysqli_fetch_array( $resultado, MYSQLI_ASSOC ) ) {
				$_SESSION ["rol"] = utf8_encode( $fila ["rol"] );
				$_SESSION ["nombre"] = utf8_encode( $fila ["nombre"] );
				$_SESSION ["id_usuario"] = utf8_encode( $fila ["id_usuario"] );
			}
			//si es usuario válido se redirecciona a página productos para que inicie pedido
			header( 'Location:http://localhost/restaurante/index.php?p=productos' );
		//Si no coincide con usuario registrado
		} else echo "Compruebe sus datos de email y contraseña";
	} else {
		//si no han llegado todas las variables del formulario o están vacías
		echo "
			<p>Por favor, complete datos para acceder
			<a href='index.php?p=login.php'></a>
			</p>
			";
	}

} //end procValidarUsuario

function formAccesoUsuario() {
	echo '
		<div class="login">
		  <div class="login-triangle"></div>
			<h2 class="login-header">Log in</h2>
			  <form class="login-container" action="index.php?p=login" method="post" name="form_login">
				<p><input type="email" placeholder="Email" name="validarEmail" required></p>
				<p><input type="password" placeholder="Password" name="pass1" required></p>
				<p><input type="submit" value="Log in"></p>
			  </form>
		</div>
		<p id="reg">¿No estás registrado? <a href="index.php?p=registro">Reg&iacute;strate</a></p>
		<footer id="main-footer">
	';
}

?>
