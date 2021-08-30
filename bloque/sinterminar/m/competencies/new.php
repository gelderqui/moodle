<?php

require_once('../../../config.php');

use block_mcdpde\forms\competencyForm;
use block_mcdpde\models\competenciesModel;
use block_mcdpde\helpers\navigationMenus;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);

$idability = required_param('idability',PARAM_INT);
$id = optional_param('id',0,PARAM_INT);

$params = array('idability'=> $idability);
$viewURL = new moodle_url('/blocks/mcdpde/competencies/new.php', $params);
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('new_competencies', 'block_mcdpde'));
$PAGE->set_heading(get_string('new_competencies', 'block_mcdpde'));

navigationMenus::createAdminMenus(navigationMenus::CP_ABILITIES);
navigationMenus::createPluginMenus();

$model = new competenciesModel();
$form = new competencyForm($viewURL);

$abilitiesListURL= new moodle_url('/blocks/mcdpde/competencies/view.php', array('id' => $idability));

if (!$ability = $DB->get_record('mcdpde_abilities', array('id' => $idability))) {
    print_error('invalidability', 'block_mcdpde', $idability);
}

if ($id != 0) {
  $abilityCompetency = $model->getRecord($id);
  $competency = $model->getCompetencyRecord($abilityCompetency->competencyid);
  $form = new competencyForm($viewURL, array('competency' => $competency));
  $form->set_data($model->getRecord($id));
}

if ($form->is_cancelled()) {
  redirect($abilitiesListURL);
} else if ($formdata = $form->get_data()) {
  $formdata->abilityid = $idability;
  $model->saveRecord($formdata);
  redirect($abilitiesListURL);
}

echo $OUTPUT->header(get_string('newability', 'block_mcdpde'));
echo $OUTPUT->heading(get_string('newabilitiestitleto', 'block_mcdpde').' '.$ability->ability);

$form->display();

echo $OUTPUT->footer();
?>
