<?php

require_once('../../../config.php');

use block_mcdpde\forms\categoryForm;
use block_mcdpde\models\categoriesModel;
use block_mcdpde\helpers\navigationMenus;
use block_mcdpde\models\areaModel;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$areaCode = required_param('areaid',PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$params = array('id' => $id);
$viewURL= new moodle_url('/blocks/mcdpde/abilities/newcategory.php', $params);
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('newcategories', 'block_mcdpde'));
$PAGE->set_heading(get_string('newcategoriestitle', 'block_mcdpde'));

navigationMenus::createAdminMenus(navigationMenus::CP_CATEGORIES);
navigationMenus::createPluginMenus();
$PAGE->navbar->add(get_string('newcategories', 'block_mcdpde'), $viewURL);

$categoriesListURL= new moodle_url('/blocks/mcdpde/abilities/view.php', array('area' => $areaCode));


$model = new categoriesModel();
$form = new categoryForm($viewURL, array('areaid' => $areaCode));

if ($id != 0) {
  $form->set_data($model->getRecord($id));
}

if ($form->is_cancelled()) {
  redirect($categoriesListURL);
} else if ($formdata = $form->get_data()) {
  $model->saveRecord($formdata);
  redirect($categoriesListURL);
}

$areas = new areaModel();
$area = $areas->getRecord($areaCode);
$areaName = "";
if (is_object($area)) {
  $areaName = $area->areaname;
}

echo $OUTPUT->header(get_string('newcategories', 'block_mcdpde'));
echo $OUTPUT->heading(get_string('newcategoriestitle', 'block_mcdpde').': '.$areaName);

$form->display();

echo $OUTPUT->footer();
?>
