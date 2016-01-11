
var $form = $('.pregunta1'),
	$button = $('#mostrarPregunta1');


function mostrarFormulario1()
{
	$form.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}


//Eventos
$button.click( mostrarFormulario1 );
