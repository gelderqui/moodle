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
use block_mcdpde\models\QueryModel;

$areaCode = optional_param('area',1,PARAM_INT);
$model = new QueryModel();
$users = $model->getRecordUserEmpleado(0,$areaCode);
global $DB, $OUTPUT, $USER, $PAGE;
require_login();

$context=context_system::instance();
$download = optional_param('download', '', PARAM_ALPHA);
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/Reporte2.php');
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/Reporte2.php');

$model2 = new QueryModel();
$perfilUser = $model2->getUserPuesto();
$perfil = preg_replace('/\s+/','',$perfilUser->perfil);

$userid = optional_param('userid', null, PARAM_INT);
$areaCode = optional_param('area',1,PARAM_INT);
//$redir_params = array('area'=>$areaCode);

//Filtros

$pais = optional_param('country','',PARAM_ALPHA);
$restaurante = optional_param('restid','',PARAM_INT);
$perfil = optional_param('puesto','',PARAM_ALPHA);
$co = optional_param('co',null,PARAM_INT);
$gr = optional_param('gr',null,PARAM_INT);
//$user = optional_param('userid',null,PARAM_INT);


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
$data3 = $model->query3($str1,$pais,$restaurante,$perfil,$co,$gr,$user);

// echo "<pre>";
// print_r($str1);
// echo "</pre>";
// die()

// $str1

$parent= [];
for($i=0;$i<count($data3);$i++){
     $parent[$i] = $data3[$i]->userid;
}
$parent= array_unique($parent);
$limit =count($data3)/count($parent); //in(599,99,99,)
$parent2 =[];
$add=3;
// start
$add2=-1;
$add3 = 0; 
$datos=[];
$datos2=[];
for($i =0;$i<count($data3);$i++){
     $datos[] = $data3[$i]->no_emple;
}
$datos = array_unique($datos);
$datos = array_values($datos);
for($i=0;$i<count($datos);$i++){    
     for($j=0;$j<count($data3);$j++){        
          if($datos[$i] ==$data3[$j]->no_emple){                    
               $tmp1["examenes"] =$data3[$j]->examenes;
               $datos2[$datos[$i]][$data3[$j]->course] = $tmp1;
          
          }
          
     }
}
//end

foreach($parent as $it){
     for($i =0; $i<count($data3);$i++){
          if($it ==$data3[$i]->userid){               
               $parent2[$it][0] = $data3[$i]->nombre;
               $parent2[$it][1] = $data3[$i]->no_cia.$data3[$i]->no_emple; //nocia.noemple
               $parent2[$it][2] = $data3[$i]->desc_puesto;   //desc_puesto             
               if($data3[$i]->sumgrades ===null){
                    $data3[$i]->sumgrades = "--";
               }               
               // $parent2[$it][$add] = $data3[$i]->sumgrades;
               $parent2[$it][$add] = $data3[$i]->sumgrades."||".$data3[$i]->minima;  
               $add++;
               $add2++;
               if($data3[$i]->ganado ==1){
                    $add3++;
               }
               if($add2==$datos2[$data3[$i]->no_emple][$data3[$i]->course]["examenes"]){
                    $add++;                   
                    // $parent2[$it][] =($add3*100)/$data3[$i]->examenes;
                    $parent2[$it][] =($add3*100)/$data3[$i]->examenes."%";
                    $add3 = 0;
                    $add2=0;
               }
          }
          else{
               $add=3;
          }
     }
}


$model2 = new QueryModel();
$perfilUser = $model2->getUserPuesto();
$perfil = preg_replace('/\s+/','',$perfilUser->perfil);
$pais = preg_replace('/\s+/','',$perfilUser->codigo_pais);
$obj->display($data1, $data2, $parent2, $pais, $perfil);
echo $OUTPUT->footer();