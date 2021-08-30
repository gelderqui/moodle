<?php

require_once('../../../config.php');

global $DB, $OUTPUT, $PAGE;



$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/abilities/view.php');
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/abilities/view.php');

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('abilities', 'block_mcdpde'));
$PAGE->set_heading(get_string('abilities', 'block_mcdpde'));


$id = required_param('id', PARAM_INT);

//echo $OUTPUT->header(get_string('abilities', 'block_mcdpde'));
if (!$ability = $DB->get_record('mcdpde_abilities', array('id' => $id))) {
    print_error('invalidability', 'block_mcdpde', $id);
}
$competencies = $DB->get_record('mcdpde_ability_competency', array('abilityid' => $id));

if( !$competencies )
{
    $DB->delete_records('mcdpde_abilities',array('id' => $id));
    redirect(new moodle_url($CFG->wwwroot.'/blocks/mcdpde/abilities/view.php'));
}
else
    print_error('invalid ability', 'block_mcdpde', $id);

$PAGE->set_heading($ability->ability);
echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('abilitiestitle', 'block_mcdpde').': '.enrolHelper::getString($roleType));

echo $OUTPUT->footer();
?>
