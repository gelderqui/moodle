<?php
use block_mcdpde\renders\seccionesRender;
use block_mcdpde\models\seccionesModel;
use block_mcdpde\tables\seccionesTable;
use block_mcdpde\models\QueryModel;

require_once('../../../config.php');
require_once("{$CFG->libdir}/tablelib.php");
require('../fileg/pdf/fpdf.php');
require_login();

global $DB, $OUTPUT, $USER, $PAGE;
$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/seccionesimprimir.php');
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/seccionesimprimir.php');

//Aqui recogemos el id de usuario que se envio desde render en la funcin datos individuales
$userid = optional_param('userid', null, PARAM_INT);

$model=new seccionesModel();
$model->configureTest($userid);
$consulta=$model->consulta();

class PDF extends FPDF
{
    // Cabecera de página
    function Header(){
        //Obtener fecha
        date_default_timezone_get("America/Guatemala");
        $fecha= date("d-m-Y");
        
        $userid = optional_param('userid', null, PARAM_INT);
        $areaCode = optional_param('area',1,PARAM_INT);
        $redir_params = array('area'=>$areaCode);
        $datos1=new QueryModel();
        $empleado=$datos1->getRecordUserEmpleado($userid,$areaCode);    
        //Obtenemos los datos de usuario
        foreach($empleado as $individual){
            $nombre=$individual->nombre;
            $apellido=$individual->apellido;
            $puesto=$individual->desc_puesto;
            $cia=$individual->no_cia;
            $emp=$individual->no_emple;
        } 

        // Logo
        $this->Image('../fileg/img/agua.png',15,80,180);
        $this->Image('../fileg/img/banner.jpg',25,13,170);
        // Arial bold 15
        $this->AddFont('CenturyGothic','','GOTHIC.php');
        $this->SetFont('CenturyGothic','',15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Ln(15);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',12); 
        $this->Cell(20,8,utf8_decode('Código: '),0,0,'c',0);
        $this->SetFont('CenturyGothic','',12);
        $this->Cell(25,8,utf8_decode($cia." ".$emp),0,1,'c',0);

        $this->SetFont('Arial','B',12); 
        $this->Cell(20,8,utf8_decode('Nombre: '),0,0,'c',0);
        $this->SetFont('CenturyGothic','',12);
        $this->Cell(60,8,utf8_decode($nombre.' '.$apellido),0,1,'c',0);

        $this->SetFont('Arial','B',12); 
        $this->Cell(20,8,utf8_decode('Puesto: '),0,0,'c',0);
        $this->SetFont('CenturyGothic','',12);
        $this->Cell(60,8,utf8_decode($puesto),0,1,'c',0);

        $this->SetFont('Arial','B',12); 
        $this->Cell(50,8,utf8_decode('Certificado generado el: '),0,0,'c',0);
        $this->SetFont('CenturyGothic','',12);
        $this->Cell(30,8,utf8_decode($fecha),0,1,'c',0);
        /*$this->Cell(0,8,utf8_decode('Código: '.$cia." ".$emp),0,1,'c',0);
        $this->Cell(0,8,utf8_decode('Nombre: '.$nombre.' '.$apellido),0,1,'c',0);
        $this->Cell(0,8,utf8_decode('Puesto: '.$puesto),0,1,'c',0);
        $this->Cell(0,8,utf8_decode('Certificado generado el: '.$fecha),0,1,'c',0);*/

        $this->Ln(2);
        $this->SetTextColor(255,255,255);
        $this->SetFillColor(49,127,67);
        //$this->SetFillColor(197,0,0);
        $this->Cell(110,8,utf8_decode('Curso'),1,0,'C',true);
        $this->Cell(20,8,utf8_decode('Nota'),1,0,'C',true);
        $this->Cell(40,8,utf8_decode('Fecha'),1,1,'C',true);
    }

    // Pie de página
    function Footer(){
        date_default_timezone_get("America/Guatemala");
        $a= date("Y")+1;
        $d= date("d");
        $m=date("m");
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->AddFont('CenturyGothic','','GOTHIC.php');
        $this->SetFont('CenturyGothic','',8);

        $this->SetTextColor(0,0,0);
        //Pie + numero de pagina
        $this->Cell(0,10,utf8_decode('Esta certificación es válido hasta '.$d.'/'.$m.'/'.$a.'.  Página').$this->PageNo().'/{nb}',0,1,'C');
        $this->Image('../fileg/img/pie.jpg',25,255,170);
        // Número de página
        //$this->Cell(0,10,utf8_decode('Página').$this->PageNo().'/{nb}',0,0,'C');
    }
}

//$pdf = new PDF();
$pdf = new PDF('P','mm','Letter');
//izquierda,arriba,derecha
$pdf->SetMargins(25, 30 , 25);
//Inferior
$pdf->SetAutoPageBreak(true,30); 

$pdf->AliasNbPages();
$pdf->AddPage();
//Letra
$pdf->AddFont('CenturyGothic','','GOTHIC.php');
$pdf->SetFont('CenturyGothic','',10);
//$pdf->SetFont('Arial','B',16);
//$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(225,225,225);
//$pdf->SetFont('Arial','',16);
$contador=0;
date_default_timezone_get("America/Guatemala");
date_default_timezone_get("America/Guatemala");
$a= date("Y")-1;
$d= date("d");
$m=date("m");
$fechaanterior=$a."-".$m."-".$d;
//$fechaanterior="2021-02-28";
foreach($consulta as $producto => $detalles)
{
    
    if($contador%2==0)$borde=false; else $borde=true;
    if($detalles->sumgrades>89 && (date("Y-m-d",strtotime($detalles->fecha)))>=$fechaanterior)$pdf->SetTextColor(0,0,0);else$pdf->SetTextColor(255,0,0);
    //if($detalles->sumgrades>89)$pdf->SetTextColor(0,0,0);else$pdf->SetTextColor(255,0,0);//Pne en rojo los cursos menores a 80
    //if((date("Y-m-d",strtotime($detalles->fecha)))>=$fechaanterior)$pdf->SetTextColor(0,0,0);else$pdf->SetTextColor(255,0,0);
    $pdf->Cell(110,6,utf8_decode($detalles->name),1,0,'c',$borde);
    $pdf->Cell(20,6,$detalles->sumgrades,1,0,'C',$borde);//(Ancho,Alto,Textodentro de la celda,borde,salto de pagina,textcentrado,fondo,)
    //$pdf->Cell(40,6,date("Y-m-d",strtotime($detalles->fecha)),1,1,'C',$borde);
    $pdf->Cell(40,6,$detalles->fecha,1,1,'C',$borde);
    $contador=$contador+1;
}


$pdf->Output();

?>