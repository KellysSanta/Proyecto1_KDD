<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script>
  var chart;
  var chartData = [
<?php
  //$dbconn = pg_connect("host=".$_SESSION["host"]." dbname=".$_SESSION["db"]." user=".$_SESSION["user"]." password=".$_SESSION["123456"]) or die('No se ha podido conectar: ' . pg_last_error());
  $query = 'SELECT salesterritorycountry AS country, SUM(totalproductcost)::numeric AS sales FROM dimsalesterritory JOIN factresellersales ON dimsalesterritory.salesterritorykey=factresellersales.salesterritorykey GROUP BY salesterritorycountry;';
  for($i = 0; $i<count($_GET["promociones"]);$i++){
    $query = $query."".$_GET["promociones"][$i].",";
  }
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  $restulado = "";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'country': '".$line['country']."', 'sales':".str_replace(",", ".",$line['sales']).'},';
  }
  $resultado = rtrim($resultado, ",");
  echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  dataProvider: chartData,
  categoryField: "country",
  depth3D: 20,
  angle: 30,

  categoryAxis: {
      labelRotation: 90,
      gridPosition: "start"
  },

  valueAxes: [{
      title: "Ventas por paises"
  }],

  graphs: [{
      valueField: "sales",
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