<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
<script src="../static/amcharts/pie.js"></script>
<script src="../static/amcharts/themes/light.js"></script>
<script>
  var chart;
  var chartData = [
    <?php 
      $query = 'SELECT COUNT(f.salesordernumber) as ventas_internet FROM (SELECT fis.salesordernumber FROM factinternetsales fis, dimdate dt WHERE dt.datekey = duedatekey AND dt.datekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' GROUP BY fis.salesordernumber) as f';
      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      $resultado = "";
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultado = "{'clase': 'Ventas por internet','valor': ".$line['ventas_internet']."},";
      } 
      $query = 'SELECT f.employeekey,f.firstname, COUNT(f.salesordernumber) as ventas FROM (SELECT frs.salesordernumber, de.employeekey,de.firstname FROM dimemployee de, factresellersales frs,dimdate dt WHERE dt.datekey = duedatekey AND dt.datekey BETWEEN '.$_GET["desde"].' AND '.$_GET["hasta"].' AND frs.employeekey = de.employeekey GROUP BY frs.salesordernumber, de.employeekey) as f GROUP BY f.employeekey,f.firstname';
      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $resultado= $resultado."{'clase': '".$line['employeekey']." - ".$line['firstname']."', 'valor':".$line['ventas']."},";
      }
      $resultado = rtrim($resultado, ",");
      echo $resultado."];\n";
  pg_free_result($result);
?>
var chart = AmCharts.makeChart("chartdiv", {
  type: "pie",
  "startDuration": 0,
  dataProvider: chartData,
  "theme": "light",
  "addClassNames": true,
  "legend":{
    "position":"right",
    "marginRight":100,
    "autoMargins":false
  },
  "innerRadius": "30%",
  "defs": {
    "filter": [{
      "id": "shadow",
      "width": "200%",
      "height": "200%",
      "feOffset": {
        "result": "offOut",
        "in": "SourceAlpha",
        "dx": 0,
        "dy": 0
      },
      "feGaussianBlur": {
        "result": "blurOut",
        "in": "offOut",
        "stdDeviation": 5
      },
      "feBlend": {
        "in": "SourceGraphic",
        "in2": "blurOut",
        "mode": "normal"
      }
    }]
  },
  "valueField": "valor",
  "titleField": "clase",
  "export": {
    "enabled": true
  }
  

});
chart.addListener("init", handleInit);
chart.addListener("rollOverSlice", function(e) {
  handleRollOver(e);
});

function handleInit(){
  chart.legend.addListener("rollOverItem", handleRollOver);
}
function handleRollOver(e){
  var wedge = e.dataItem.wedge.node;
  wedge.parentNode.appendChild(wedge);  
}
</script>
<!-- scripts for exporting chart as an image -->
<!-- Exporting to image works on all modern browsers except IE9 (IE10 works fine) -->
<!-- Note, the exporting will work only if you view the file from web server -->
<!--[if (!IE) | (gte IE 10)]> -->
<script type="text/javascript" src="../static/amcharts/plugins/export/export.js"></script>
<link  type="text/css" href="../static/amcharts/plugins/export/export.css" rel="stylesheet">
<!-- <![endif]-->
<div id="chartdiv" style="width: 100%; height: 1000px;"></div>