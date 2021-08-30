<?php
require_once "../modelos/Querymodelos.php";
//Llamada a metodos de la base de datos
$reporte1 = new Consultas();
//Variables
$fecha_inicio=$_REQUEST["fecha_inicio"];
$fecha_fin=$_REQUEST["fecha_fin"];
//Convertir fecha
$inicio=strtotime($fecha_inicio);
$fin=strtotime($fecha_fin);


$codigo=$_REQUEST["codigo"];
$curso=$_REQUEST["curso"];
$area=$_REQUEST["area"];

//Colocar valor vacio para traer todos los datos por like
if($area=='seleccionaarea'){$area="";}
if($codigo==''){$codigo="";}
if($curso=='seleccionacurso'){ $curso="";}

//Cargar datos varios para cargar las graficas
if(isset($_REQUEST["option"]) && $_REQUEST["option"] == "getcurso"){

    $rspta=$reporte1->reportegeneral($inicio,$fin,$codigo,$curso,$area);
    $rspta2=$reporte1->grafica4($inicio,$fin,$codigo,$curso,$area);
    $rspta3=$reporte1->reportegrafica($inicio,$fin,$codigo,$curso,$area);

     $data= Array();
     $tiendas=Array();
     $notas=Array();
     //LLenar filtros
      foreach ($rspta as $key => $valor) {
                  $data[]=array(
                    "1"=>$valor->curso,
                    "2"=>$valor->codigo ,
                    "3"=>$valor->department);
                  }

      foreach ($rspta2 as $key => $value1) {
                     $tiendas[] = $value1->department;
                     $notas[] = $value1->nota;
                  }

    $noiniciados=Array();
    $finalizados=Array();

      foreach ($rspta3 as $key => $value2) {
                      $noiniciados[] = $value2->noiniciado;
                      $finalizados[] = $value2->finalizados;
                  }

    $cursosendata=Array();

    foreach ($data as $key => $value) {
                       if (isset($value[1])){
                        if (in_array($value[1], $cursosendata)){}
                        else {
                          array_push($cursosendata,$value[1]);
                        }
                       }
                     }

    $estudiante=Array();
    foreach ($data as $key => $value) {
                                     if (isset($value[2])){
                                      if (in_array($value[2], $estudiante)){}
                                      else {
                                        array_push($estudiante,$value[2]);
                                      }
                                     }
                                   }

    $tienda=Array();
    foreach ($data as $key => $value) {
                                     if (isset($value[3])){
                                       if (in_array($value[3], $tienda)){}
                                         else {
                                           array_push($tienda,$value[3]);
                                         }
                                       }
                                     }

    $arrayName = array(
                  'cursos' => $cursosendata,
                  'estudiantes'=>$estudiante,
                  'tiendasL'=>$tienda,
                  'tiendas' => $tiendas,
                  'notas' => $notas,
                  'noiniciado'=>$noiniciados,
                  'finalizados'=>$finalizados
                  );
                echo json_encode($arrayName);
}

//Grafica Notas por curso en cada tienda
if(isset($_REQUEST["option"]) && $_REQUEST["option"] == "grafica1"){
  $idcurso1=$_REQUEST["idcurso1"];
  $grafica1=$reporte1->grafica1($inicio,$fin,$codigo,$curso,$area,$idcurso1);

   $tiendas=Array();
   $notas=Array();
  foreach ($grafica1 as $key => $value) {
     $tiendas[] = $value->department;
     $notas[] = $value->nota;
  }
  $arrayName = array(
    'tiendas' => $tiendas,
    'notas' => $notas);
      echo json_encode($arrayName);
}

//Grafica notas por estudiante
if(isset($_REQUEST["option"]) && $_REQUEST["option"] == "grafica3"){
  $idstudent=$_REQUEST["idstudent"];
  $grafica3=$reporte1->grafica3($inicio,$fin,$codigo,$curso,$area,$idstudent);

   $curso=Array();
   $notas=Array();
  foreach ($grafica3 as $key => $value) {
     $curso[] = $value->curso;
     $notas[] = $value->nota;
  }
  $arrayName = array(
    'curso' => $curso,
    'notas' => $notas);
      echo json_encode($arrayName);
}

//Grafica estado de usuarios por tienda
if(isset($_REQUEST["option"]) && $_REQUEST["option"] == "graficaF"){
  $area=$_REQUEST["area"];
  $graficaF=$reporte1->graficaF($inicio,$fin,$codigo,$curso,$area);

   $noiniciados=Array();
   $finalizados=Array();
  foreach ($graficaF as $key => $value) {
     $noiniciados[] = $value->noiniciado;
     $finalizados[] = $value->finalizados;
  }
  $arrayName = array(
    'noiniciado'=>$noiniciados,
    'finalizados'=>$finalizados);
      echo json_encode($arrayName);
}
  exit;
