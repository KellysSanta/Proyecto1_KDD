
var $form = $('.pregunta1'),
	$button = $('#mostrarPregunta1'),
	$form2 = $('.pregunta2'),
	$button2 = $('#mostrarPregunta2');
	$form3 = $('.pregunta3'),
	$button3 = $('#mostrarPregunta3');




function mostrarFormulario1()
{
	$form.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

function mostrarFormulario2()
{
	$form2.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

function mostrarFormulario3()
{
	$form3.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

//Eventos
$button.click( mostrarFormulario1 );
$button2.click( mostrarFormulario2 );
$button3.click( mostrarFormulario3 );
