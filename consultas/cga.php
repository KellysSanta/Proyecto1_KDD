<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
<?php
  $query = 'SELECT nombrecategoria, SUM (salesproducto)::numeric as salesproducto FROM (SELECT spanishproductcategoryname AS nombrecategoria,productcategorykey FROM dimproductcategory) t3 JOIN (SELECT nombresubcategoria,pkcat, salesproducto FROM (SELECT spanishproductsubcategoryname AS nombresubcategoria, productsubcategorykey AS pksub, productcategorykey AS pkcat FROM dimproductsubcategory) t1 JOIN (SELECT productsubcategorykey AS llavesubcategoria, SUM (totalproductcost) AS salesproducto FROM dimproduct JOIN factinternetsales ON dimproduct.productkey=factinternetsales.productkey WHERE duedatekey  BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].'GROUP BY llavesubcategoria ) t2 ON (t2.llavesubcategoria = t1.pksub)) t4 ON (t4.pkcat= t3.productcategorykey) GROUP BY nombrecategoria;';
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'nombrecategoria': '".$line['nombrecategoria']."', 'salesproducto':".str_replace(",", ".",$line['salesproducto'])."},";
  }
  $resultado = rtrim($resultado, ",");
  echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  "theme": "light",
  dataProvider: chartData,
  categoryField: "nombrecategoria",
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
    "title" : "Producto Por Categorias"
  }],

  graphs: [{
    "balloonText": "[[category]]: <b>[[value]]</b>",
    valueField: "salesproducto",
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