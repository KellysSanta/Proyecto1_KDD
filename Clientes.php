<!DOCTYPE html>
<?php 
include "conexion.php";?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Bice-Bykes Bienvenido!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/clientesStyle.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="shortcut icon" href="img/icono.jpg">
<link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
  
	
</head>

<body>


<div class="container">

	<nav>
		<ul class="menu_principal">
			<li><a class="principal">Bici-Bykes</a></li>
			<li class="derecha active"><a class="principal" href="index.php">Salir</a></li>
			<li class="derecha active"><a class="principal" href="menuReportes.php">Menú</a></li>
		</ul>

	</nav>



		<div class="columna1">
			<div class="cuadros">
					<div class=logo>
						<img class="img-circulo" alt="140x140" src="img/icono.jpg">
					</div>
					<div class="nombre">
						<a  href="#"><h1>Clientes</h1></a>
					</div>
			</div>
			<div>
				<nav class="navPregunta">
					<ul class="pregunta">
						<li><a class="principal2" href="#" id="mostrarPregunta1">Pregunta1</a></li>
						</a></li>
					</ul>

				</nav>
			</div>
			<form class="pregunta1" role="form" id="preguntaUno">
				<div class="cuadros2" >
					<h3>Respuesta1</h3>
				</div>
			</form>		

		</div>


	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="js/clientesScripts.js"></script>
</body>
</html>

			


			
