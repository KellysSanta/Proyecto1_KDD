var pensum,cursos={},agrupaciones={},idiomas_vistos={},nivelacion_vistos={},cursos_vistos={},cursos_repetidos={},electivas_vistas={};
var creditos_electivos_vistos=0,cursos_aprobados=[],creditos_fundamentacion_obligatorios =0,creditos_discicplinar_obligatorios =0;
var creditos_fundamentacion_opcionales=0,creditos_discicplinar_opcionales=0;
function cargar_Pensum () {
	$.get("/app/estudiante/pensum/get/?tipo=pensum_carrera", {}, function(data){
  		pensum = jQuery.parseJSON(data);
  		for(a = 0;a<pensum.componente_fundamentacion.length;a++){
  			b = pensum.componente_fundamentacion[a];
  			cursos[b.codigo] = b;
  		}
  		for(a = 0;a<pensum.componente_disciplinar.length;a++){
  			b = pensum.componente_disciplinar[a];
  			cursos[b.codigo] = b;
  		}
  		for(a = 0;a<pensum.idiomas.length;a++){
  			b = pensum.idiomas[a];
  			cursos[b.codigo] = b;
  		}
  		for(a = 0;a<pensum.nivelacion.length;a++){
  			b = pensum.nivelacion[a];
  			cursos[b.codigo] = b;
  		}
  		for(a = 0;a<pensum.componente_fundamentacion_exigidos.length;a++){
  			b = pensum.componente_fundamentacion_exigidos[a];
  			agrupaciones[b.agrupacion_id] = b;
  		}
		agrupaciones[14] = new Object();
		agrupaciones[14].agrupacion= "Nivelacion";
  		agrupaciones[14].agrupacion_id= 14;
  		for(a = 0;a<pensum.componente_disciplinar_exigidos.length;a++){
  			b = pensum.componente_disciplinar_exigidos[a];
  			agrupaciones[b.agrupacion_id] = b;
  		}
  		$.get("/app/estudiante/pensum/get/?tipo=pensum_usuario_idiomas", {}, function(data){
  			c = jQuery.parseJSON(data);
	  		for(a = 0;a<c.idiomas.length;a++){
	  			b = c.idiomas[a];
	  			idiomas_vistos[b] = true;
	  		}
	  		$.get("/app/estudiante/pensum/get/?tipo=pensum_usuario_nivelacion", {}, function(data){
	  			c = jQuery.parseJSON(data);
		  		for(a = 0;a<c.cursos.length;a++){
		  			b = c.cursos[a];
		  			nivelacion_vistos[b.codigo] = b;
		  		}
		  		$.get("/app/estudiante/pensum/get/?tipo=pensum_usuario_cursos", {}, function(data){
		  			cursos_vistos = jQuery.parseJSON(data);
		  			$.get("/app/estudiante/pensum/get/?tipo=pensum_usuario_electivas", {}, function(data){
			  			c = jQuery.parseJSON(data);
				  		for(a = 0;a<c.electivas.length;a++){
				  			b = c.electivas[a];
				  			electivas_vistas[b.codigo] = b;
				  		}
			  			recargar();
				  	});
		  		});
		  	});
	  	});
    });
}	
function calcular(){
	creditos_fundamentacion_obligatorios =0;
	creditos_discicplinar_obligatorios =0;
	creditos_idiomas = 0;creditos_nivelacion=0;creditos_electivas=0;
	creditos_fundamentacion_opcionales=0;
	papa_sumatoria =0;
	papa_creditos=0;
	creditos_discicplinar_opcionales=0;
	p_semestre=[]
	p_cuantos=[]
	for(a=0;a<20;a++)
	{
		$("#dibujo_semestre_"+a).html("");
		p_semestre[a]=0;
		p_cuantos[a]=0;
	}
	//Calculos con idiomas
	for(var propiedad in idiomas_vistos){
		if(idiomas_vistos[propiedad])
			creditos_idiomas+=cursos[propiedad].creditos;
	}
	$("#barra_idioma").attr("style","width:"+(creditos_idiomas/pensum.idiomas_obligatorio*25)+"%");
	$("#barra_idioma").html("Idiomas ("+creditos_idiomas+"/"+pensum.idiomas_obligatorio+")");
	//Calculos con nivelaciones
	for(var propiedad in nivelacion_vistos){
		if(nivelacion_vistos[propiedad] && !nivelacion_vistos[propiedad].habilitada)
		{
			p_semestre[nivelacion_vistos[propiedad].semestre] +=nivelacion_vistos[propiedad].calificacion;
			p_cuantos[nivelacion_vistos[propiedad].semestre]++;
			creditos_nivelacion+=cursos[propiedad].creditos;
			papa_sumatoria += cursos[propiedad].creditos*nivelacion_vistos[propiedad].calificacion;
			papa_creditos += cursos[propiedad].creditos;
		}
	}
	//Calculos con Electivas
	for(var propiedad in electivas_vistas){
		if(electivas_vistas[propiedad])
		{
			p_semestre[electivas_vistas[propiedad].semestre] +=electivas_vistas[propiedad].calificacion;
			p_cuantos[electivas_vistas[propiedad].semestre]++;
			creditos_electivas+=electivas_vistas[propiedad].creditos;
			papa_sumatoria += electivas_vistas[propiedad].creditos*electivas_vistas[propiedad].calificacion;
			papa_creditos += electivas_vistas[propiedad].creditos;
		}
	}
	$("#barra_electivas").attr("style","width:"+(creditos_electivas/pensum.libre_eleccion*25)+"%");
	$("#barra_electivas").html("Electivas ("+creditos_electivas+"/"+pensum.libre_eleccion+")");
	
	//Calculos con Fundamentacion
	for(a =0;a< pensum["componente_fundamentacion"].length;a++){
		b = pensum["componente_fundamentacion"][a];
		if(cursos_vistos[b.codigo]!=null){
			for(c = 0;c<cursos_vistos[b.codigo].length;c++){
				d=cursos_vistos[b.codigo][c];
				for(var propiedad in d){
					//console.log(d[propiedad])
					p_semestre[propiedad] +=d[propiedad];
					p_cuantos[propiedad]++;
					papa_sumatoria += d[propiedad]*cursos[b.codigo].creditos;
					papa_creditos += cursos[b.codigo].creditos;
					if(b.obligatoria && d[propiedad]>=3 ){
						creditos_fundamentacion_obligatorios +=cursos[b.codigo].creditos;
					}else if(!b.obligatoria && d[propiedad]>=3){
						creditos_fundamentacion_opcionales +=cursos[b.codigo].creditos;
					}
				}
			}
		}	
	}
	$("#barra_fundamentacion").attr("style","width:"+((creditos_fundamentacion_obligatorios+creditos_fundamentacion_opcionales)/pensum.fundamentacion*25)+"%");
	$("#barra_fundamentacion").html("Fundamentacion ("+(creditos_fundamentacion_obligatorios+creditos_fundamentacion_opcionales)+"/"+pensum.fundamentacion+")");
	
	//console.log(creditos_discicplinar_obligatorios);
	//Disciplinar
	for(a =0;a< pensum["componente_disciplinar"].length;a++){
		b = pensum["componente_disciplinar"][a];
		if(cursos_vistos[b.codigo]!=null){
			for(c = 0;c<cursos_vistos[b.codigo].length;c++){
				d=cursos_vistos[b.codigo][c];
				for(var propiedad in d){
					//console.log(d[propiedad])
					p_semestre[propiedad] +=d[propiedad];
					p_cuantos[propiedad]++;
					papa_sumatoria += d[propiedad]*cursos[b.codigo].creditos;
					papa_creditos += cursos[b.codigo].creditos;
					if(b.obligatoria &&d[propiedad]>=3 ){
						creditos_discicplinar_obligatorios +=cursos[b.codigo].creditos;
					}else if(!b.obligatoria && d[propiedad]>=3){
						creditos_discicplinar_opcionales +=cursos[b.codigo].creditos;
					}
				}
			}
		}	
		/*
		if(cursos_vistos[curso.codigo]!=null)
		{
			creditos_electivas+=electivas_vistas[propiedad].creditos;
			papa_sumatoria += electivas_vistas[propiedad].creditos*electivas_vistas[propiedad].calificacion;
			papa_creditos += electivas_vistas[propiedad].creditos;
		}*/
	}
	$("#barra_disciplinar").attr("style","width:"+((creditos_discicplinar_obligatorios+creditos_discicplinar_opcionales)/pensum.disciplinar*25)+"%");
	$("#barra_disciplinar").html("Disciplinar ("+(creditos_discicplinar_obligatorios+creditos_discicplinar_opcionales)+"/"+pensum.disciplinar+")");

	
	$("#barra_progreso").attr("style","width:"+((creditos_discicplinar_obligatorios+creditos_discicplinar_opcionales+creditos_fundamentacion_opcionales+creditos_fundamentacion_obligatorios+creditos_electivas+creditos_idiomas)/(pensum.fundamentacion+pensum.disciplinar+pensum.idiomas_obligatorio+pensum.libre_eleccion)*100)+"%");
	$("#barra_progreso").html("Progreso "+((creditos_discicplinar_obligatorios+creditos_discicplinar_opcionales+creditos_fundamentacion_opcionales+creditos_fundamentacion_obligatorios+creditos_electivas+creditos_idiomas)/(pensum.fundamentacion+pensum.disciplinar+pensum.idiomas_obligatorio+pensum.libre_eleccion)*100).toFixed(2)+"%");
	papa= papa_sumatoria/papa_creditos;
	$("#papa").html(papa.toFixed(2));
	for(a=1;a<20;a++)
	{
		$("#semestre_"+a+"_promedio").html((p_semestre[a]/p_cuantos[a]).toFixed(2));
		//console.log(p_semestre[a]);
	}
	//console.log(papa_creditos);
	/*
	(pensum["fundamentacion"]+pensum["disciplinar"]+pensum["idiomas_obligatorio"]+pensum["libre_eleccion"])
	for (index = 0; index < pensum_usuario.cursos.length; ++index) { //Imprimir Pensum Normal
  		var curso = pensum_usuario.cursos[index];
  		var curso_pensum = this.getCreditos(curso.codigo);
  		console.log(curso_pensum);
  		suma = parseFloat(curso.calificacion) * parseFloat(curso_pensum.creditos);
  		total_notas_semestres[curso.semestre] += suma;
  		total_nota_papa += suma;
  		total_creditos_semestres[curso.semestre] += curso.creditos;
  		total_creditos_papa +=curso.creditos;
    }
    console.log(total_nota_papa+" "+total_creditos_papa)
    $("#papa").html(total_nota_papa/total_creditos_papa);*/
}
function dibujarPensum(){
    for (a = 0; a < pensum.idiomas.length; a++) { //Dibujar idiomas
    	dibujarIdioma(pensum.idiomas[a].codigo);
    }
    for (b = 0; b < pensum.nivelacion.length; b++) { //Dibujar nivelaciones
    	dibujarNivelacion(pensum.nivelacion[b].codigo);
    }
	for (index = 0; index < pensum.semestres.length; ++index) { //Dibujar cursos obligatorios
    	semestre = pensum.semestres[index];
      	for (j = 0; j < semestre.cursos.length; ++j) {
        	curso = semestre.cursos[j];
			dibujarCurso(curso,index+1);
      	}
  	}
  	for (a = 0; a < pensum["componente_fundamentacion"].length; a++) { //Dibujar cursos opcionales
    	b = pensum["componente_fundamentacion"][a];
      	if(!b.obligatoria){
      		if(cursos_vistos[b.codigo]!=null){
				dibujarOptativa(b.codigo,a+1);	
      		}
      	}
  	}
    c_e_v=0;
    for (elec in electivas_vistas) {
    	if(electivas_vistas[elec]!=null){
    		c_e_v += electivas_vistas[elec].creditos;
    		dibujarElectiva(true,"",electivas_vistas[elec]);
    	}
    }
  	for (index = 15; index >= 1; index--) { //Imprimir Pensum Normal
    	if($("#dibujo_semestre_"+index)[0]){
    		for (j = $("#dibujo_semestre_"+index)[0].childElementCount; j <6; j++) {
	      		if(c_e_v<pensum["libre_eleccion"])
	    		{
    				dibujarElectiva(false,index,"");
	    			c_e_v+=3;
	    		}
	      	}
    	}
  	}
}
function dibujarAgrupaciones(){
	$("#accordion_fundamentacion").html("");
    for (a = 0; a < pensum["componente_fundamentacion_exigidos"].length; a++) {
    	dibujarComponenteFundamentacion(pensum["componente_fundamentacion_exigidos"][a]);
    }
    $("#accordion_disciplinar").html("");
    for (a = 0; a < pensum["componente_disciplinar_exigidos"].length; a++) {
    	dibujarComponenteDisciplinar(pensum["componente_disciplinar_exigidos"][a]);
    }
}
function dibujarComponenteFundamentacion(componente){
	//console.log(componente);
	d = 0;
	for(b=0;b<pensum["componente_fundamentacion"].length;b++){
		c = pensum["componente_fundamentacion"][b];
    	if(cursos_aprobados[c.codigo]!=null  && c.agrupacion_id==componente.agrupacion_id)
    		d+= cursos[c.codigo].creditos;
	}
	if(d>=componente.creditos)
		res='<div class="panel panel-success"><div class="panel-heading"><h4 class="panel-title">'+componente.agrupacion+' ('+d+'/'+componente.creditos+')</h4></div></div>';
	else{
		res ='<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_fundamentacion" href="#agrupacion_'+componente.agrupacion_id+'" aria-expanded="false" class="collapsed">'+componente.agrupacion+' ('+d+'/'+componente.creditos+')</a></h4></div><div id="agrupacion_'+componente.agrupacion_id+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;"><div class="panel-body row">';
	    for(b=0;b<pensum["componente_fundamentacion"].length;b++){
	    	c = pensum["componente_fundamentacion"][b];
	    	if(c.agrupacion_id==componente.agrupacion_id && !c.obligatoria && cursos_aprobados[c.codigo]==null){
				req="[]";
				bloquear=false;
				if(cursos[c.codigo].requisitos.length>0){
					req="[";
					for(z=0;z<cursos[c.codigo].requisitos.length;z++){
						req += cursos[c.codigo].requisitos[z]+ ((z!=cursos[c.codigo].requisitos.length-1) ? "," : "");
						if(!cursos_vistos[cursos[c.codigo].requisitos[z]])
							bloquear=true;
					}
					req+="]";
				}	
				res += "<div class='col-lg-3'><div "+((bloquear) ? "" : "data-toggle='modal' data-target='#modal_addCurso'")+"'  id='"+c.codigo+"' class='cajon-curso panel "+((bloquear) ? "panel-default" : "panel-success")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+c.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>OPCIONAL</h6></div><div class='col-xs-2 text-right'><h6>"+c.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+c.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[c.agrupacion_id].agrupacion+"</div></div></div></div></div>";
	    	}
	    	//<div data-toggle="modal" data-target="#modal_addCurso" '="" id="3007845" class="cajon-curso panel panel-success" onmouseover="pintarRequisito([])" onmouseleave="despintarRequisito([])"><div class="cajon-curso-encabezado panel-heading"><div class="row"><div class="col-xs-4"><h6>3007845</h6></div><div class="col-xs-6 text-center"><h6>OBLIGATORIA</h6></div><div class="col-xs-2 text-right"><h6>3</h6></div></div></div><div class="panel-body cajon-curso-cuerpo text-center"><h5>Seminario de Proyectos en Ingeniería II</h5></div><div class="panel-footer text-center cajon-curso-pie"><div class="row"><div class="col-xs-12">Seminario de Proyectos en Ingeniería</div></div></div></div>
	    }
    	res+='</div></div></div>';
    }
    $("#accordion_fundamentacion").append(res);
	/*if(!vista){
		res = "<div data-toggle='modal' data-target='#modal_addElectiva' id='"+curso.codigo+"' class='cajon-curso panel panel-success'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12'><h6>Electiva</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>Electiva</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Electiva</div></div></div></div></div>";
		$("#dibujo_semestre_"+semestre).append(res);
	}else{
		res = "<div id="+electiva.codigo+" data-toggle='modal' data-target='#modal_removeElectiva' class='cajon-curso panel "+((electiva.calificacion< 3) ? "panel-red" :"panel-green")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>"+electiva.calificacion+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+electiva.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Electiva</div></div></div></div></div>";
		$("#dibujo_semestre_"+electiva.semestre).append(res);
	}*/
}
function dibujarComponenteDisciplinar(componente){
	//console.log(componente);
	d = 0;
	for(b=0;b<pensum["componente_disciplinar"].length;b++){
		c = pensum["componente_disciplinar"][b];
    	if(cursos_aprobados[c.codigo]!=null  && c.agrupacion_id==componente.agrupacion_id)
    		d+= cursos[c.codigo].creditos;
	}
	if(d>=componente.creditos)
		res='<div class="panel panel-success"><div class="panel-heading"><h4 class="panel-title">'+componente.agrupacion+' ('+d+'/'+componente.creditos+')</h4></div></div>';
	else{
		//console.log(c.codigo)
		res ='<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion_disciplinar" href="#agrupacion_'+componente.agrupacion_id+'" aria-expanded="false" class="collapsed">'+componente.agrupacion+' ('+d+'/'+componente.creditos+')</a></h4></div><div id="agrupacion_'+componente.agrupacion_id+'" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;"><div class="panel-body row">';
	    for(b=0;b<pensum["componente_disciplinar"].length;b++){
	    	c = pensum["componente_disciplinar"][b];
	    	if(c.agrupacion_id==componente.agrupacion_id && !c.obligatoria && cursos_aprobados[c.codigo]==null){
				req="[]";
				bloquear=false;
				if(cursos[c.codigo].requisitos.length>0){
					req="[";
					for(z=0;z<cursos[c.codigo].requisitos.length;z++){
						req += cursos[c.codigo].requisitos[z]+ ((z!=cursos[c.codigo].requisitos.length-1) ? "," : "");
						if(!cursos_vistos[cursos[c.codigo].requisitos[z]])
							bloquear=true;
					}
					req+="]";
				}	
				res += "<div class='col-lg-3'><div "+((bloquear) ? "" : "data-toggle='modal' data-target='#modal_addCurso'")+"'  id='"+c.codigo+"' class='cajon-curso panel "+((bloquear) ? "panel-default" : "panel-success")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+c.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>OPCIONAL</h6></div><div class='col-xs-2 text-right'><h6>"+c.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+c.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[c.agrupacion_id].agrupacion+"</div></div></div></div></div>";
	    	}
	    	//<div data-toggle="modal" data-target="#modal_addCurso" '="" id="3007845" class="cajon-curso panel panel-success" onmouseover="pintarRequisito([])" onmouseleave="despintarRequisito([])"><div class="cajon-curso-encabezado panel-heading"><div class="row"><div class="col-xs-4"><h6>3007845</h6></div><div class="col-xs-6 text-center"><h6>OBLIGATORIA</h6></div><div class="col-xs-2 text-right"><h6>3</h6></div></div></div><div class="panel-body cajon-curso-cuerpo text-center"><h5>Seminario de Proyectos en Ingeniería II</h5></div><div class="panel-footer text-center cajon-curso-pie"><div class="row"><div class="col-xs-12">Seminario de Proyectos en Ingeniería</div></div></div></div>
	    }
    	res+='</div></div></div>';
    }
    $("#accordion_disciplinar").append(res);
	/*if(!vista){
		res = "<div data-toggle='modal' data-target='#modal_addElectiva' id='"+curso.codigo+"' class='cajon-curso panel panel-success'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12'><h6>Electiva</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>Electiva</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Electiva</div></div></div></div></div>";
		$("#dibujo_semestre_"+semestre).append(res);
	}else{
		res = "<div id="+electiva.codigo+" data-toggle='modal' data-target='#modal_removeElectiva' class='cajon-curso panel "+((electiva.calificacion< 3) ? "panel-red" :"panel-green")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>"+electiva.calificacion+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+electiva.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Electiva</div></div></div></div></div>";
		$("#dibujo_semestre_"+electiva.semestre).append(res);
	}*/
}
function dibujarElectiva(vista,semestre,electiva){
	if(!vista){
		res = "<div data-toggle='modal' data-target='#modal_addElectiva' id='"+curso.codigo+"' class='cajon-curso panel panel-success'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12'><h6>Electiva</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>Electiva</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Electiva</div></div></div></div></div>";
		$("#dibujo_semestre_"+semestre).append(res);
	}else{
		res = "<div id="+electiva.codigo+" data-toggle='modal' data-target='#modal_removeElectiva' class='cajon-curso panel "+((electiva.calificacion< 3) ? "panel-red" :"panel-green")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>"+electiva.calificacion+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+electiva.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Electiva</div></div></div></div></div>";
		$("#dibujo_semestre_"+electiva.semestre).append(res);
	}
}
function dibujarNivelacion(codigo){
	//console.log(nivelacion_vistos);
	curso = cursos[codigo];
	if(nivelacion_vistos[codigo]!=null){
		//console.log(nivelacion_vistos[codigo]);
		if(nivelacion_vistos[codigo].habilitada){
			res = "<div data-toggle='modal' data-target='#modal_removeNivelacion' id='"+curso.codigo+"' class='cajon-curso panel panel-green'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>Aprobado</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Nivelacion</div></div></div></div></div>";
			$("#dibujo_semestre_0").append(res);
		}
		else{
			res = "<div data-toggle='modal' data-target='#modal_removeNivelacion' id='"+curso.codigo+"' class='cajon-curso panel "+((nivelacion_vistos[codigo].calificacion < 3) ? "panel-red" :"panel-green")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>"+nivelacion_vistos[codigo].calificacion+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Nivelacion</div></div></div></div></div>";
			$("#dibujo_semestre_"+nivelacion_vistos[codigo].semestre).append(res);
		}
	}else{
		cursos_vistos[codigo]=null;
		res = "<div data-toggle='modal' data-target='#modal_addNivelacion' id='"+curso.codigo+"' class='cajon-curso panel panel-success'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+curso.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>Nivelacion</h6></div><div class='col-xs-2 text-right'><h6>"+curso.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Nivelacion</div></div></div></div></div>";
		$("#dibujo_semestre_0").append(res);
	}
}
function dibujarOptativa(codigo,semestre){
	curso = cursos[codigo];
	curso_visto = cursos_vistos[codigo];
	req="[]";
	if(curso_visto){
		//console.log(agrupaciones[curso.agrupacion_id]);
		for(z=0;z<curso_visto.length;z++)
		{
			for(pr in curso_visto[z]){
				//console.log(curso_visto[z][pr]);
				if(curso_visto[z][pr]>=3)
					cursos_aprobados[curso.codigo] = curso.codigo;
				res = "<div id="+codigo+" data-toggle='modal' data-semestre="+pr+" data-target='#modal_removeCurso' class='cajon-curso panel "+((curso_visto[z][pr]< 3) ? "panel-red" :"panel-green")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>"+curso_visto[z][pr]+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[curso.agrupacion_id].agrupacion+"</div></div></div></div></div>";
				$("#dibujo_semestre_"+pr).append(res);
			}

		}
	}else{
		bloquear=false;
		if(cursos[codigo].requisitos.length>0){
			req="[";
			for(z=0;z<cursos[codigo].requisitos.length;z++){
				req += cursos[codigo].requisitos[z]+ ((z!=cursos[codigo].requisitos.length-1) ? "," : "");
				if(!cursos_vistos[cursos[codigo].requisitos[z]])
					bloquear=true;
			}
			req+="]";
		}	
		res = "<div "+((bloquear) ? "" : "data-toggle='modal' data-target='#modal_addCurso'")+"'  id='"+curso.codigo+"' class='cajon-curso panel "+((bloquear) ? "panel-default" : "panel-success")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+curso.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>OBLIGATORIA</h6></div><div class='col-xs-2 text-right'><h6>"+curso.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[curso.agrupacion_id].agrupacion+"</div></div></div></div></div>";
		$("#dibujo_semestre_"+semestre).append(res);
	}	
	$("#"+codigo).attr("onmouseover","pintarRequisito("+req+")");
	$("#"+codigo).attr("onmouseleave","despintarRequisito("+req+")");
}
function dibujarIdioma(codigo){
	//console.log(codigo);
	curso = cursos[codigo];
	req="[]";
	if(idiomas_vistos[codigo]){
			res = "<div data-toggle='modal' data-target='#modal_removeIdioma' id='"+curso.codigo+"' class='cajon-curso panel panel-green'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>Aprobado</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Idiomas</div></div></div></div></div>";
	}else{
		//console.log(curso.agrupacion_id);
		bloquear=false;
		if(cursos[codigo].requisitos.length>0){
			req="[";
			for(z=0;z<cursos[codigo].requisitos.length;z++){
				req += cursos[codigo].requisitos[z]+ ((z!=cursos[codigo].requisitos.length-1) ? "," : "");
				if(!idiomas_vistos[cursos[codigo].requisitos[z]])
					bloquear=true;
			}
			req+="]";
			
		}	
		//console.log(bloquear);
		res = "<div "+((bloquear) ? "" : "data-toggle='modal' data-target='#modal_addIdioma'")+" id='"+curso.codigo+"' class='cajon-curso panel "+((bloquear) ? "panel-default" : "panel-success")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+curso.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>Nivelacion</h6></div><div class='col-xs-2 text-right'><h6>"+curso.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>Idiomas</div></div></div></div></div>";
	}
	$("#dibujo_semestre_0").append(res);
	$("#"+codigo).attr("onmouseover","pintarRequisito("+req+")");
	$("#"+codigo).attr("onmouseleave","despintarRequisito("+req+")");
}
function dibujarCurso(codigo,semestre){
	curso = cursos[codigo];
	curso_visto = cursos_vistos[codigo];
	req="[]";
	if(curso_visto){
		//console.log(agrupaciones[curso.agrupacion_id]);
		calificacion_alta =-1;semestre_alto=1;
		for(z=0;z<curso_visto.length;z++)
		{
			//console.log(curso_visto[z]);
			for(pr in curso_visto[z]){
				//console.log(curso_visto[z][pr]);
				if(calificacion_alta<parseFloat(curso_visto[z][pr])) calificacion_alta = parseFloat(curso_visto[z][pr]);
				if(semestre_alto<pr) semestre_alto =pr;
				res = "<div id="+codigo+" data-toggle='modal' data-semestre="+pr+" data-target='#modal_removeCurso' class='cajon-curso panel "+((curso_visto[z][pr]< 3) ? "panel-red" :"panel-green")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-12 text-center'><h6>"+curso_visto[z][pr]+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[curso.agrupacion_id].agrupacion+"</div></div></div></div></div>";
				$("#dibujo_semestre_"+pr).append(res);
			}

		}
		if(calificacion_alta<3){
			res = "<div data-toggle='modal' data-target='#modal_addCurso' id='"+curso.codigo+"' class='cajon-curso panel panel-success'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+curso.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>OBLIGATORIA</h6></div><div class='col-xs-2 text-right'><h6>"+curso.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[curso.agrupacion_id].agrupacion+"</div></div></div></div></div>";
			if(calificacion_alta==-1)
				$("#dibujo_semestre_"+(semestre)).append(res);
			else
				$("#dibujo_semestre_"+(parseInt(semestre_alto)+1)).append(res);
		}else{
			cursos_aprobados[curso.codigo] = curso.codigo;
		}
	}else{
		bloquear=false;
		if(cursos[codigo].requisitos.length>0){
			req="[";
			for(z=0;z<cursos[codigo].requisitos.length;z++){
				req += cursos[codigo].requisitos[z]+ ((z!=cursos[codigo].requisitos.length-1) ? "," : "");
				if(!cursos_vistos[cursos[codigo].requisitos[z]])
					bloquear=true;
			}
			req+="]";
		}	
		res = "<div "+((bloquear) ? "" : "data-toggle='modal' data-target='#modal_addCurso'")+"'  id='"+curso.codigo+"' class='cajon-curso panel "+((bloquear) ? "panel-default" : "panel-success")+"'><div class='cajon-curso-encabezado panel-heading'><div class='row'><div class='col-xs-4'><h6>"+curso.codigo+"</h6></div><div class='col-xs-6 text-center'><h6>OBLIGATORIA</h6></div><div class='col-xs-2 text-right'><h6>"+curso.creditos+"</h6></div></div></div><div class='panel-body cajon-curso-cuerpo text-center'><h5>"+curso.nombre+"</h5></div><div class='panel-footer text-center cajon-curso-pie'><div class='row'><div class='col-xs-12'>"+agrupaciones[curso.agrupacion_id].agrupacion+"</div></div></div></div></div>";
		$("#dibujo_semestre_"+semestre).append(res);
	}	
	$("#"+codigo).attr("onmouseover","pintarRequisito("+req+")");
	$("#"+codigo).attr("onmouseleave","despintarRequisito("+req+")");
}
function pintarRequisito(codigos){
	for (i = 0; i < codigos.length; ++i)
		$("#"+codigos[i]).addClass("cajon-requisito");
}
function despintarRequisito(codigos){
	for (i = 0; i < codigos.length; ++i) 
		$("#"+codigos[i]).removeClass("cajon-requisito");
}