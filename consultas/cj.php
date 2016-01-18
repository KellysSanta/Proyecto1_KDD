<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
<?php
  //$dbconn = pg_connect("host=".$_SESSION["host"]." dbname=".$_SESSION["db"]." user=".$_SESSION["user"]." password=".$_SESSION["123456"]) or die('No se ha podido conectar: ' . pg_last_error());
  $query = 'SELECT temporada, ventas FROM (SELECT sum(ventas) as ventas, 'Primavera' as temporada FROM(SELECT count(*) AS ventas FROM factinternetsales WHERE EXTRACT (MONTH FROM duedate) BETWEEN 3 AND 6AND EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' UNION ALL SELECT count(*) AS ventas FROM factresellersales WHERE EXTRACT (MONTH FROM duedate) BETWEEN 3 AND 6 AND EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].') as mitablita UNION ALL SELECT sum(ventas) as ventas, 'Verano' as temporada FROM(SELECT count(*) AS ventas FROM factinternetsales WHERE EXTRACT (MONTH FROM duedate) BETWEEN 6 AND 9 AND EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' UNION ALL SELECT count(*) AS ventas FROM factresellersales WHERE EXTRACT (MONTH FROM duedate) BETWEEN 6 AND 9 AND EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].') as mitablita2 UNION ALL SELECT sum(ventas) as ventas, 'Otono' as temporada FROM(SELECT count(*) AS ventas FROM factinternetsales WHERE EXTRACT (MONTH FROM duedate) BETWEEN 9 AND 12 AND EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' UNION ALL SELECT count(*) AS ventas FROM factresellersales WHERE EXTRACT (MONTH FROM duedate) BETWEEN 9 AND 12 AND EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].') as mitablita3 UNION ALL SELECT sum(ventas) as ventas, 'Invierno'  as temporada FROM(SELECT count(*) AS ventas FROM factinternetsales WHERE EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' AND EXTRACT (MONTH FROM duedate) BETWEEN 1 AND 3 OR EXTRACT (MONTH FROM duedate) = 12 UNION ALL SELECT count(*) AS ventas FROM factresellersales WHERE EXTRACT (YEAR FROM duedate) BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' AND EXTRACT (MONTH FROM duedate) BETWEEN 1 AND 3 OR EXTRACT (MONTH FROM duedate) = 12) as mitablita4) as mitablitafinal';
  for($i = 0; $i<count($_GET["promociones"]);$i++){
    $query = $query."".$_GET["promociones"][$i].",";
  }
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  $restulado = "";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'promocion': '".$line['temporada']."', 'compras':".$line['ventas']."},";
  }
  $resultado = rtrim($resultado, ",");
  echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  dataProvider: chartData,
  categoryField: "promocion",
  depth3D: 20,
  angle: 30,

  categoryAxis: {
      labelRotation: 90,
      gridPosition: "start"
  },

  valueAxes: [{
      title: "Ventas"
  }],

  graphs: [{
      valueField: "compras",
      type: "column",
      lineAlpha: 0,
      fillAlphas: 1
  }],

  chartCursor: {
      cursorAlpha: 0,
      zoomable: true,
      categoryBalloonEnabled: true
  },
  "export": {
      "enabled": true
  }

});
</script>
<!-- scripts for exporting chart as an image -->
<!-- Exporting to image works on all modern browsers except IE9 (IE10 works fine) -->
<!-- Note, the exporting will work only if you view the file from web server -->
<!--[if (!IE) | (gte IE 10)]> -->
<script type="text/javascript" src="../static/amcharts/plugins/export/export.js"></script>
<link  type="text/css" href="../static/amcharts/plugins/export/export.css" rel="stylesheet">
<!-- <![endif]-->
<div id="chartdiv" style="width: 100%; height: 1000px;"></div>