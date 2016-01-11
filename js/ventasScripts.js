
var $form = $('.pregunta1'),
	$button = $('#mostrarPregunta1'),
	$form2 = $('.pregunta2'),
	$button2 = $('#mostrarPregunta2'),
	$form3 = $('.pregunta3'),
	$button3 = $('#mostrarPregunta3'),
	$form4 = $('.pregunta4'),
	$button4 = $('#mostrarPregunta4'),
	$form5 = $('.pregunta5'),
	$button5 = $('#mostrarPregunta5'),
	$form6 = $('.pregunta6'),
	$button6 = $('#mostrarPregunta6'),
	$form7 = $('.pregunta7'),
	$button7 = $('#mostrarPregunta7'),
	$form8 = $('.pregunta8'),
	$button8 = $('#mostrarPregunta8');





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

function mostrarFormulario4()
{
	$form4.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

function mostrarFormulario5()
{
	$form5.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

function mostrarFormulario6()
{
	$form6.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

function mostrarFormulario7()
{
	$form7.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

function mostrarFormulario8()
{
	$form8.slideToggle();/*Si esta oculto lo muestra, si esta visible lo oculta*/
	return false; /*Quita el efecto que tiene por defecto la etiqueta a, ya que esta va a una url*/
}

//Eventos
$button.click( mostrarFormulario1 );
$button2.click( mostrarFormulario2 );
$button3.click( mostrarFormulario3 );
$button4.click( mostrarFormulario4 );
$button5.click( mostrarFormulario5 );
$button6.click( mostrarFormulario6 );
$button7.click( mostrarFormulario7 );
$button8.click( mostrarFormulario8 );