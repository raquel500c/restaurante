<?php

/*** Esta función gestiona el contenido a visualizar y todas las operaciones relacionadas con los productos y el carrito de la compra ***/
function visualizarContenido() {
	//efectua una operación según variable GET "id" recibida
	if ( isset( $_GET["id"] ) ) {
		switch ($_GET["id"]) {
		 	case 'agregar': // ENVÍA DATOS por post
		 		incluirEnCarrito();
		 		break;
		 	case 'modificar': //ENVÍA DATOS por post
		 		modificarCarrito();
		 		break;
		 	case 'procesar':
		 		procPedido();
		 		break;
		 	case 'anular':
		 		anularPedido();
		 		break;
		 	default:
		 		eliminarDeCarrito();
		 		break;
		}
	}

	//muestra contenido según variable get "ver"
	if ( isset( $_GET["carrito"] ) )
		mostrarListadoCarrito(); //get
	else
	 	mostrarListadoProductos();	//get sin ver solo p=productos

	echo '	<footer id="main-footer">';//apertura footer

}


/*Esta función procesa el pedido. Agrega en las tablas pedidos y productos_pedido de la BBDD los datos del pedido */
function procPedido() {
	//si no está conectado se indica que debe hacerlo para poder procesar pedido
	if (usuarioConectado()) {

		//obtenemos id_usuario almacenado en sesión
		$idUsuario = $_SESSION["id_usuario"];
		//insertamos en tabla pedidos, la consulta insert devuelve el id autoincrement insertado  por la bbdd para el registro
		$id_pedido = consultar( "INSERT INTO pedidos(id_usuario) VALUES($idUsuario)" );
		//si  inserta correctamente en tabla pedidos
		if( $id_pedido ) {
			//recorremos el carrito y agregamos con el id_pedido cada producto en la tabla productos_pedido
			foreach ( $_SESSION["carrito"] as $linea ) {
				$id_prod = $linea["id_prod"];
				$precio_prod = $linea["precio_prod"];
				$cantidad = $linea["cantidad"];
				//insertamos en tabla productos_pedido
				$resultado = consultar( "INSERT INTO productos_pedido( id_pedido, id_prod, precio_venta, cantidad)
								 VALUES( $id_pedido, $id_prod, $precio_prod ,$cantidad )" );
			}

			//eliminamos carrito
			unset( $_SESSION["carrito"] );
			print "<script>alert('Venta procesada exitosamente')</script>";
		} else
			print "<script>alert('Se ha producido un problema al registrar su pedido. Por favor, inténtelo de nuevo')</script>";
	} else	{
		echo "
			<script>
				alert('Debe conectarse a su cuenta para hacer el pedido');
				window.location='index.php?p=login';
			</script>
		";
	}
}

function modificarCarrito() {
	if ( isset( $_POST["id_prod"] ) && isset( $_POST["modCantidad"] ) ) {
		//almacenamos clave que corresponde al id del producto recibido
		$clave = $_POST["id_prod"];
		$modCantidad = $_POST["modCantidad"];
		//actualizamos cantidad en carrito para ese registro
		$_SESSION["carrito"] [$clave] ["cantidad"] = $modCantidad;
	}
}

/*Esta función elimina del carrito con el id recibido por Get*/
function eliminarDeCarrito() {
		//si solo hay un producto en el carrito eliminamos sesion carrito
		if ( count($_SESSION["carrito"] ) <=1 ) unset( $_SESSION["carrito"]);
		//si hay varios productos
		else {
		//almacenamos clave que corresponde al id del producto recibido
		$clave = $_GET["id"];
		//eliminamos el registro con esa clave dentro del carrito
		unset($_SESSION["carrito"] [$clave]);
		}
}


/*** Esta función genera tabla con listado de productos agregados al carrito o indica que está vacío ***/
function mostrarListadoCarrito(){
	//apertura de contenedores y título
	echo '
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>Carrito</h1>
	';
	//agregamos un enlace para ir a productos
	echo '
				<a href="index.php?p=productos" class="btn btn-default"> Productos</a>
				<br><br>
	';

	//si existen productos en sesión carrito los mostraremos en una tabla
	if ( isset( $_SESSION["carrito"] ) && !empty( $_SESSION["carrito"] ) ) {
		//creamos comienzo tabla y encabezados
		echo '
				<table class="table table-bordered">
					<thead>
						<th>Producto</th>
						<th>Precio</th>
						<th>Cantidad</th>
						<th>Total</th>
						<th></th>
					</thead>';
		//recorremos lineas del carrito y mostramos datos de cada producto
		foreach( $_SESSION["carrito"] as $linea ) {
			echo "	<tr>
						<td>" . $linea["nombre_prod"] . "</td>
						<td>" . $linea["precio_prod"] . " €</td>
						<th>
						<form action='index.php?p=productos&carrito&id=modificar' method='POST'>
						<input type='hidden' name='id_prod' value='" . $linea["id_prod"] . "' />
						<input type='number' name='modCantidad' min='1' max='10' value='". $linea["cantidad"] ."' />
						<input type='submit' value='modificar' />
						</form>
						</th>
			";
			//calculamos el precio total por cada producto y lo mostramos
			$total = $linea["cantidad"] * $linea["precio_prod"];
			echo "
						<td>". $total . " €</td>
						<td >
			";
						//creamos un enlace para eliminar cada producto  si se desea
			echo "		<a href=\"index.php?p=productos&carrito&id=" . $linea["id_prod"] . "\" class=\"btn btn-danger\">Eliminar</a>
						</td>

					</tr>
			";
		}
		//fuera del foreach cerramos tabla y añadimos un formulario de envío de datos para procesar pedido
		echo '	</table>

				<form class=\"form-horizontal\" method="POST" action="index.php?p=productos&id=procesar">
						<div class="form-group">

							<div class="col-sm-5">

							</div>
						</div>
							<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Procesar Pedido</button>
							</div>
						</div>
					</form>
		';
	//si no hay producto indicamos que el carrito está vacío
	} else
		echo '<p class="alert alert-warning">El carrito esta vacio.</p>';
	//cierre de contenedores
	echo'
			<br><br><hr>
			</div>
		</div>
	</div>

	';
}


/*** Esta función incluye en carrito el producto con los datos recibidos por post junto a extraidos de BBDD ***/
function incluirEnCarrito() {
	if ( isset( $_POST["id_prod"] ) && isset( $_POST["cantidad"] ) ) {
		//pasamos a variables
		$id_prod = $_POST["id_prod"];
		//sumamos 0 para convertir variable a numérica y posteriormente poder operar
		$cantidad = 0 + $_POST["cantidad"];

		//Preparamos la orden SQL para extraer resto de datos del producto:
		$query = "
				SELECT nombre_prod, precio_prod
				FROM productos
				WHERE id_prod = $id_prod
				";

		//Ejecutamos consulta:
		$resultado = consultar( $query );

		//Al tratarse de un SELECT, la consulta devolverá un array $resultado con un registro
		if( mysqli_num_rows( $resultado ) == 1 ) {
			//almacenamos datos del producto
			$fila = mysqli_fetch_array( $resultado, MYSQLI_ASSOC ) ;
			$nombre_prod = $fila["nombre_prod"];
			$precio_prod = $fila["precio_prod"];

			//creamos clave para registrar linea en carrito, equivalente al id del producto.
			$clave = $id_prod;

			//  buscamos si ya existe algún registro con esa clave:
			if (@array_key_exists ( $clave, $_SESSION["carrito"] ) ) {
			//almacenamos en variable la cantidad que hay en carrito
			$cantidadCarrito = $_SESSION["carrito"] ["$clave"] ["cantidad"];
			//sumamos cantidades del carrito y de la nueva petición
			$Suma =  $cantidadCarrito + $cantidad;
			//actualizamos cantidad en carrito para ese registro, con variable casteada a string
			$_SESSION["carrito"] [$clave] ["cantidad"] = (string)$Suma;

			}else {
			// si el producto no esta todavía en carrito entonces simplemente agregamos un nuevo registro completo con su clave
			$_SESSION["carrito"] [$clave] = array( "id_prod"=>$id_prod, "nombre_prod"=>$nombre_prod, "precio_prod"=>$precio_prod,
			"cantidad"=>$cantidad  ) ;
			}
		}
	}
}


/*** Esta función genera estructura de página productos con datos extraidos de la BBDD ***/
function mostrarListadoProductos() {
 	//apertura de contenedores y título de página
 	echo '
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>Productos</h1>
	';

	//agregamos enlace para ver el contenido del carrito y cierre de contenedores
	echo '
				<a href="index.php?p=productos&carrito" class="btn btn-warning">Ver Carrito</a>
			</div>
		</div>
	<div>
	';

	//obtenemos de BBDD todos los productos
 	$resultado = consultar( "SELECT * FROM productos" );
 	//recorremos todas las filas de productos
 	while ( $fila = mysqli_fetch_array( $resultado, MYSQLI_ASSOC ) ) {
 	//mostramos datos para cada producto
	 	echo  '
		<div class="responsive">
		  	<div class="img">
			  	<img src="/restaurante/images/' . $fila["ruta_imagen"] . '" alt="'
						. $fila["nombre_prod"] . '" width="300" height="300">
				<div class="desc">'. $fila["nombre_prod"] . '</div>
				<div class="desc">'. $fila["precio_prod"] . ' &euro;</div>';

	/*incluimos form por cada producto para enviar cantidad a agregar al carrito
				junto  al id del producto oculto*/
	echo  '
				<form  method="POST" action="index.php?p=productos&id=agregar">
					<input type="hidden" name="id_prod" value="' . $fila["id_prod"] . '">
			  		<div class="form-group">
				    	<input type="number" name="cantidad" min="1" max="10" value=1 required>
				    	<button type="submit" id="btn-agregar" class="btn btn-primary">Agregar al Carrito</button>
				 	</div>
				</form>
			</div>
		</div>';
	} //endwhile
}

?>
