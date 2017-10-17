<?php
/* Este fichero muestra toda la actividad de los pedidos. Se encuentra en construcción. 
 */
function visualizarContenido() {
	//mostramos título y sección en obras
	echo "
		<h1>Pedidos realizados</h1>	
		<p class=\"en-obras\">Sección en obras</strong></p>
		<img src=\"images/obras.gif\" alt=\"obras\" />		
		";

	//Obtenemos datos a mostrar mediante consulta a distintas tablas de la BBDD
	$resultado = consultar( "
		SELECT ped.id_pedido, ped.fecha_hora, usu.id_usuario, usu.nombre, usu.apellidos, usu.email, prp.id_prod, prod.nombre_prod, prp.cantidad, prp.precio_venta
		FROM productos_pedido AS prp, productos AS prod, pedidos AS ped, usuarios AS usu
		WHERE  ped.id_usuario = usu.id_usuario AND  
		 prp.id_pedido = ped.id_pedido  AND prod.id_prod = prp.id_prod 
		"
	);
	//generamos tabla y encabezados
 	echo "
		<table>
			<thead>
				<th>Nº Pedido</th>
				<th>Fecha</td>
				<th>Id Usuario</th>
				<th>Nombre y apellidos</th>
				<th>Email</td>
				<th>Id Producto</th>
				<th>Producto</th>
				<th>Cantidad</th>
				<th>Precio Venta</th>
				<th>Total</th>
			</thead>
		";
	
	while ( $pedido = mysqli_fetch_array( $resultado, MYSQLI_ASSOC ) ) {
		//almacenamos en variables y forzamos a que sean numéricas para poder calcular el total por producto
		$precioVenta = 0 + $pedido["precio_venta"];
		$cantidad = 0 + $pedido["cantidad"];
		//almacenamos total
		$totalProd = $precioVenta * $cantidad;
		//mostramos resultados
		echo "
			<tr>
			<td>" . $pedido["id_pedido"] . "</td>
			<td>" . $pedido["fecha_hora"] . "</td>
			<td>" . $pedido["id_usuario"] . "</td>
			<td>" . $pedido["nombre"] . " " . $pedido["apellidos"] . "</td>
			<td>" . $pedido["email"] . "</td>
			<td>" . $pedido["id_prod"] . "</td>
			<td>" . $pedido["nombre_prod"] . "</td>
			<td>" . $pedido["cantidad"] . "</td>
			<td>" . $pedido["precio_venta"] . "</td>
			<td>" . $totalProd . "</td>
			</tr>
			";
	}
	//fuera del bucle cerramos tabla
	echo"
		</table>
		";
}

?>
