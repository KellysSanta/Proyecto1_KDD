<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
<?php
  //$dbconn = pg_connect("host=".$_SESSION["host"]." dbname=".$_SESSION["db"]." user=".$_SESSION["user"]." password=".$_SESSION["123456"]) or die('No se ha podido conectar: ' . pg_last_error());
  $query = 'SELECT g.currencykey, g.currencyname, SUM(g.cuantos) as cuantos FROM (SELECT f.currencykey, f.currencyname, COUNT(f.currencykey) as cuantos FROM (SELECT frs.salesordernumber, dc.currencyname, frs.currencykey FROM factresellersales as frs, dimcurrency dc, dimdate dt WHERE dt.datekey = duedatekey AND dt.datekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' AND dc.currencykey= frs.currencykey GROUP BY frs.salesordernumber, frs.currencykey, dc.currencyname) f GROUP BY f.currencykey, f.currencyname UNION  SELECT f.currencykey, f.currencyname, COUNT(f.currencykey) as cuantos FROM (SELECT fis.salesordernumber, dc.currencyname, fis.currencykey FROM factinternetsales as fis, dimcurrency dc, dimdate dt WHERE dt.datekey = duedatekey AND dt.datekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' AND dc.currencykey= fis.currencykey GROUP BY fis.salesordernumber,fis.currencykey, dc.currencyname) f GROUP BY f.currencykey, f.currencyname) as g WHERE g.currencykey IN (';
  for($i = 0; $i<count($_GET["monedas"]);$i++){
    $query = $query."".$_GET["monedas"][$i].",";
  }
  $query = substr ($query, 0, -1).") GROUP BY g.currencykey, g.currencyname";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  $restulado = "";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'moneda': '".$line['currencyname']."', 'cuantos':".$line['cuantos']."},";
  }
  $resultado = rtrim($resultado, ",");
  echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  "theme": "light",
  dataProvider: chartData,
  categoryField: "moneda",
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
    "title" : "Veces Usada"
  }],

  graphs: [{
    "balloonText": "[[category]]: <b>[[value]]</b>",
    valueField: "cuantos",
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