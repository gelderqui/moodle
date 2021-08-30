<?php
namespace block_mcdpde\forms;
require_once("{$CFG->libdir}/formslib.php");

/**
 * Form for categories on hablities
 */
class categoryForm extends \moodleform
{

  public function definition()
  {
    $form =& $this->_form;

    $form->addElement('hidden', 'id', 0);
    $form->setType('id', PARAM_INT);
    $form->addElement('hidden', 'areaid');
    $form->setType('areaid', PARAM_INT);
    $form->setDefault('areaid', $this->_customdata['areaid']);
    $form->addElement('text','name', get_string('name','block_mcdpde'));
    $form->setType('name', PARAM_TEXT);
    $form->addRule('name', get_string('required', 'block_mcdpde'), 'required');
    $orderData = array ( 1 => 1, 2 => 2, 3 => 3 );
    $form->addElement('select','position', get_string('order','block_mcdpde'), $orderData);

    $this->add_action_buttons(true, get_string('add','block_mcdpde'));

  }
}


?>
