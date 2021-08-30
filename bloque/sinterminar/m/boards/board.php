<?php

require_once('../../../config.php');
require_once("{$CFG->libdir}/tablelib.php");

use block_mcdpde\models\QueryModel;
use block_mcdpde\renders\resumeRender;
use block_mcdpde\helpers\navigationMenus;
use block_mcdpde\helpers\areaLinks;

global $DB, $OUTPUT, $USER, $PAGE;

require_login();


$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/board.php');
$PAGE->requires->css('/blocks/mcdpde/board.css');
$PAGE->requires->js_call_amd('block_mcdpde/params', 'boardTable');

$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/board.php');

$userid = optional_param_array('userid', array(), PARAM_INT);
$country = optional_param_array('country', array(), PARAM_ALPHA);
$consultant = optional_param_array('consultant', array(), PARAM_INT);
$restaurant = optional_param_array('restid', array(), PARAM_TEXT);
$name = optional_param('name', '', PARAM_ALPHA);
$areaCode = optional_param('area',1,PARAM_INT);
$redir_params = array('area'=>$areaCode);
$string_redir = '';
if (!empty($restaurant)) {
  $redir_params['restid']='_qf__force_multiselect_submission';
  foreach ($restaurant as $restid) {
    // $redir_params['restid[]']=$restid;
    $string_redir.='&restid[]='.$restid;
  }
}
if (!empty($userid)) {
  $redir_params['userid']='_qf__force_multiselect_submission';
  foreach ($userid as $uid) {
    // $redir_params['userid[]']=$uid;
    $string_redir.='&userid[]='.$uid;
  }
}
if ($name != '') {
  $redir_params['name']=$name;
}

$download = optional_param('download', '', PARAM_ALPHA);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('report_board', 'block_mcdpde'));
$PAGE->set_heading(get_string('report_board', 'block_mcdpde'));
//$PAGE->requires->js_call_amd('block_mcdpde/params', 'init', array($string_redir));

navigationMenus::createAdminMenus();
navigationMenus::createPluginMenus(navigationMenus::CP_BOARD);

$model = new QueryModel();
$usr = $model->getUserPuesto();
$viewFilter = false;
//var_dump($consultant);
if ($usr) {
    //echo '<h5>'.$usr->no_cia.' '.$usr->no_emple.' '.$usr->nombre.' '.$usr->apellido.'</h5>';
  $model->configureBoardBasic(0, $userid, $country, $restaurant, $name, $consultant, $areaCode);
    $viewFilter = true;
} else {
    $model->configureBoardBasic($USER->id);
}
//
//echo $model->getSQL();

echo $OUTPUT->header(get_string('report_board', 'block_mcdpde'));
echo areaLinks::createLinks('/blocks/mcdpde/boards/board.php', $areaCode);
echo $OUTPUT->heading(get_string('report_boardheading', 'block_mcdpde'));

echo '<div class="reportTable">';
$t = new \flexible_table('mcdpde_brief');
$t->define_baseurl($viewURL);

$render = new resumeRender($t, true, $areaCode);
$render->populate($model);
$render->display();

// $t->define_columns($table->briefColumns);
// $t->define_headers($table->briefHeaders);
//
// $t->setup();
// foreach ($table->brief as $row) {
//     $t->add_data(array_values($row));
// }
// $t->finish_output();
echo '</div>';
echo $OUTPUT->footer();
