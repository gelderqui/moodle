<?php
use block_mcdpde\renders\seccionesRender;
use block_mcdpde\models\seccionesModel;
use block_mcdpde\tables\seccionesTable;
use block_mcdpde\models\QueryModel;

global $DB, $OUTPUT, $USER, $PAGE;

require_once('../../../config.php');
require_once("{$CFG->libdir}/tablelib.php");
require('../pdf/fpdf.php');
require_login();

$context=context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($CFG->wwwroot.'/blocks/mcdpde/boards/imprimir.php');
$viewURL= new moodle_url($CFG->wwwroot.'/blocks/mcdpde/boards/imprimir.php');

$userid = optional_param('userid', null, PARAM_INT);
$model=new seccionesModel();
$model->configureTest($userid);

$consulta=$model->consulta();
$datos1=new QueryModel();
$empleado=$datos1->getRecordUserEmpleado($userid);    
//var_dump($empleado);
foreach($empleado as $individual){
    $nombre=$individual->nombre;
    $apellido=$individual->apellido;
    $puesto=$individual->desc_puesto;
    $cia=$individual->no_cia;
    $emp=$individual->no_emple;
} 
$cod=$cia.$emp;

//$datos=json_decode(json_encode($consulta),true);
//var_dump($consulta);
//echo "salto";echo "salto";echo "salto";echo "salto";
//Datos encabezado

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../fileg/img/logo.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(30,10,utf8_decode('Corporación Macdonalds SA'),0,1,'C');
    $this->Cell(80);
    $this->Cell(30,10,'Universidad Mac',0,1,'C');
    $this->Cell(80);
    $this->Cell(30,10,utf8_decode('Certificación de cursos'),0,5,'C');
    // Salto de línea
    $this->Ln(20);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    //
    $this->Cell(0,10,utf8_decode('Esta certificacion solo es valida por un año.  Página').$this->PageNo().'/{nb}',0,1,'C');
    // Número de página
    //$this->Cell(0,10,utf8_decode('Página').$this->PageNo().'/{nb}',0,0,'C');
}
}



//var_dump($datos);
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(90,10,"Codigo: ".$cod,0,1,'c',0);
$pdf->Cell(90,10,"Nombre: ".$nombre." ".$apellido,0,1,'c',0);
$pdf->Cell(90,10,"Puesto: ".$puesto,0,1,'c',0);

foreach($consulta as $producto => $detalles)
{
    $pdf->Cell(90,10,$detalles->name,1,0,'c',0);
    $pdf->Cell(90,10,$detalles->sumgrades,1,1,'c',0);
}
$pdf->Output();
?>