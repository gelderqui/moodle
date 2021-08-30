<?php

require_once('../../../config.php');
require_once("{$CFG->libdir}/tablelib.php");

use block_mcdpde\models\seccionesModel;
use block_mcdpde\tables\seccionesTable;
use block_mcdpde\renders\seccionesRender;

global $DB, $OUTPUT, $USER, $PAGE;
require_login();

$context=context_system::instance();
$download = optional_param('download', '', PARAM_ALPHA);
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/secciones.php');
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/secciones.php');

$userid = optional_param('userid', null, PARAM_INT);
$name = optional_param('name', '', PARAM_ALPHA);
$areaCode = optional_param('area',1,PARAM_INT);
$redir_params = array('area'=>$areaCode);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('report_secciones', 'block_mcdpde'));
$PAGE->set_heading(get_string('report_secciones', 'block_mcdpde'));
echo $OUTPUT->header(get_string('report_secciones', 'block_mcdpde'));

$model=new seccionesModel();
//Se envia el codigo de usuario
//Poner aqui el codigo del usuario logueado
if(is_null($userid))$userid=$USER->id;

$model->configureTest($userid);
//$model->examenesIndividual($userid);
/*
$data1 = $model->query1();

echo "<pre>";
var_dump($data1);
echo "<hr>";
var_dump($model);
echo "</pre>";*/




$t = new seccionesTable('mcdpde_secciones',$download);
$t->define_baseurl($viewURL);
$t->setModel($model);

$render = new seccionesRender($t, 'my titulo de secciones');
$render->display($userid);



echo $OUTPUT->footer();
