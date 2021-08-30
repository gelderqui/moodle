<?php 
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

require_once('../../../config.php');
require_once("{$CFG->libdir}/tablelib.php");

use block_mcdpde\models\ReporteModel2;
use block_mcdpde\tables\seccionesTable;
use block_mcdpde\renders\Reporte2Render;
use block_mcdpde\forms\nameFilter;

global $DB, $OUTPUT, $USER, $PAGE;
require_login();

$context=context_system::instance();
$download = optional_param('download', '', PARAM_ALPHA);
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/Reporte2.php');
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/Reporte2.php');

$userid = optional_param('userid', null, PARAM_INT);
$areaCode = optional_param('area',1,PARAM_INT);
//$redir_params = array('area'=>$areaCode);

//Filtros
$restaurante = optional_param('restid','',PARAM_ALPHA);
$pais = optional_param('country','',PARAM_ALPHA);


$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('report_secciones', 'block_mcdpde'));
$PAGE->set_heading(get_string('report_secciones', 'block_mcdpde'));
echo $OUTPUT->header(get_string('report_secciones', 'block_mcdpde'));

$obj = new Reporte2Render();
$model = new ReporteModel2();
$filter = new nameFilter();
$data1 = $model->query1();
$data2 = $model->query2();


$users = $filter->definition2();
$users = array_keys($users);
$nusers  = "";
$cont1 =0;
$str1 = "in(";
$count =  count($users);
$size = $count/500;
$rest =  1 / $size;
if(($rest >0) and ($rest<1) ){
     $size ++;
     $v = explode(".",$size);
     $size = $v[0];
}
$cont = 0;
$cont2 = 1;
$cont3 =1;
$n= 500;
$str1 = "in";
while($cont <$size){
    // echo $cont. "-".$size."<br>";
    if($cont == $size){
         $n =$count%($size-1);
    }
    $str1.="(";
    while($cont2<=$n){        
         if(isset($users[$cont3])){
              //echo $cont3.",";          
              $str1 .= $users[$cont3].",";          
         }
         $cont2++;          
         $cont3++;
    }
    $str1 = substr($str1, 0, -1);
    $str1 .=") or u.id in ";    
    if($cont == $size){
        $str1 .=")  ";
    }
    $cont2 =1;
    $cont++;     
}

$str1 = substr($str1, 0, -11);
$names =[];
$cont =0;

foreach($nusers as $it){
    $names[$cont][$id] = $it->id;
    $cont ++;
}

$nusers = substr($nusers, 0, -1);
$data3 = $model->query3($str1,$pais);

$parent= [];
for($i=0;$i<count($data3);$i++){
     $parent[$i] = $data3[$i]->no_emple;
}
$parent= array_unique($parent);
$limit =count($data3)/count($parent);
$parent2 =[];
$add=3;
$add2=0;
// echo "<pre>";
// print_r($data3);
// echo "</pre>";
// die();
$add1 =0;
foreach($parent as $it){
     for($i =0; $i<count($data3);$i++){
          if($it ==$data3[$i]->no_emple){
               $parent2[$it][0] = $data3[$i]->no_cia.$data3[$i]->no_emple ; //nocia.noemple
               $parent2[$it][1] = $data3[$i]->nombre;
               $parent2[$it][2] = $data3[$i]->desc_puesto;   //desc_puesto             
               if($data3[$i]->sumgrades ===null){
                    $data3[$i]->sumgrades = 0;
               }
               
               $parent2[$it][$add] = $data3[$i]->sumgrades;
               $add++;
               $add1++;
               if($add1 ==$data3[$i]->examenes){
                    $add1++;
                    $parent2[$it][$add1] = "dato";
                    
               }
          }
          else{
               $add=3;
          }
     }
}


$obj->display($data1, $data2, $parent2);

echo $OUTPUT->footer();