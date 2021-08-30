<?php
namespace block_mcdpde\forms;
require_once("{$CFG->libdir}/formslib.php");

use block_mcdpde\models\QueryModel;
/**
 * reportFilter class for generate filters
 */
class reportFilter extends \moodleform
{
  //
  // function __construct(argument)
  // {
  //   # code...
  // }

  public function definition()
  {
    global $USER;

    $form =& $this->_form;

    $areaCode = optional_param('area',1,PARAM_INT);
    $model = new QueryModel();
    $users = $model->getRecordUserEmpleado(0,$areaCode);
    $perfilUser = $model->getUserPuesto();
    //var_dump($USER);
    if ($USER->id == 2)
      echo $model->getUserEmpleadoSQL(0, $areaCode);
    $codes = array();
    $countries = array();
    $restaurants = array();
    $consultant = array();
    foreach ($users as $user) {
      $codes[$user->id] = $user->no_cia.$user->no_emple;
      // $countries[$user->codigo_pais] = get_string($user->codigo_pais,'countries');
      $restaurants[$user->codigo_pais.$user->no_rest] = $user->nombre_rest;
      // $consultant[$user->cod_cons] = $user->conombre.' '.$user->coapellido;
    }
    // echo "<br />";
    // var_dump($restaurants);
    $opts = array(
      'multiple' => true,

    );

    $form->addElement('hidden', 'area');
    $form->setType('area', PARAM_INT);
    $form->setDefault('area', $areaCode);

    $form->addElement('autocomplete', 'userid', get_string('usercode', 'block_mcdpde'), $codes, $opts);
    // $form->addElement('autocomplete', 'country', get_string('country', 'block_mcdpde'), $countries, $opts);
    // $form->addElement('autocomplete', 'consultant', get_string('consultant', 'block_mcdpde'), $consultant, $opts);
    $perfil = preg_replace('/\s+/','',$perfilUser->perfil);
    if ( $perfil != 'CH' && $perfil != 'GR' ) {
      $form->addElement('autocomplete', 'restid', get_string('restaurant', 'block_mcdpde'), $restaurants, $opts);
    }
    $form->addElement('text','name', get_string('name','block_mcdpde'));
    $form->setType('name', PARAM_TEXT);

    $form->addElement('submit','submitbutton',get_string('find','block_mcdpde'));
  }
}


?>
