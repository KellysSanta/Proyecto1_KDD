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
            <a class="navbar-brand" href="../menuReportes.html">KDD</a>
        </div>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                  <li><a  href="#" onclick="actualizar('ca_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="Promociones mas tomadas por los clientes"><i class=" fa fa-industry fa-fw"></i> Mas tomadas</a></li>
                  <li><a  href="#" onclick="actualizar('consultas/c1.php')" data-toggle="tooltip" data-placement="right" data-original-title="Comparación de ventas por promoción y producto"><i class=" fa fa-industry fa-fw"></i> Comparacion</a></li>
                  <li><a  href="#" onclick="actualizar('cd_parametros.php')" data-toggle="tooltip" data-placement="right" data-original-title="¿Cuál es la promoción de ventas por volumen que más prefieren los clientes?"><i class=" fa fa-industry fa-fw"></i> Preferencia por volumen</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="page-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">Apartado promociones,</h1>
              <br>Aqui encontrara los informes y consultas relacionadas con las promociones
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
      function error(mensaje){
          $('#mensajes').html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Error: </strong>"+mensaje+"</div>");
      }
      function realizado(mensaje){
          $('#mensajes').html("<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+mensaje+"</div>");
      }
      function formulario(form){
          var formulario=form.serialize(), url = form.attr( "action" );
          var formulario=form.serialize(), url = form.attr( "action" );
          var re;
          $.ajax({
              async:false, cache:false, dataType:"html", type: 'POST', url: url, data: formulario, 
              success:  function(respuesta){  
                re= respuesta;
              },
              beforeSend:function(){},
              error:function(objXMLHttpRequest){}
          });
          return re;
      }
      function accion(url){
          var re;
          $.ajax({
              async:false, cache:false, dataType:"html", type: 'GET', url: url, data: '', 
              success:  function(respuesta){  
                re= respuesta;
              },
              beforeSend:function(){},
              error:function(objXMLHttpRequest){}
          });
          return re;
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