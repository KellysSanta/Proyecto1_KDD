<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
<?php
  $tipo = $_GET['tipo'];

  if($tipo=="anho"){
    $query ='SELECT EXTRACT (YEAR FROM duedate) AS anho, SUM(totalproductcost)::numeric AS sales FROM factresellersales WHERE duedatekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY anho ORDER BY anho;';
    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultado= $resultado."{'anho': '".$line['anho']."', 'sales':".str_replace(",",".",$line['sales'])."},";
    }
    
  }
  if ($tipo=="semestre"){
    $query= 'SELECT calendarsemester AS semestre, SUM(totalproductcost)::numeric AS sales FROM dimdate JOIN factresellersales ON  duedatekey=datekey WHERE duedatekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY semestre;';
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultado= $resultado."{'anho': '".$line['semestre']."', 'sales':".str_replace(",",".",$line['sales'])."},";
    }
  }
  if ($tipo=="mes"){
    $query='SELECT nombremes,sales::numeric FROM (SELECT spanishmonthname AS nombremes, monthnumberofyear AS mesfecha FROM dimdate GROUP BY  mesfecha, nombremes) t1 LEFT JOIN (SELECT EXTRACT (MONTH FROM duedate) AS mes, SUM (totalproductcost) AS sales FROM factresellersales WHERE duedatekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY mes)  t2 ON (t2.mes = t1.mesfecha) ORDER BY mesfecha;';
    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultado= $resultado."{'anho': '".$line['nombremes']."', 'sales':".str_replace(",",".",$line['sales'])."},";
    }
  }
  if ($tipo=="trimestre"){
    $query='SELECT calendarquarter AS trimestre, SUM(totalproductcost)::numeric AS sales FROM dimdate JOIN factresellersales ON  duedatekey=datekey WHERE duedatekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY trimestre;';
    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultado= $resultado."{'anho': '".$line['trimestre']."', 'sales':".str_replace(",",".",$line['sales'])."},";
    }
  }
  $resultado = rtrim($resultado, ",");
    echo $resultado."];\n";
    pg_free_result($result);
  
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  "theme": "light",
  dataProvider: chartData,
  categoryField: "anho",
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
    "title" : "Ventas"
  }],

  graphs: [{
    "balloonText": "[[category]]: <b>[[value]]</b>",
    valueField: "sales",
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