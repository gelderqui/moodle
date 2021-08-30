<?php

require_once('../../../config.php');

use block_mcdpde\forms\categoryForm;
use block_mcdpde\models\categoriesModel;
use block_mcdpde\models\areaModel;
use block_mcdpde\tables\categoriesTable;
use block_mcdpde\helpers\navigationMenus;
use block_mcdpde\helpers\areaLinks;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$viewURL= new moodle_url('/blocks/mcdpde/abilities/view.php');
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$areaCode = optional_param('area',1,PARAM_INT);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('categories', 'block_mcdpde'));
$PAGE->set_heading(get_string('categories', 'block_mcdpde'));

navigationMenus::createAdminMenus(navigationMenus::CP_CATEGORIES);
navigationMenus::createPluginMenus();

echo $OUTPUT->header(get_string('categories', 'block_mcdpde'));
$areas = new areaModel();
$area = $areas->getRecord($areaCode);
$areaName = "";
if (is_object($area)) {
  $areaName = $area->areaname;
}

echo areaLinks::createLinks('/blocks/mcdpde/abilities/view.php', $areaCode);
echo $OUTPUT->heading(get_string('categoriestitle', 'block_mcdpde').': '.$areaName);

$abilities = new categoriesModel();
$abilities->configureAllCategories($areaCode);

$table = new categoriesTable('categorytable', $areaCode);
$table->define_baseurl($viewURL);
$table->setModel($abilities);
$table->out(10, true);

echo '['.\html_writer::link(new \moodle_url('/blocks/mcdpde/abilities/newcategory.php', array('areaid'=>$areaCode)),
                                  get_string('add','block_mcdpde')).']';

// $form = new categoryForm();
// $form->display();

echo $OUTPUT->footer();
?>
