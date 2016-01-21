<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"> Comparativo de ventas por temporada</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
      <div class="panel-heading">Parametros</div>
      <div class="panel-body">
          <div class="form-group">
            <label>Filtrar por dimension fecha</label>
            <br>Fecha desde 
            <select data-placeholder="Año" id="year_desde" style="width:70px;" tabindex="1">
              <?php 
                for($i=2005;$i<=2015;$i++)
                  echo "<option value=".$i.">".$i."</option>";
              ?>
            </select> hasta 
            <select data-placeholder="Año" id="year_hasta" style="width:70px;" tabindex="1">
              <?php 
                for($i=2005;$i<=2015;$i++)
                  echo "<option value=".$i.">".$i."</option>";
              ?>
              <option value="2016" selected>2016</option>
            </select> -
          </div>
          <a href="#" id="calcular" class="btn btn-primary">Calcular</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
      <div class="panel-heading">Resultados</div>
      <div id="resultados" class="panel-body">
        
      </div>
    </div>
  </div>
  <script>
    $('#calcular').click(function(){
      var fecha_desde = parseInt($('#year_desde').val());
      var fecha_hasta = parseInt($('#year_hasta').val());
      if(fecha_hasta>=fecha_desde){
        $.get('cc.php', {desde:fecha_desde,hasta:fecha_hasta}, function(data){
          $('#resultados').html(data);
        });
      }else alert("Rango de fechas erroneo.");
    });

    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>