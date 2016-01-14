<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
<?php
  //$dbconn = pg_connect("host=".$_SESSION["host"]." dbname=".$_SESSION["db"]." user=".$_SESSION["user"]." password=".$_SESSION["123456"]) or die('No se ha podido conectar: ' . pg_last_error());
  $query = 'SELECT f.id,f.nombre,SUM(f.compras) as compras FROM  (SELECT dp.promotionkey as id, dp.spanishpromotionname as Nombre, COUNT(dp.promotionkey) as compras FROM factinternetsales as fis, dimpromotion dp, dimdate dd  WHERE dd.datekey = fis.orderdatekey AND dp.promotionkey = fis.promotionkey AND dd.datekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY  dp.promotionkey UNION  SELECT dp.promotionkey as id, dp.spanishpromotionname as Nombre, COUNT(dp.promotionkey) as compras FROM factresellersales as frs, dimpromotion dp, dimdate dd WHERE dd.datekey = frs.orderdatekey AND dp.promotionkey = frs.promotionkey AND dd.datekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY  dp.promotionkey) as f WHERE f.id IN (';
  for($i = 0; $i<count($_GET["promociones"]);$i++){
    $query = $query."".$_GET["promociones"][$i].",";
  }
  $query = substr ($query, 0, -1).") GROUP BY f.id, f.nombre";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  $restulado = "";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'promocion': '".$line['nombre']."', 'compras':".$line['compras']."},";
  }
  $resultado = rtrim($resultado, ",");
  echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  "theme": "light",
  dataProvider: chartData,
  categoryField: "promocion",
  "gridAboveGraphs": true,
  "startDuration": 1,
  categoryAxis: {
      labelRotation: 90,
      gridPosition: "start",
    "gridAlpha": 0,
    "tickPosition": "start",
    "tickLength": 20
  },

  valueAxes: [{
  	"gridColor": "#FFFFFF",
    "gridAlpha": 0.2,
    "dashLength": 0,
    "title" : "Compras"
  }],

  graphs: [{
    "balloonText": "[[category]]: <b>[[value]]</b>",
    valueField: "compras",
    type: "column",
    lineAlpha: 0.2,
    fillAlphas: 0.8
  }],

  "chartCursor": {
    "categoryBalloonEnabled": false,
    "cursorAlpha": 0,
    "zoomable": false
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