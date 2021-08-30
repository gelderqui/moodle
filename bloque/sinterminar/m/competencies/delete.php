<?php

require_once('../../../config.php');

global $DB, $OUTPUT, $PAGE;
$context=context_system::instance();
$PAGE->set_context($context);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('competencies', 'block_mcdpde'));
$PAGE->set_heading(get_string('competencies', 'block_mcdpde'));
$viewURL= new moodle_url('/blocks/mcdpde/competencies/delete.php');
$PAGE->set_url($viewURL);
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$id = required_param('id', PARAM_INT);
$abilityid = required_param('ability', PARAM_INT);

if (!$ability = $DB->get_record('mcdpde_ability_competency', array('id' => $id)))
    print_error('invalidability', 'block_mcdpde', $id);
else
{
    $DB->delete_records('mcdpde_ability_competency',array('id' => $id, 'abilityid' => $abilityid));
}

redirect(new moodle_url($CFG->wwwroot.'/blocks/mcdpde/competencies/view.php',array('id'=>$abilityid)));
?>
