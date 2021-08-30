<?php

require_once('../../../config.php');

use block_mcdpde\models\QueryModel;
use block_mcdpde\tables\medalTable;
use block_mcdpde\renders\boardRender;
use block_mcdpde\helpers\navigationMenus;

global $DB, $OUTPUT, $USER, $PAGE;

require_login();


$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/medalboard.php');

$userid = optional_param_array('userid', array(), PARAM_INT);
$country = optional_param_array('country', array(), PARAM_ALPHA);
$consultant = optional_param_array('consultant', array(), PARAM_INT);
$restaurant = optional_param_array('restid', array(), PARAM_TEXT);
$name = optional_param('name', '', PARAM_ALPHA);
$download = optional_param('download', '', PARAM_ALPHA);
$areaCode = optional_param('area',1,PARAM_INT);
$redir_params = array('area'=>$areaCode);
$string_redir = '';
if (!empty($restaurant)) {
  $redir_params['restid']='_qf__force_multiselect_submission';
  foreach ($restaurant as $restid) {
    //$redir_params['restid[]']=$restid;
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

$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/medalboard.php',$redir_params);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('report_medals', 'block_mcdpde'));
$PAGE->set_heading(get_string('report_medals', 'block_mcdpde'));
$PAGE->requires->js_call_amd('block_mcdpde/params', 'init', array($string_redir));

navigationMenus::createAdminMenus();
navigationMenus::createPluginMenus(navigationMenus::CP_MEDALS);


$model = new QueryModel();
$model->setMedals();
$usr = $model->getUserPuesto();
$viewFilter = false;
//var_dump($consultant);
if ($usr) {
    $model->configureBoardBasic(0, $userid, $country, $restaurant, $name, $consultant, 1, 1);
    $viewFilter = true;
} else {
    $model->configureBoardBasic($USER->id);
}


$table = new medalTable('mcdpde_myboard', $download);

$table->setModel($model);
$table->define_baseurl($viewURL);
$table->is_downloading(
  $download,
  get_string('report_medals', 'block_mcdpde'),
  get_string('report_medals', 'block_mcdpde')
);

$render = new boardRender($table, get_string('report_medals', 'block_mcdpde'), 30);
if (!$table->is_downloading()) {
    echo $OUTPUT->header(get_string('report_medals', 'block_mcdpde'));
    echo '<div class="reportTable">';
}
$render->display($viewFilter, $table->is_downloading());
if (!$table->is_downloading()) {
    echo '</div>';

    echo $OUTPUT->footer();
}
