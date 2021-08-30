<?php

require_once('../../../config.php');

use block_mcdpde\forms\categoryForm;
use block_mcdpde\models\abilitiesModel;
use block_mcdpde\models\areaModel;
use block_mcdpde\tables\abilitiesTable;
use block_mcdpde\helpers\navigationMenus;
use block_mcdpde\helpers\areaLinks;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$viewURL= new moodle_url('/blocks/mcdpde/abilities/abilitiesview.php');
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$areaCode = optional_param('area',1,PARAM_INT);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('abilities', 'block_mcdpde'));
$PAGE->set_heading(get_string('abilities', 'block_mcdpde'));

navigationMenus::createAdminMenus(navigationMenus::CP_ABILITIES);
navigationMenus::createPluginMenus();

$areas = new areaModel();
$area = $areas->getRecord($areaCode);
$areaName = "";
if (is_object($area)) {
  $areaName = $area->areaname;
}

echo $OUTPUT->header(get_string('abilities', 'block_mcdpde'));
echo areaLinks::createLinks('/blocks/mcdpde/abilities/abilitiesview.php', $areaCode);
echo $OUTPUT->heading(get_string('abilitiestitle', 'block_mcdpde').': '.$areaName);

$model = new abilitiesModel();
$model->configureAllAbilities($areaCode);

$table = new abilitiesTable('abilitytable', $areaCode);
$table->define_baseurl($viewURL);
$table->setModel($model);

$table->out(50,true);

echo '['.\html_writer::link(new \moodle_url('/blocks/mcdpde/abilities/newability.php',
                            array('areaid' => $areaCode)),
                                  get_string('add','block_mcdpde')).']';

echo $OUTPUT->footer();
?>
