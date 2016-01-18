<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <meta charset = "utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"  href="/static/css/bootstrap.min.css">
    <link rel="stylesheet"  href="/static/css/metisMenu.min.css" >
    <link rel="stylesheet"  href="/static/css/sb-admin-2.css">
    <link href="/static/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/static/css/dataTables.responsive.css" rel="stylesheet"> 
    <link href="/static/css/chosen.css" rel="stylesheet" >
    <link href="/static/css/bootstrap-social.css" rel="stylesheet">
    <link href="/static/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="/static/js/jquery.js"></script>
  <title>KDD</title>
</head>
<body>
  <div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>   
            <a class="navbar-brand" href="../menuReportes.php">KDD - Ventas</a>
        </div>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                  <li><a  href="#" onclick="actualizar('cc_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="Comparación de ventas por promoción"><i class=" fa fa-industry fa-fw"></i> Comp. Promocion</a></li>
                  <li><a  href="#" onclick="actualizar('ch_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="Comparación de ventas por producto"><i class=" fa fa-industry fa-fw"></i> Comp. Producto</a></li>
                  <li><a  href="#" onclick="actualizar('cj_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="Comparación de ventas por volumen frente a ventas por temporada"><i class=" fa fa-industry fa-fw"></i> Comp. Volumen</a></li>
                  <li><a  href="#" onclick="actualizar('ce_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="Comparativo entre ventas por Internet y los vendedores de la empresa"><i class=" fa fa-industry fa-fw"></i> Comparativo</a></li>
                  <li><a  href="#" onclick="actualizar('')" data-toggle="tooltip" data-placement="right" data-original-title="Nivel de ventas por año,mes, trimestre y semestre"><i class=" fa fa-industry fa-fw"></i> Nivel de ventas</a></li>
                  <li><a  href="#" onclick="actualizar('')" data-toggle="tooltip" data-placement="right" data-original-title="Total de ventas de productos agrupados por categoría"><i class=" fa fa-industry fa-fw"></i> Total por productos</a></li>
                  <li><a  href="#" onclick="actualizar('lb.php')" data-toggle="tooltip" data-placement="right" data-original-title="Total de ventas agrupado por países en internet"><i class=" fa fa-industry fa-fw"></i> Total por paises internet</a></li>
                  <li><a  href="#" onclick="actualizar('la.php')" data-toggle="tooltip" data-placement="right" data-original-title="Total de ventas agrupado por países en distribuidores"><i class=" fa fa-industry fa-fw"></i> Total por paises resellers</a></li>
                  <li><a  href="#" onclick="actualizar('ci_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="Monedas más usadas por los clientes en las ventas"><i class=" fa fa-industry fa-fw"></i> Monedas + usadas</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="page-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">Apartado ventas,</h1>
              <br>Aqui encontrara los informes y consultas relacionadas con las ventas
            </div>
          </div>
    </div>
  </div>
  <script src="/static/js/bootstrap.min.js"></script>
  <script src="/static/js/metisMenu.min.js"></script>
  <script src="/static/js/sb-admin-2.js"></script>
  <script src="/static/js/jquery.dataTables.min.js"></script>
  <script src="/static/js/dataTables.bootstrap.min.js"></script>
  <script src="/static/js/chosen.jquery.js" type="text/javascript"></script>
  <script>
    $('#side-menu').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })
      function actualizar(direccion){
          $(".modal-backdrop").remove();
          $("body").removeClass("modal-open");
          $("body").removeAttr("style");
          $("#ajax_loader").show();
          $.get(direccion).success(function(data){
              $("#ajax_loader").hide();
              $('#page-wrapper').html(data);
              $('html,body').scrollTop(0);
          });
      }
      var config = {
          '.chosen-select'           : {inherit_select_classes:true, width: "95%"},
          '.chosen-select-deselect'  : {allow_single_deselect:true},
          '.chosen-select-no-single' : {disable_search_threshold:10},
          '.chosen-select-no-results': {no_results_text:'No se encontraron resultados'},
      }
  </script>
</body>
</html>