<?php
namespace block_mcdpde\forms;
require_once("{$CFG->libdir}/formslib.php");
use block_mcdpde\models\competenciesModel;

/**
 * Form for categories on hablities
 */
class competencyForm extends \moodleform
{

  public function definition()
  {
    $form =& $this->_form;

    $competencies = competenciesModel::getCompetenciesAvalaible();
    $areanames = array();
    if (isset($this->_customdata) && array_key_exists('competency', $this->_customdata)) {
        $areanames[$this->_customdata['competency']->id] = $this->_customdata['competency']->shortname;
    }
    foreach ($competencies as $competency) {
        $areanames[$competency->id] = $competency->shortname;
    }

    $form->addElement('hidden', 'id', 0);
    $form->setType('id', PARAM_INT);

    $form->addElement('hidden', 'abilityid', 0);
    $form->setType('abilityid', PARAM_INT);

    $form->addElement('select', 'competencyid', get_string('competencies', 'block_mcdpde'), $areanames);

    $levelabData = array(
                      'A' => get_string('advanceds', 'block_mcdpde'),
                      'B' => get_string('basics','block_mcdpde')
                    );

    $form->addElement('select', 'levelab', get_string('level', 'block_mcdpde'), $levelabData);

    $medalData = array(
                      'N' => get_string('none', 'block_mcdpde'),
                      'B' => get_string('bronze', 'block_mcdpde'),
                      'P' => get_string('silver','block_mcdpde'),
                      'O' => get_string('gold','block_mcdpde'),
                    );
    $form->addElement('select', 'medal', get_string('medal', 'block_mcdpde'), $medalData);

    $this->add_action_buttons(true, get_string('add','block_mcdpde'));
  }
}


?>
