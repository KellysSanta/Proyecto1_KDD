<?php include '../conexion.php'; ?>
<script src="../static/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../static/amcharts/serial.js" type="text/javascript"></script>
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Monedas más usadas por los clientes en las ventas</h1>
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
            </select> - 
            <select data-placeholder="Mes" id="month_desde" style="width:70px;" tabindex="1">
              <?php 
                for($i=1;$i<=12;$i++)
                  echo "<option value=".($i < 10 ? "0" : "").$i.">".($i < 10 ? "0" : "").$i."</option>";
              ?>
            </select> -
            <select data-placeholder="Dia" id="day_desde" style="width:70px;" tabindex="1">
              <?php 
                for($i=1;$i<=31;$i++)
                  echo "<option value=".($i < 10 ? "0" : "").$i.">".($i < 10 ? "0" : "").$i."</option>";
              ?>
            </select> hasta 
            <select data-placeholder="Año" id="year_hasta" style="width:70px;" tabindex="1">
              <?php 
                for($i=2005;$i<=2015;$i++)
                  echo "<option value=".$i.">".$i."</option>";
              ?>
              <option value="2016" selected>2016</option>
            </select> -
            <select data-placeholder="Mes" id="month_hasta" style="width:70px;" tabindex="1">
              <?php 
                for($i=1;$i<=11;$i++)
                  echo "<option value=".($i < 10 ? "0" : "").$i.">".($i < 10 ? "0" : "").$i."</option>";
              ?>
              <option value="12" selected>12</option>
            </select> -
            <select data-placeholder="Dia" id="day_hasta" style="width:70px;" tabindex="1">
              <?php 
                for($i=1;$i<=30;$i++)
                  echo "<option value=".($i < 10 ? "0" : "").$i.">".($i < 10 ? "0" : "").$i."</option>";
              ?>
              <option value="31" selected>31</option>
            </select>
          </div>
          <div class="form-group">
            <label>Filtrar por Moneda</label>
            <select id="monedas" data-placeholder="Escoga las monedas a mostrar" class="chosen-select" multiple style="width:350px;" tabindex="4">
              <?php
                $query = 'SELECT currencykey, currencyname FROM dimcurrency';
                $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
                $restulado = "";
                while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                    echo '<option value="'.$line['currencykey'].'" selected>'.$line['currencyname'].'</option>';
                }
                pg_free_result($result);
              ?>
            </select>
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
      var fecha_desde = parseInt($('#year_desde').val()+""+$('#month_desde').val()+""+$('#day_desde').val());
      var fecha_hasta = parseInt($('#year_hasta').val()+""+$('#month_hasta').val()+""+$('#day_hasta').val());
      if(fecha_hasta>=fecha_desde){
        $.get('ci.php', {desde:fecha_desde,hasta:fecha_hasta,monedas:$("#monedas").val()}, function(data){
          $('#resultados').html(data);
        });
      }else alert("Rango de fechas erroneo.");
    });

    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>