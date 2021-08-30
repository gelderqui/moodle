<?php

require_once('../../../config.php');

use block_mcdpde\forms\abilitiesForm;
use block_mcdpde\models\abilitiesModel;
use block_mcdpde\models\areaModel;
use block_mcdpde\helpers\navigationMenus;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$id = optional_param('id', 0, PARAM_INT);
$areaCode = required_param('areaid',PARAM_INT);
$params = array('id' => $id);
$viewURL= new moodle_url('/blocks/mcdpde/abilities/newability.php', $params);
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('newability', 'block_mcdpde'));
$PAGE->set_heading(get_string('newabilitiestitle', 'block_mcdpde'));

navigationMenus::createAdminMenus(navigationMenus::CP_ABILITIES);
navigationMenus::createPluginMenus();
$PAGE->navbar->add(get_string('newability', 'block_mcdpde'), $viewURL);

$abilitiesListURL= new moodle_url('/blocks/mcdpde/abilities/abilitiesview.php', array('area' => $areaCode));


$model = new abilitiesModel();
$form = new abilitiesForm($viewURL, array('areaid' => $areaCode));

if ($id != 0) {
  $form->set_data($model->getRecord($id));
}

if ($form->is_cancelled()) {
  redirect($abilitiesListURL);
} else if ($formdata = $form->get_data()) {
  $model->saveRecord($formdata);
  redirect($abilitiesListURL);
}

echo $OUTPUT->header(get_string('newability', 'block_mcdpde'));
$areas = new areaModel();
$area = $areas->getRecord($areaCode);
$areaName = "";
if (is_object($area)) {
  $areaName = $area->areaname;
}
echo $OUTPUT->heading(get_string('newabilitiestitle', 'block_mcdpde').': '.$areaName);

$form->display();

echo $OUTPUT->footer();
?>
