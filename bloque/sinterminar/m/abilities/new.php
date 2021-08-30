<?php

require_once('../../../config.php');
require_once('form_abilities.php');

global $DB, $OUTPUT, $PAGE;


$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('new_abilities', 'block_mcdpde'));
$PAGE->set_heading(get_string('new_abilities', 'block_mcdpde'));

require_login();
require_capability('block/mcdpde:allowmanage', $context);
$id = optional_param('id', 0, PARAM_INT);

//echo $OUTPUT->heading(get_string('abilitiestitle', 'block_mcdpde'));

$formAbilities = new form_abilities();

if ($formAbilities->get_data())
{
    $ability = new stdClass();
    $ability->ability = $formAbilities->get_data()->ability;

    $lastinsertid = $DB->insert_record('mcdpde_abilities', $ability, true);

    redirect(new moodle_url($CFG->wwwroot.'/blocks/mcdpde/competencies/view.php',array('id'=>$lastinsertid)));
    //$courseurl = new moodle_url('/blocks/mcdpde/compecourse/view.php', array('id' => $courseid));
    //redirect($courseurl);
}
else
{

    echo $OUTPUT->header(get_string('new_abilities', 'block_mcdpde'));
    $formAbilities->display();

    echo html_writer::tag('p', html_writer::link($CFG->wwwroot.'/blocks/mcdpde/abilities/view.php',get_string('backlist', 'block_mcdpde')));


    echo $OUTPUT->footer();
}
?>
