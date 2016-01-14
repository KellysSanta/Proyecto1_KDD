<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script>
  var chart;
  var chartData = [
<?php

  //$dbconn = pg_connect("host=".$_SESSION["host"]." dbname=".$_SESSION["db"]." user=".$_SESSION["user"]." password=".$_SESSION["123456"]) or die('No se ha podido conectar: ' . pg_last_error());
  $query = "select (a.accountdescription || ' - ' || d.departmentgroupname) as nombre,  to_char(f.am,'9999999999999999999999.9999') as am from dimaccount AS a join (select accountkey, departmentgroupkey, sum(amount) as am from factfinance group by accountkey, departmentgroupkey) as f on f.accountkey = a.accountkey join dimdepartmentgroup as d on f.departmentgroupkey = d.departmentgroupkey where f.DateKey between '".$_GET["desde"]."' and '".$_GET["hasta"]."' order by am desc limit ".$_GET["limite_pr"].";";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  $restulado = "";
  while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $resultado= $resultado."{'descripcion': '".$line['nombre']."', 'valor':".$line['am']."},";
  }
  $resultado = rtrim($resultado, ",");
  echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "serial",
  dataProvider: chartData,
  categoryField: "descripcion",
  depth3D: 20,
  angle: 30,

  categoryAxis: {
      labelRotation: 90,
      gridPosition: "start"
  },

  valueAxes: [{
      title: "Valor"
  }],

  graphs: [{
      valueField: "valor",
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
<div id="chartdiv" style="width: 100%; height: 400px;"></div>