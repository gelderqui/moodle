<?php

require_once "../modelos/Querymodelos.php";
//llamado  a la base de datos
$reporte1 = new Consultas();
//Variables a utilizar
$fecha_inicio=$_REQUEST["fecha_inicio"];
$fecha_fin=$_REQUEST["fecha_fin"];
//Trasformacion de fecha
$inicio=strtotime($fecha_inicio);
$fin=strtotime($fecha_fin);
$codigo=$_REQUEST["codigo"];
$curso=$_REQUEST["curso"];
$area=$_REQUEST["area"];

if($curso=='seleccionacurso'){
  $curso="";
}
if($area=='seleccionaarea'){
  $area="";
}

$rspta=$reporte1->reportegeneral($inicio,$fin,$codigo,$curso,$area);
        $data= Array();

          foreach ($rspta as $key => $valor) {
              $data[]=array(
                "0"=>$valor->id,
                "1"=>$valor->nombre,
                "2"=>$valor->codigo,
                "3"=>$valor->department,
                "4"=>$valor->curso,
                "5"=>$valor->inicio,
                "6"=>$valor->final,
                "7"=>$valor->avance,
                "8"=>$valor->nota,
                "9"=>$valor->completado

                            );
              }

        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);

  exit;
