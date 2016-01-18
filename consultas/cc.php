<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
<?php
  //$dbconn = pg_connect("host=".$_SESSION["host"]." dbname=".$_SESSION["db"]." user=".$_SESSION["user"]." password=".$_SESSION["123456"]) or die('No se ha podido conectar: ' . pg_last_error());
  $query = 'SELECT englishpromotionname AS nombre, count (*) as ventas FROM(SELECT dimpromotion.promotionkey, dimpromotion.englishpromotionname FROM dimpromotion, factinternetsales WHERE dimpromotion.promotionkey = factinternetsales.promotionkey AND dimpromotion.promotionkey != 1 AND duedate BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' UNION ALL SELECT dimpromotion.promotionkey, dimpromotion.englishpromotionname FROM dimpromotion, factresellersales WHERE dimpromotion.promotionkey = factresellersales.promotionkey AND dimpromotion.promotionkey != 1 AND duedate BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].') AS mitabla1 GROUP BY englishpromotionname';
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  $restulado = "";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'promocion': '".$line['nombre']."', 'compras':".$line['ventas']."},";
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