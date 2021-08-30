<?php
namespace block_mcdpde\forms;
require_once("{$CFG->libdir}/formslib.php");
use block_mcdpde\models\QueryModel;

class nameFilter extends \moodleform
{
  public function definition()
  {
    global $USER;
    $form =& $this->_form;
    $areaCode = optional_param('area',1,PARAM_INT);
    $model = new QueryModel();
    $users = $model->getRecordUserEmpleado(0,$areaCode);
    $perfilUser = $model->getUserPuesto();

    //if ($USER->id == 2)
     // echo $model->getUserEmpleadoSQL(0, $areaCode);
    $codes = array();
    foreach ($users as $user) {
      // $codes[$user->id] = $user->no_cia.$user->no_emple;
      $codes[$user->id] = $user->no_cia.$user->no_emple." - ".$user->nombre." ".$user->apellido ;
      //$codes[$user->id] = $user->id;
    }
    $opts = array(
      'multiple' => false,
    );

    $form->addElement('hidden', 'area');
    $form->setType('area', PARAM_INT);
    $form->setDefault('area', $areaCode);
    $form->addElement('autocomplete', 'userid', get_string('usercode', 'block_mcdpde'), $codes,$opts);
    $form->addElement('submit','submitbutton',get_string('find','block_mcdpde'));
  }

  public function definition2()
  {
    global $USER;
    $form =& $this->_form;
    $areaCode = optional_param('area',1,PARAM_INT);
    $model = new QueryModel();
    $users = $model->getRecordUserEmpleado(0,$areaCode);
    $perfilUser = $model->getUserPuesto();
    return $users;
    
  }
}


?>
