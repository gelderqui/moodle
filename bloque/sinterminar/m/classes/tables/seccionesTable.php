<?php
namespace block_mcdpde\tables;
use block_mcdpde\models\abilitiesModel;
use block_mcdpde\helpers\levelabHelper;

require_once $CFG->libdir.'/tablelib.php';

class seccionesTable extends \table_sql
{
    public $columns;

    public function __construct($uniqueid, $download)
    {
        parent::__construct($uniqueid);

        $headers = array(
          get_string('course', 'block_mcdpde'),
          get_string('sumgrades','block_mcdpde'),
          get_string('fecha','block_mcdpde'));

        $this->columns = array('name', 'sumgrades','fecha');
        $this->define_columns($this->columns);
        $this->define_headers($headers);
        $this->sortable(false);
    }
    public function setModel(\block_mcdpde\models\seccionesModel $model)
    {
        $this->set_sql(
            $model->getQuerySelect(),
            $model->getQueryFrom(),
            $model->getQueryWhere(),
            $model->getQueryParams()
        );
    }

    public function col_fecha($values)
    {
        date_default_timezone_get("America/Guatemala");
        $a= date("Y")-1;
        $d= date("d");
        $m=date("m");
        $fechaanterior=$a."-".$m."-".$d;
        $fechaconvertida=date("Y-m-d",strtotime($values->fecha));
        $fecha=$values->fecha;
        if($fechaconvertida<=$fechaanterior)$fecha1='<div style="color:'.$CFG->block_mcdpde_expiredate.';font-weight: bold;">'.$fecha.'</div>';
        else $fecha1='<div style="color:#000000;font-weight: bold;">'.$fecha.'</div>';
        return $fecha1;
    }
    public function col_sumgrades($values)
    {
        global $CFG;

        $nota=$values->sumgrades;
        if($nota<90)$nota1='<div style="color:'.$CFG->block_mcdpde_expiredate.';font-weight: bold;">'.number_format($nota , 2, '.', '').'</div>';
        else $nota1='<div style="color:#000000;font-weight: bold;">'.number_format($nota , 2, '.', '').'</div>'; //number_format($número, 2, '.', '')
        return $nota1;
    }



    public function other_cols($colname, $value)
    {
        if (in_array($colname, $this->columns)) {
            return null;
        }
        return "not yet implement";
    }
}
 