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
function cincoMejoresEventos(){
$sql_query = "select nombre, count(*) total_participantes from 
				evento inner join Invitacion_evento as ie on evento.id_evento = ie.id_evento and ie.asistir = true 
				group by nombre 
				order by total_participantes  desc;";
$consulta = pg_query($sql_query);
$counter = 0;
while($fila=pg_fetch_row($consulta) and $counter <5){
	$counter = $counter +1;
	echo "<div class='sesion_formulario'>
		<label class='label'>Evento:</label>
		<input class='input' type='text' OnFocus='this.blur()' value = '$fila[0]'>
	    <label class='label'>Total:</label>
		<input class='input2' type='text' OnFocus='this.blur()'  value = '$fila[1]'>
	</div>";
}
}?>