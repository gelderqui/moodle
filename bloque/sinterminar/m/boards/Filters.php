<?php 
require_once('../../../config.php');
require_once("{$CFG->libdir}/tablelib.php");
global $DB, $OUTPUT, $USER, $PAGE;
$context=context_system::instance();
$download = optional_param('download', '', PARAM_ALPHA);
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/Filters.php');
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/Filters.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('report_filters', 'block_mcdpde'));
$PAGE->set_heading(get_string('report_filters', 'block_mcdpde'));
if(isset($_GET)){
     echo $_GET["id"];
}

?>

<h1>titulo</h1>