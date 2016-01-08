<?php //Creamos las variables de conexion
$server = "localhost";
$db_name = "";
$log = "postgres";
$pass = "";
$port = "5432";
$cadena_con= "host=$server port=$port dbname=$db_name user=$log password=$pass";

//Conectamos con la cadena de conexion
$con = pg_connect($cadena_con) or die ('Ha fallado la conexion');
?>




<?php /************************************************************************************************************/ ?>

<?php
//Nombre de la función
function cincoMejoresEventos(){
//Sentencia sql
$sql_query = "select nombre, count(*) total_participantes from 
				evento inner join Invitacion_evento as ie on evento.id_evento = ie.id_evento and ie.asistir = true 
				group by nombre 
				order by total_participantes  desc;";
//hacemos la consulta				
$consulta = pg_query($sql_query);
/*contador*/
$counter = 0;
//condición while para cuando existan varios resultados o filas de resultados
while($fila=pg_fetch_row($consulta) and $counter <5){
	$counter = $counter +1;
	//insercción html (es lo que aparecera en la página)
	echo "<div class='sesion_formulario'>
		<label class='label'>Evento:</label>
		<input class='input' type='text' OnFocus='this.blur()' value = '$fila[0]'>
	    <label class='label'>Total:</label>
		<input class='input2' type='text' OnFocus='this.blur()'  value = '$fila[1]'>
	</div>";
}
}?>