<?php

require_once('../../../config.php');

use block_mcdpde\forms\categoryForm;
use block_mcdpde\models\categoriesModel;

global $DB, $OUTPUT, $PAGE;

$context=context_system::instance();
$PAGE->set_context($context);
$viewURL= new moodle_url('/blocks/mcdpde/abilities/deletecategory.php');
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('newcategories', 'block_mcdpde'));
$PAGE->set_heading(get_string('newcategoriestitle', 'block_mcdpde'));

$categoriesListURL= new moodle_url('/blocks/mcdpde/abilities/view.php');
$id = optional_param('id', 0, PARAM_INT);

$model = new categoriesModel();

if ($id != 0) {
  $model->deleteRecord($id);
}
redirect($categoriesListURL);

?>
