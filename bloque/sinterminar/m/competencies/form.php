<?php
require_once('../../../config.php');
require_once("{$CFG->libdir}/formslib.php");

class form extends moodleform {
 
    function definition() {
        global $DB;

        $sql = "SELECT * FROM mdl_competency WHERE id NOT IN ( SELECT competencyid FROM mdl_mcdpde_ability_competency ) ORDER BY shortname";
        $competencies = $DB->get_records_sql($sql);
        $areanames = array();                                                                                                       
        foreach ($competencies as $competency) {                                                                          
            $areanames[$competency->id] = $competency->shortname;                                                                  
        }                                                                                                                           
        $options = array(                                                                                                           
            'multiple' => false,                                                                                                     
        );
        
        $mform =& $this->_form;
        $mform->addElement('autocomplete', 'competencyid', get_string('competencies', 'block_mcdpde'), $areanames, $options);
        //$mform->addRule('ability', get_string('missing'), 'required', null, 'server');
        $this->add_action_buttons(false, get_string('save', 'block_mcdpde'));
    }
}
?>
