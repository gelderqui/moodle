<?php
require_once("{$CFG->libdir}/formslib.php");

class form_abilities extends moodleform {

    function definition() {

        $mform =& $this->_form;
        $mform->addElement('text', 'ability', get_string('ability', 'block_mcdpde'));

        if( $this->_customdata['ability'] )
        {
            $ability = $this->_customdata['ability'];
            $mform->addElement('hidden','id');
            $mform->setDefault('ability',$ability->ability);
            $mform->setDefault('id',$ability->id);
        }

        $mform->addRule('ability', get_string('missing', 'block_mcdpde'), 'required', null, 'server');
        $this->add_action_buttons(false, get_string('save', 'block_mcdpde'));
    }
}
?>
