<?php require_once('../../../config.php');
require_once('../forms/presupuesto.php');
global $DB, $OUTPUT, $PAGE;

$PAGE->set_url('/blocks/inicial/vistas/create_presupuesto.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_inicial'));
$PAGE->set_context(\context_system::instance());
$settingsnode = $PAGE->settingsnav->add(get_string('inicialsetting', 'block_inicial'));
$editurl = new moodle_url('/blocks/inicial/vistas/create_presupuesto.php');
$editnode = $settingsnode->add(get_string('editpage', 'block_inicial'), $editurl);
$editnode->make_active();

echo $OUTPUT->header();
echo "<h2>Área de creación</h2>";
$actualizateform = new presupuesto();
$segmento = $actualizateform->display();
echo '<a href="http://cursos.mayahonh.com/blocks/inicial/vistas/index_presupuesto.php" class="btn btn-danger">Cancelar</a>';
echo $OUTPUT->footer();

?>
