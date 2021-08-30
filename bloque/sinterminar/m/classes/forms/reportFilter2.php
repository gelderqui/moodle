<?php
namespace block_mcdpde\forms;
require_once("{$CFG->libdir}/formslib.php");

use block_mcdpde\models\QueryModel;
use block_mcdpde\models\ReporteModel2;

/**
 * reportFilter class for generate filters
 */
class reportFilter2 extends \moodleform
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

    $model1 = new ReporteModel2();
    $puestos = $model1->listaPuestos();
    $cos = $model1->listaCO();
    $grs = $model1->listaGR();
    $restaurante = $model1->listaRes();

    $puestosArray=array();
    $COArray=array();
    $GRArray=array();
    $RestaurantArray=array();

    foreach ($puestos as $puesto) {
      $puestosArray[$puesto->perfil]=$puesto->desc_perfil;
    }
    foreach ($cos as $co) {
      $COArray[$co->userid]=$co->nombre." ".$co->apellido;
    }
    foreach ($grs as $gr) {
      $GRArray[$gr->userid]=$gr->nombre." ".$co->apellido;
    }
    foreach ($restaurante as $res) {
      $RestaurantArray[$res->nombre_rest]=$res->nombre_rest;
    }

    //if ($USER->id == 2) echo $model->getUserEmpleadoSQL(0, $areaCode);
    $codes = array();
    $countries = array();
    $restaurants = array();
    //$consultant = array();
    foreach ($users as $user) {
      //$codes[$user->id] = $user->id;
      $codes[$user->id] = $user->no_cia.$user->no_emple;
      $countries[$user->codigo_pais] = get_string($user->codigo_pais,'countries');
      //$restaurants[$user->codigo_pais.$user->no_rest] = $user->nombre_rest;
      $restaurants[$user->no_rest] = $user->nombre_rest;
      //$consultant[$user->cod_cons] = $user->conombre.' '.$user->coapellido;
      $user->id;
    }
    // echo "<br />";
    // var_dump($restaurants);
    $opts = array(
      'multiple' => true,

    );

    $form->addElement('hidden', 'area');
    $form->setType('area', PARAM_INT);
    $form->setDefault('area', $areaCode);

    //$form->addElement('autocomplete', 'userid', get_string('usercode', 'block_mcdpde'), $codes);
    

    $perfil = preg_replace('/\s+/','',$perfilUser->perfil);

    if ( $perfil != 'CH' && $perfil != 'GR' && $perfil != 'CO') {
      $form->addElement('autocomplete', 'co', get_string('CO', 'block_mcdpde'), $COArray);
      $form->addElement('autocomplete', 'gr', get_string('GR', 'block_mcdpde'), $GRArray);
    }
    $form->addElement('autocomplete', 'country', get_string('country', 'block_mcdpde'), $countries);
    $form->addElement('autocomplete', 'restid', get_string('restaurant', 'block_mcdpde'), $restaurants);
    //$form->addElement('autocomplete', 'restid', get_string('restaurant', 'block_mcdpde'), $RestaurantArray);
    $form->addElement('autocomplete', 'puesto', get_string('PuestosDisponibles', 'block_mcdpde'), $puestosArray);


    // $form->addElement('autocomplete', 'consultant', get_string('consultant', 'block_mcdpde'), $consultant, $opts);
    // $form->addElement('text','name', get_string('name','block_mcdpde'));
    // $form->setType('name', PARAM_TEXT);

    $form->addElement('submit','submitbutton',get_string('find','block_mcdpde'));
  }
}


?>
