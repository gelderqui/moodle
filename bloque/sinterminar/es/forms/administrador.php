<?php
require_once("{$CFG->libdir}/formslib.php");
require_once('../modelos/Querymodelos.php');
class administrador extends moodleform {

    function definition() {

$reporte1 = new Consultas();
//Consultas a la base de datos

$cursosinfo=$reporte1->cursos();
$areainfo=$reporte1->areas();

$arraycurso['seleccionacurso']='Selecciona tu curso';
$arrayarea['seleccionaarea']='Selecciona tu departamento';


foreach ($cursosinfo as $key => $value) {
  $arraycurso[$key] = $value->shortname;
}
foreach ($areainfo as $key => $value) {
  $arrayarea[$key] = $value->department;
}
        $mform =& $this->_form;
        $mform->addElement('header','displayinfo', get_string('textfields', 'block_estandarcl'));
     	  $mform->addElement('date_selector', 'fecha_inicio', get_string('from'));
      	$mform->addElement('date_selector', 'fecha_fin', get_string('to'));
        $mform->addElement('select', 'curso', get_string('curso', 'block_estandarcl'), $arraycurso);
        $mform->addElement('select', 'area', get_string('area', 'block_estandarcl'), $arrayarea);
        $mform-> addElement ('text', 'codigo', get_string ('codigo', 'block_estandarcl'));
        




}

}







?>
