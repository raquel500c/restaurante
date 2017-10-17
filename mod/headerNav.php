	<header id="main-header">

		<span id="logo-ico"><img src="images/speedy.png"><img src="images/cantinflas.png"></span>
		
		<a id="logo-header" href="index.php">			
			<span class="site-name">Speedy-Cantinflas</span>
			<span class="site-desc">Comida Mexicana a domicilio</span>
		</a> <!-- / #logo-header -->
	
		<nav>
	 		<a id="pull" href="#"></a>
			<ul>
			<li><a id="inicio" href="index.php">Speedy-Cantinflas</a></li>				
				<li><a href="index.php?p=productos">Productos</a></li>
				<?php if ( !usuarioConectado() ) : ?>
				<li><a href="index.php?p=login">Acceder</a></li>
				<li><a href="index.php?p=registro">Registrarse</a></li>
				<?php else : ?>				
				<li>Usuario conectado: <?php echo $_SESSION['nombre']?> </li>
				<?php if ( usuarioEsCliente() ) : ?>
				<li><a href="index.php?p=logout">Salir</a></li>
				<?php endif; ?>	
				<?php if ( usuarioEsAdmin() ): ?>	
				<li><a id="panel" href="admin/index.php">PANEL ADMINISTRADOR</a></li>
				<?php endif; ?>	
				<?php if  ( usuarioEsTrabajador() ): ?>	
				<li><a id="panel" href="admin/index.php">PANEL EMPLEADOS</a></li>
				<?php endif; ?>				
				<?php endif; ?>
				<br clear="all"/>
			</ul>

		</nav> 
	</header>
	




