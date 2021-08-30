<?php

require_once('../../../config.php');

use block_mcdpde\models\abilitiesModel;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$viewURL= new moodle_url('/blocks/mcdpde/abilities/deleteability.php');
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('newcategories', 'block_mcdpde'));
$PAGE->set_heading(get_string('newcategoriestitle', 'block_mcdpde'));

$abilitiesListURL= new moodle_url('/blocks/mcdpde/abilities/abilitiesview.php');
$id = optional_param('id', 0, PARAM_INT);

$model = new abilitiesModel();

if ($id != 0) {
  $model->deleteRecord($id);
}
redirect($abilitiesListURL);

?>
