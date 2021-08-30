<?php

require_once('../../../config.php');

use block_mcdpde\models\QueryModel;
use block_mcdpde\tables\boardPopTable;
use block_mcdpde\renders\boardRender;
use block_mcdpde\helpers\navigationMenus;
use block_mcdpde\helpers\areaLinks;

global $DB, $OUTPUT, $USER, $PAGE;

require_login();


$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/popboard.php');
$PAGE->requires->css('/blocks/mcdpde/myboard.css');
$PAGE->requires->js_call_amd('block_mcdpde/params', 'popboardTable');

$userid = optional_param_array('userid',array(), PARAM_INT);
$country = optional_param_array('country',array(), PARAM_ALPHA);
$consultant = optional_param_array('consultant',array(), PARAM_INT);
$restaurant = optional_param_array('restid',array(), PARAM_TEXT);
$name = optional_param('name','',PARAM_ALPHA);
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
    //$redir_params['userid[]']=$uid;
    $string_redir.='&userid[]='.$uid;
  }
}
if ($name != '') {
  $redir_params['name']=$name;
}

$download = optional_param('download', '', PARAM_ALPHA);

$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/popboard.php', $redir_params);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('report_popboard', 'block_mcdpde'));
$PAGE->set_heading(get_string('report_popboard', 'block_mcdpde'));
$PAGE->requires->js_call_amd('block_mcdpde/params', 'init', array($string_redir));

navigationMenus::createAdminMenus();
navigationMenus::createPluginMenus(navigationMenus::CP_POP);

$model = new QueryModel();
$usr = $model->getUserPuesto();
$viewFilter = false;
//var_dump($consultant);
if ($usr) {
  //echo '<h5>'.$usr->no_cia.' '.$usr->no_emple.' '.$usr->nombre.' '.$usr->apellido.'</h5>';
  $model->configureBoardBasic(0, $userid, $country, $restaurant, $name, $consultant, $areaCode);
  $viewFilter = true;
}
else {
  $model->configureBoardBasic($USER->id);
}

//Ahora
echo $model->getSQLQuery();


$table = new boardPopTable('mcdpde_popboard', $download, $areaCode);
$table->setModel($model);
$table->define_baseurl($viewURL);
$table->is_downloading(
  $download,
  get_string('report_popboard', 'block_mcdpde'),
  get_string('report_popboard', 'block_mcdpde')
);

$render = new boardRender($table, get_string('report_popboard', 'block_mcdpde'),30, true, $areaCode);

if (!$table->is_downloading()) {
  echo $OUTPUT->header(get_string('report_popboard', 'block_mcdpde'));
  echo areaLinks::createLinks('/blocks/mcdpde/boards/popboard.php', $areaCode);
  echo '<div class="reportTable">';
}

$render->display($viewFilter, $table->is_downloading());

if (!$table->is_downloading()) {
  echo '</div>';
  echo $OUTPUT->footer();
}
