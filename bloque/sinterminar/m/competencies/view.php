<?php

require_once('../../../config.php');

use block_mcdpde\models\competenciesModel;
use block_mcdpde\tables\competenciesTable;
use block_mcdpde\helpers\navigationMenus;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/competencies/view.php');
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$id = required_param('id', PARAM_INT);
$params = array('id' => $id);
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/competencies/view.php', $params);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('competencies', 'block_mcdpde'));

navigationMenus::createAdminMenus(navigationMenus::CP_ABILITIES);
navigationMenus::createPluginMenus();

if (!$ability = $DB->get_record('mcdpde_abilities', array('id' => $id))) {
    print_error('invalidability', 'block_mcdpde', $id);
}
$PAGE->set_heading($ability->ability);

$model = new competenciesModel();
$model->configureAllCompetencies($id);
$table = new competenciesTable('competenciestable');
$table->define_baseurl($viewURL);
$table->setCompetency($id);
$table->setModel($model);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('competenciesto', 'block_mcdpde').' '.$ability->ability);

$table->out(10, true);

if (competenciesModel::countCompetencies($id) <= 1)
{
  echo '['.\html_writer::link(new \moodle_url('/blocks/mcdpde/competencies/new.php',array('idability' => $id)),
                                  get_string('add','block_mcdpde')).']';
}

echo $OUTPUT->footer();
?>
