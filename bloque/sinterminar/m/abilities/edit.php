<?php

require_once('../../../config.php');
require_once('form_abilities.php');

global $DB, $OUTPUT, $PAGE;


$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/mcdpde/abilities/edit.php');
require_login();
require_capability('block/mcdpde:allowmanage', $context);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('new_abilities', 'block_mcdpde'));
$PAGE->set_heading(get_string('new_abilities', 'block_mcdpde'));


$id = required_param('id', PARAM_INT);

if (!$ability = $DB->get_record('mcdpde_abilities', array('id' => $id)))
    print_error('invalidability', 'block_mcdpde', $id);

$url=$CFG->wwwroot."/blocks/mcdpde/abilities/edit.php";

$formAbilities = new form_abilities($url, array('id' => $id , 'ability' => $ability));

if ($formAbilities->get_data())
{
    $ability = new stdClass();
    $ability->ability = $formAbilities->get_data()->ability;
    $ability->id = $formAbilities->get_data()->id;

    $DB->update_record('mcdpde_abilities', $ability, true);

    redirect(new moodle_url($CFG->wwwroot.'/blocks/mcdpde/competencies/view.php',array('id'=>$id)));
    //$courseurl = new moodle_url('/blocks/mcdpde/compecourse/view.php', array('id' => $courseid));
    //redirect($courseurl);
}
else
{

    echo $OUTPUT->header(get_string('new_abilities', 'block_mcdpde'));
    $formAbilities->display();

    echo html_writer::tag('p', html_writer::link($CFG->wwwroot.'/blocks/mcdpde/abilities/view.php',get_string('backlist', 'block_mcdpde')));


    // echo $OUTPUT->heading(get_string('abilitiestitle', 'block_mcdpde').': '.enrolHelper::getString($roleType));


    echo $OUTPUT->footer();
}
?>
