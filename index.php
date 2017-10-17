<?php
session_start();
// Usaremos cadenas UTF-8 hasta el final
mb_internal_encoding('UTF-8');
// Generaremos cadenas UTF-8
mb_http_output('UTF-8');

//Incluimos los datos de conexión y las funciones:
require_once("func/datosConexion.php");
require_once("func/funcConexion.php");
require_once("func/funcConsultas.php");
require_once("func/funcSesiones.php");
//require_once("func/funcUsuarios.php");
//require_once("func/funcRegistro.php");
require_once("func/funcPaginas.php");
//require_once("func/funcProductos.php");
require_once("admin/func/funcAdmin.php");

//Incluimos los módulos html
require_once("mod/head.php");
require_once("mod/headerNav.php");

//Obtenemos la variable de la página de la interfaz de cliente que se debe mostrar en el navegador
$pagina = getPagina();

//Cargamos la página a mostrar
require_once(cargar($pagina));

visualizarContenido();

//Incluimos resto de módulos html
require_once("mod/footer.php");

?>	
