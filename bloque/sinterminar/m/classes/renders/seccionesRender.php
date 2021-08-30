<?php
namespace block_mcdpde\renders;
use block_mcdpde\forms\nameFilter;
use block_mcdpde\models\QueryModel;

require_once("{$CFG->libdir}/formslib.php");
defined('MOODLE_INTERNAL') || die();

class seccionesRender implements \renderable
{
    private $table;

    private $title;
    private $pagesize;
    private $useinitialsbar;

    public function __construct(\table_sql $table, $title,$pagesize = 10, $useinitialsbar = true, $areaCode=1)
    {
        $this->table = $table;
        $this->title = $title;
        $this->useinitialsbar = $useinitialsbar;
    }

    public function display($idusuario,$viewFilter = false, $download = false)
    {
      global $DB;

        $filter= new nameFilter();
        $filter->display();

        $this->datosIndividuales($idusuario);
        $datos=new QueryModel();
        $empleado=$datos->getRecordUserEmpleado($idusuario);    
        //echo $datos->getUserEmpleadoSQL($idusuario);
        foreach($empleado as $individual){
            $nombre=$individual->nombre;
            $apellido=$individual->apellido;
            $puesto=$individual->desc_puesto;
            $cia=$individual->no_cia;
            $emp=$individual->no_emple;
        } 

        echo '<style>
            h1 { color: blue;}
            #hijo {display: table-cell;vertical-align: middle;color:red;}
            </style>';
 
        echo '<form action="../fileg/pdf/imprimir.php" method="POST">
        <div id="hijo"><label for="">Codigo:</label>
        <input type="text" readonly name="hola" value="'.$cia.$emp.'"></div>';
        echo '<form action="../fileg/pdf/imprimir.php" method="POST">
        <div id="hijo"><label for="">Nombre:</label>
        <input type="text" readonly name="nombre" value="'.$nombre." ".$apellido.'"></div>';
        echo '<form action="../fileg/pdf/imprimir.php" method="POST">
        <div id="hijo"><label for="">Puesto:</label>
        <input type="text" readonly name="puesto" value="'.$puesto.'"><br></div>';
        echo '<div id="hijo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
        echo '<div id="hijo"><img src="../fileg/img/logo.PNG" alt="Mc" title="Imagen iMc" width="110px" height="150px"/></div>';

        $this->table->out($this->pagesize, $this->useinitialsbar);

    }
    
    public function datosIndividuales($idusuario)
    {
        echo '<form action="seccionesimprimir.php" method="POST">
        <input type="hidden" name="userid" value="'.$idusuario.'">
        <input type="submit" value="Generar PDF"/>';
    }

    /**
     * Set the value of Title.
     *
     * @param string title
     *
     * @return self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the value of Pagesize.
     *
     * @param int pagesize
     *
     * @return self
     */
    public function setPagesize(int $pagesize)
    {
        $this->pagesize = $pagesize;

        return $this;
    }

    /**
     * Set the value of Useinitialsbar.
     *
     * @param bool useinitialsbar
     *
     * @return self
     */
    public function setUseinitialsbar(boolean $useinitialsbar)
    {
        $this->useinitialsbar = $useinitialsbar;

        return $this;
    }
}
