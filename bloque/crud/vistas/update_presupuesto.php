<?php require_once('../../../config.php');
require_once('../forms/uppresupuesto.php');
global $DB, $OUTPUT, $PAGE;

$PAGE->set_url('/blocks/inicial/vistas/update_presupuesto.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_inicial'));
$PAGE->set_context(\context_system::instance());
$settingsnode = $PAGE->settingsnav->add(get_string('inicialsetting', 'block_inicial'));
$editurl = new moodle_url('/blocks/inicial/vistas/update_presupuesto.php');
$editnode = $settingsnode->add(get_string('editpage', 'block_inicial'), $editurl);
$editnode->make_active();

echo $OUTPUT->header();
echo "<h2>Área de actualización</h2>";
$response_id = optional_param('response_id', null, PARAM_INT);
$response_year = optional_param('response_year', null, PARAM_ALPHANUMEXT);
$response_month = optional_param('response_month', null, PARAM_ALPHANUMEXT);
$response_nombre = optional_param('response_nombre', null, PARAM_ALPHANUMEXT);
$response_valor = optional_param('response_valor', null, PARAM_NUMBER);
$response_agrupacion = optional_param('response_agrupacion', null, PARAM_INT);
$response_segmento = optional_param('response_segmento', null, PARAM_INT);


$actualizateform = new uppresupuesto();
$segmento = $actualizateform->display($response_id,$response_year,$response_month,$response_nombre,$response_valor,$response_agrupacion,$response_segmento);
echo '<a href="http://cursos.mayahonh.com/blocks/inicial/vistas/index_presupuesto.php" class="btn btn-danger center-block">Cancelar</a>';
echo $OUTPUT->footer();

?>
