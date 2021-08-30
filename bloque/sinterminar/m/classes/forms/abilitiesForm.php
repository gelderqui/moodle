<?php
namespace block_mcdpde\forms;
require_once("{$CFG->libdir}/formslib.php");

use block_mcdpde\models\categoriesModel;

/**
 * Form for categories on hablities
 */
class abilitiesForm extends \moodleform
{

  public function definition()
  {
    $form =& $this->_form;

    $form->addElement('hidden', 'id', 0);
    $form->setType('id', PARAM_INT);
    $form->addElement('hidden', 'areaid');
    $form->setType('areaid', PARAM_INT);
    $form->setDefault('areaid', $this->_customdata['areaid']);
    $form->addElement('text','ability', get_string('name','block_mcdpde'));
    $form->setType('ability', PARAM_TEXT);
    $form->addRule('ability', get_string('required', 'block_mcdpde'), 'required');

    $form->addElement('text', 'intervaldate', get_string('interval', 'block_mcdpde'));
    $form->setType('intervaldate', PARAM_INT);
    $form->addRule('intervaldate', get_string('required', 'block_mcdpde'), 'required');
    $form->addRule('intervaldate', get_string('required', 'block_mcdpde'), 'numeric');

    $intervaltypeData = array(
                              'd' => get_string('day', 'block_mcdpde'),
                              'm' => get_string('month', 'block_mcdpde'),
                              'y' => get_string('year', 'block_mcdpde'),
                            );

    $form->addElement('select','intervaltype', get_string('intervaldate', 'block_mcdpde'), $intervaltypeData);

    $categories = array();
    $categoriesList = categoriesModel::getAllCategories( $this->_customdata['areaid']);

    foreach ($categoriesList as $cat) {
      $categories[$cat->id] = $cat->name;
    }

    $form->addElement('select','categoriesid', get_string('category', 'block_mcdpde'), $categories);

    $this->add_action_buttons(true, get_string('add','block_mcdpde'));

  }
}


?>
