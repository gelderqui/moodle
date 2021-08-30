<?php require_once('../../../config.php');
require_once('../modelos/agrupacion.php');

global $DB, $OUTPUT, $PAGE;

$PAGE->set_url('/blocks/cmi/vistas/index_agrupacion.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_cmi'));
$PAGE->set_context(\context_system::instance());
$settingsnode = $PAGE->settingsnav->add(get_string('cmisetting', 'block_cmi'));
$editurl = new moodle_url('/blocks/cmi/vistas/index_agrupacion.php');
$editnode = $settingsnode->add(get_string('editpage', 'block_cmi'), $editurl);
$editnode->make_active();

echo $OUTPUT->header();

echo "<h2>Área de agrupación</h2>";
echo '<a style="margin-left: 415px" href="http://cursos.mayahonh.com/blocks/cmi/vistas/index.php" class="btn btn-primary">Reiniciar filtro</a>';


$actualizateform = new queryAgrupacion();
$agrupacion = $actualizateform->listar();
$templatecontext = (object)[
    'agrupaciones' => $agrupacion,
];
var_dump($agrupacion);


echo $OUTPUT->render_from_template('block_cmi/agrupacion',$templatecontext);
echo $OUTPUT->footer();
?>
